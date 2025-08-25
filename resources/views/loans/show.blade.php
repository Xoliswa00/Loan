@extends('layouts.app')

@section('title', 'Loan Application Details')

@section('content')
<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-semibold">Loan Application Details</h1>

    <div class="mt-6">
        <p><strong>User ID:</strong> {{ $loanApplication->user_id }}</p>
        <p><strong>Loan Type:</strong> {{ $loanApplication->loan_type }}</p>
        <p><strong>Loan Amount:</strong> {{ $loanApplication->loan_amount }}</p>
        <p><strong>Purpose:</strong> {{ $loanApplication->purpose }}</p>
        <p><strong>Collateral:</strong> {{ $loanApplication->collateral }}</p>
        <p><strong>Status:</strong> {{ $loanApplication->status }}</p>
        <p><strong>Approval Date:</strong> {{ $loanApplication->approval_date }}</p>
        <p><strong>Created At:</strong> {{ $loanApplication->created_at }}</p>
        <p><strong>Updated At:</strong> {{ $loanApplication->updated_at }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('loan_applications.edit', $loanApplication->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</a>
        <form action="{{ route('loan_applications.destroy', $loanApplication->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
        </form>
    </div>
</div>
@endsection
