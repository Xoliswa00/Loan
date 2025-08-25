@section('title', 'Login - Liger Management System')

<x-guest-layout>

    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-100 to-gray-300 dark:from-gray-900 dark:to-gray-800">

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="/">
                <x-application-logo class="mx-auto h-16 w-auto fill-current text-blue-600 dark:text-blue-400" />
            </a>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white" data-aos="fade-down">
                Welcome Back to <span class="text-blue-600 dark:text-blue-400">Liger Management</span>
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300 max-w">
                Please sign in to access your loan dashboard and manage your clients.
            </p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="max-w-md mx-auto mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md" role="alert" data-aos="fade-down">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline"> There were some problems with your input:</span>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md" data-aos="fade-up" data-aos-delay="150">
            <form method="POST" action="{{ route('login') }}" class="bg-white dark:bg-gray-800 py-8 px-6 shadow-lg rounded-lg space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input
                        id="email"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-400 transition"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input
                        id="password"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-400 transition"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input
                        id="remember_me"
                        name="remember"
                        type="checkbox"
                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-800"
                    />
                    <label for="remember_me" class="ml-2 block text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                        {{ __('Remember me') }}
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ml-3 px-6 py-2 text-base">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>
