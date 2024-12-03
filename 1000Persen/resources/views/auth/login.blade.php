<x-guest-layout>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="text-4xl font-bold text-blue-600 hover:scale-110 transform transition-all-custom">LIBRAGE</a>
            <h2 class="mt-6 text-center text-2xl font-extrabold text-gray-900">
                {{ __('Log in to your account') }}
            </h2>   
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-900">
                    {{ __('create a new account') }}
                </a>
            </p>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="px-2 block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="px-2 block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-black shadow-sm" name="remember">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        {{ __('Remember me') }}
                    </label>
                </div>

                <div class="text-sm">
                    @if (Route::has('password.request'))
                        <a class="font-medium text-blue-600 hover:text-blue-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <x-primary-button onclick="window.location='{{ url('/') }}'" class="bg-blue-600 hover:bg-blue-700">
                    {{ __('Back') }}
                </x-primary-button>

                <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>