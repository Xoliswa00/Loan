<!-- resources/views/loan_interests/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Loan Interest Records</h1>
    <a href="{{ route('loan_interests.create') }}" class="btn btn-primary">Add Loan Interest</a>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Loan ID</th>
                <th>Interest Rate (%)</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loanInterests as $loanInterest)
                <tr>
                    <td>{{ $loanInterest->loan->id }}</td>
                    <td>{{ $loanInterest->interest_rate }}</td>
                    <td>{{ $loanInterest->start_date }}</td>
                    <td>{{ $loanInterest->end_date ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('loan_interests.show', $loanInterest) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('loan_interests.edit', $loanInterest) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('loan_interests.destroy', $loanInterest) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
