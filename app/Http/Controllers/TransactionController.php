<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with('account')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::orderBy('account_number')->get();

        return view('transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'type' => ['required', Rule::in(['deposit', 'withdrawal'])],
            'amount' => ['required', 'numeric', 'min:1'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $account = Account::findOrFail($validated['account_id']);

        if ($validated['type'] === 'withdrawal') {
            $resultingBalance = $account->balance - $validated['amount'];

            if ($resultingBalance < $account->minimum_balance) {
                return back()->withInput()->withErrors([
                    'amount' => 'Insufficient funds: this withdrawal would drop the balance below the required minimum of '
                        . number_format($account->minimum_balance, 2) . '.',
                ]);
            }
        }

        DB::transaction(function () use ($validated, $account) {
            Transaction::create([
                'account_id' => $account->id,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded successfully.');
    }
}
