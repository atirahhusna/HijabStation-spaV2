<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pink-50">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            <div class="mb-4 text-sm text-pink-700 font-semibold">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-pink-600" />

                    <x-text-input id="password" class="block mt-1 w-full border-pink-200 focus:border-pink-400 focus:ring-pink-300 rounded-md shadow-sm"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-500" />
                </div>

                <div class="flex justify-end mt-6">
                    <x-primary-button class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        {{ __('Confirm') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>