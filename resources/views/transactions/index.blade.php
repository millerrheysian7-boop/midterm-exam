<x-app-layout>
       <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
<h2>Record Transaction</h2>

<h2>Transactions</h2>
<a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">+ Record Transaction</a>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr><th>Date</th><th>Account</th><th>Type</th><th>Amount</th><th>Reference #</th></tr>
    </thead>
    <tbody>
        @forelse ($transactions as $t)
            <tr>
                <td>{{ $t->created_at->format('M d, Y h:i A') }}</td>
                <td>{{ $t->account->account_number ?? 'Deleted' }}</td>
                <td>{{ ucfirst($t->type) }}</td>
                <td>{{ $t->type === 'deposit' ? '+' : '-' }}{{ number_format($t->amount, 2) }}</td>
                <td>{{ $t->reference_number ?: '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center">No transactions yet.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $transactions->links() }}

       </div></div>
   </x-app-layout>
