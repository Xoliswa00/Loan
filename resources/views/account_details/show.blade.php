<x-app-layout>
    <div class="container">
        <h1>Account Detail</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Account Holder: {{ $accountDetail->account_holder_name }}</h5>
                <p><strong>Bank Name:</strong> {{ $accountDetail->bank_name }}</p>
                <p><strong>Account Number:</strong> {{ $accountDetail->account_number }}</p>
                <p><strong>Account Type:</strong> {{ ucfirst($accountDetail->account_type) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($accountDetail->payment_method) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($accountDetail->status) }}</p>

                <a href="{{ route('account_details.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</x-app-layout>
