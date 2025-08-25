@extends('layouts.app')

@section('title', 'Edit Loan Application')

@section('content')
<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-semibold">Edit Loan Application</h1>

    <form action="{{ route('loan_applications.update', $loanApplication->id) }}" method="POST" class="mt-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="loan_type" class="block text-gray-700">Loan Type</label>
            <input type="text" id="loan_type" name="loan_type" class="w-full px-4 py-2 border rounded-md" value="{{ $loanApplication->loan_type }}" required>
        </div>

        <div class="mb-4">
            <label for="loan_amount" class="block text-gray-700">Loan Amount</label>
            <input type="number" id="loan_amount" name="loan_amount" class="w-full px-4 py-2 border rounded-md" value="{{ $loanApplication->loan_amount }}" required>
        </div>

        <div class="mb-4">
            <label for="purpose" class="block text-gray-700">Purpose</label>
            <input type="text" id="purpose" name="purpose" class="w-full px-4 py-2 border rounded-md" value="{{ $loanApplication->purpose }}" required>
        </div>

        <div class="mb-4">
            <label for="collateral" class="block text-gray-700">Collateral</label>
            <input type="text" id="collateral" name="collateral" class="w-full px-4 py-2 border rounded-md" value="{{ $loanApplication->collateral }}">
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-700">Status</label>
            <select id="status" name="status" class="w-full px-4 py-2 border rounded-md" required>
                <option value="pending" {{ $loanApplication->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $loanApplication->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $loanApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="approval_date" class="block text-gray-700">Approval Date</label>
            <input type="date" id="approval_date" name="approval_date" class="w-full px-4 py-2 border rounded-md" value="{{ $loanApplication->approval_date }}">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md mt-4">Update</button>
    </form>
</div>
@endsection
