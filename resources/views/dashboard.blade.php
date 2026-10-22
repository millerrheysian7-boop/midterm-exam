<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Total accounts: <span id="statTotalAccounts">-</span></p>
<p>Total money in bank: <span id="statTotalMoney">-</span></p>
<p>Transactions today: <span id="statTotalTransactions">-</span></p>
<a href="{{ route('transactions.create') }}" class="text-indigo-600 underline">New transaction</a>
|
<a href="{{ route('transactions.index') }}" class="text-indigo-600 underline">All transactions</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<div class="p-6">
    <p>Total accounts: <span id="statTotalAccounts">-</span></p>
    <p>Total money: <span id="statTotalMoney">-</span></p>
    <p>Transactions today: <span id="statTotalTransactions">-</span></p>
</div>
<script src="{{ asset('js/dashboard-polling.js') }}"></script>
