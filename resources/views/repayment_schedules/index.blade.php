@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Repayment Schedules</h1>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('repayment_schedules.create') }}" class="btn btn-primary mb-3">Create New Repayment Schedule</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Loan</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repaymentSchedules as $schedule)
                    <tr>
                        <td>{{ $schedule->loan->name }}</td>
                        <td>{{ $schedule->due_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($schedule->amount, 2) }}</td>
                        <td>{{ ucfirst($schedule->status) }}</td>
                        <td>
                            <a href="{{ route('repayment_schedules.show', $schedule->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('repayment_schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('repayment_schedules.destroy', $schedule->id) }}" method="POST" style="display: inline;">
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
