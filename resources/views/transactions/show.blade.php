@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Transaction Details</h1>
    <p><strong>ID:</strong> {{ $transaction->id }}</p>
    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
    <p><strong>Type:</strong> {{ ucfirst($transaction->type) }}</p>
    <p><strong>Date:</strong> {{ $transaction->transaction_date }}</p>
    <p><strong>Description:</strong> {{ $transaction->description ?? 'N/A' }}</p>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
