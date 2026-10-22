   <x-app-layout>
       <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
<h2>Record Transaction</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('transactions.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Account</label>
        <select name="account_id" class="form-select">
            @foreach ($accounts as $acc)
                <option value="{{ $acc->id }}" @selected(old('account_id') == $acc->id)>
                    {{ $acc->account_number }} (Balance: {{ number_format($acc->balance, 2) }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select">
            <option value="deposit" @selected(old('type') == 'deposit')>Deposit</option>
            <option value="withdrawal" @selected(old('type') == 'withdrawal')>Withdrawal</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" min="1" name="amount" class="form-control" value="{{ old('amount') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Reference Number</label>
        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save Transaction</button>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>

       </div></div>
   </x-app-layout>

