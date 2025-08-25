<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Account Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container mx-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-3xl font-bold">Account Details</h1>
                            <a href="{{ route('accountdetails.create') }}" class="bg-blue-500 text-white px-5 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                                Add Account Detail
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($accountDetails->isEmpty())
                            <p class="text-center text-gray-500 dark:text-gray-400">No account details found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-700 border border-gray-300 rounded-lg shadow-lg">
                                    <thead>
                                        <tr class="bg-gray-200 dark:bg-gray-900 text-gray-800 dark:text-gray-200 uppercase text-lg font-semibold">
                                            <th class="px-6 py-4 text-left">Account Holder</th>
                                            <th class="px-6 py-4 text-left">Bank Name</th>
                                            <th class="px-6 py-4 text-left">Account Number</th>
                                            <th class="px-6 py-4 text-left">Account Type</th>
                                            <th class="px-6 py-4 text-left">Payment Method</th>
                                            <th class="px-6 py-4 text-left">Status</th>
                                            <th class="px-6 py-4 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-800 dark:text-gray-100 text-sm font-light">
                                        @foreach ($accountDetails as $accountDetail)
                                            <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $accountDetail->account_holder_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $accountDetail->bank_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $accountDetail->account_number }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($accountDetail->account_type) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $accountDetail->payment_method }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($accountDetail->status) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex justify-center items-center space-x-2">
                                                        <a href="{{ route('accountdetails.edit', $accountDetail) }}" class="text-yellow-500 hover:underline" aria-label="Edit Account">Edit</a>
                                                        <form action="{{ route('accountdetails.destroy', $accountDetail) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')" aria-label="Delete Account">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
