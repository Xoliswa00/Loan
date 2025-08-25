<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Review Application - #{{ $application->id }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-8">

            {{-- Applicant Details --}}
            <section>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-100">Applicant: {{ $application->user->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Submitted on: {{ $application->created_at->format('d M Y') }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Customer Code: {{ $application->user->customer->customer_code ?? 'N/A' }}</p>
            </section>

            {{-- Loan Information --}}
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Loan Amount</label>
                    <p class="text-xl font-bold text-green-700 dark:text-green-300">R{{ number_format($application->loan_amount, 2) }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Loan Purpose</label>
                    <p class="text-base dark:text-gray-200">{{ $application->purpose }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Application Status</label>
                    <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium 
                        {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($application->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </section>

            {{-- Loan Fees --}}
            @if($application->fees && $application->fees->count())
            <section>
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-3">Loan Fees Breakdown</h4>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2">interest_rate</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">initiation_fee</th>
                             <th class="px-4 py-2">service_fee</th>
                              <th class="px-4 py-2">total_due</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-600">
                        @foreach($application->fees as $fee)
                        <tr>
                            <td class="px-4 text-center py-2">{{ $fee->interest_rate }}</td>
                             <td class="px-4 text-center py-2">R{{ number_format($fee->interest_amount, 2) }}</td>
                              <td class="px-4 text-center py-2">{{ $fee->initiation_fee }}</td>
                               <td class="px-4 text-center py-2">{{ $fee->service_fee }}</td>
                            <td class="px-4 text-center py-2">R{{ number_format($fee->total_due, 2) }}</td>
                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            @endif

            {{-- Documents --}}
            <section>
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-3">Submitted Documents</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($application->credit_score_report)
                        <x-admin.document-preview label="Credit Report" :path="$application->credit_score_report" />
                    @endif
                    @if($application->bank_statement)
                        <x-admin.document-preview label="Bank Statement" :path="$application->bank_statement" />
                    @endif
                    @if($application->payslips)
                        <x-admin.document-preview label="Payslip" :path="$application->payslips" />
                    @endif
                    @if($application->user->ID_copy)
                        <x-admin.document-preview label="ID Copy" :path="$application->user->ID_copy" />
                    @endif
                </div>
            </section>

            {{-- Outstanding / Previous Loans --}}
            @php
                $previousLoans = $application->user->loanApplications()->where('id', '!=', $application->id)->get();
                $hasOutstanding = $previousLoans->where('status', 'approved')->where('is_fully_paid', false);
                $rejected = $previousLoans->where('status', 'rejected');
            @endphp

            @if($previousLoans->count())
            <section>
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-3">Previous Loans</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Outstanding</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-600">
                            @foreach($previousLoans as $loan)
                            <tr>
                                <td class="px-4 py-2">#{{ $loan->id }}</td>
                                <td class="px-4 py-2">R{{ number_format($loan->loan_amount, 2) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($loan->status) }}</td>
                                <td class="px-4 py-2">
                                    @if($loan->status === 'approved' && !$loan->is_fully_paid)
                                        <span class="text-red-600 dark:text-red-400">Yes</span>
                                    @else
                                        <span class="text-green-600 dark:text-green-300">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $loan->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif

            @if($rejected->count())
            <section>
                <h4 class="text-md font-semibold text-red-600 dark:text-red-300 mb-2">⚠️ Previous Rejections</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300">
                    @foreach($rejected as $rej)
                        <li>#{{ $rej->id }} – {{ $rej->created_at->format('d M Y') }} ({{ $rej->reason ?? 'No reason given' }})</li>
                    @endforeach
                </ul>
            </section>
            @endif

            {{-- Admin Notes --}}
            <section>
                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-2">💬 Add Internal Note</h4>
                <form method="POST" action="{{ route('loan.store', $application->id) }}">
                    @csrf
                    <textarea name="note" rows="3" class="w-full p-2 rounded-md bg-gray-100 dark:bg-gray-700 text-sm" placeholder="Write a note about this application..."></textarea>
                    <button type="submit" class="btn btn-primary mt-2">💾 Save Note</button>
                </form>
            </section>

            {{-- Approve / Reject --}}
            @if($application->status === 'pending')
            <section class="flex flex-wrap gap-4 mt-6">
                <form method="POST" action="{{ route('loans.approve', $application->id) }}">
                    @csrf
                    <button class="btn btn-success">✅ Approve</button>
                </form>

                <form method="POST" action="{{ route('loans.reject', $application->id) }}">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="Rejection reason..." required class="input input-bordered w-64">
                    <button class="btn btn-danger ml-2">❌ Reject</button>
                </form>
            </section>
            @endif

        </div>
    </div>
</x-app-layout>
