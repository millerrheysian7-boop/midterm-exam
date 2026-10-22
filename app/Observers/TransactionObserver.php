<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Notifications\LowBalanceNotification;
use Illuminate\Support\Facades\DB;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $account = $transaction->account()->lockForUpdate()->first();

            if (! $account) {
                return;
            }

            $newBalance = match ($transaction->type) {
                'deposit' => $account->balance + $transaction->amount,
                'withdrawal', 'transfer' => $account->balance - $transaction->amount,
                default => $account->balance,
            };

            if (in_array($transaction->type, ['withdrawal', 'transfer']) && $newBalance < $account->minimum_balance) {
                throw new \RuntimeException(
                    "Transaction rejected: balance would fall below the minimum balance of {$account->minimum_balance}."
                );
            }

            $account->balance = $newBalance;
            $account->save();

            if ($account->balance <= $account->minimum_balance) {
                $this->notifyLowBalance($account);
            }
        });
    }

    protected function notifyLowBalance($account): void
    {
        $alreadyNotified = $account->user
            ->notifications()
            ->where('type', LowBalanceNotification::class)
            ->whereNull('read_at')
            ->where('data->account_id', $account->id)
            ->exists();

        if ($alreadyNotified) {
            return;
        }

        $account->user->notify(new LowBalanceNotification($account));
    }
}
