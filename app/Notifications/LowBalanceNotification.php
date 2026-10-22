<?php

namespace App\Notifications;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowBalanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Account $account)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Balance Alert: Account ' . $this->account->account_number)
            ->line("Your account {$this->account->account_number} balance has fallen below the minimum balance.")
            ->line('Current balance: ' . number_format($this->account->balance, 2))
            ->line('Minimum balance: ' . number_format($this->account->minimum_balance, 2));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'account_id' => $this->account->id,
            'account_number' => $this->account->account_number,
            'balance' => $this->account->balance,
            'minimum_balance' => $this->account->minimum_balance,
            'message' => "Account {$this->account->account_number} is below the minimum balance.",
        ];
    }
}
