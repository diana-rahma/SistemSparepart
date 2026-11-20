<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Masuk ke Sistem</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Silakan masuk menggunakan akun Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-md text-sm border border-green-200 dark:border-green-800 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-md text-sm border border-red-200 dark:border-red-800 font-medium">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @else
                <span></span>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Belum punya akun pembeli?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 underline ms-1">
                Daftar Akun Baru
            </a>
        </p>
    </div>
</x-guest-layout>
