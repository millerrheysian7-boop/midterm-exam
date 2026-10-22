<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_accounts' => Account::count(),
            'total_money_in_bank' => round(Account::sum('balance'), 2),
            'total_transactions_today' => Transaction::whereDate('created_at', today())->count(),
            'generated_at' => now()->toIso8601String(),
        ]);
    }
}
