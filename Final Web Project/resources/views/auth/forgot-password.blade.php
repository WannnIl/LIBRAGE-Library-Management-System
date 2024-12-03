<x-guest-layout>
    <div class="mb-4 text-sm font-semibold text-black ">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <!-- Tombol Back -->
            <a href="{{ route('login') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-800 active:bg-blue-900">
                {{ __('Back') }}
            </a>
        
            <!-- Tombol Email Password Reset Link -->
            <x-primary-button class="bg-green-600 hover:bg-green-700 focus:bg-green-800 active:bg-green-900">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
        
    </form>
</x-guest-layout>
