<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Available Treatments 🌸') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-pink-100 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @forelse ($treatments as $treatment)
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-4 flex flex-col justify-between">
                                @if ($treatment->t_pic)
                                    <img src="{{ asset('storage/' . $treatment->t_pic) }}"
                                         alt="{{ $treatment->t_name }}"
                                         class="w-full h-32 object-cover rounded-md mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-300 rounded-md mb-2 flex items-center justify-center text-gray-500">
                                        No Image
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $treatment->t_name }}
                                    </h3>
                                    <p class="text-gray-700 dark:text-gray-300 text-sm mt-2">
                                        {{ $treatment->t_desc }}<br>
                                        <strong>Price:</strong> RM{{ number_format($treatment->t_price, 2) }}<br>
                                        <strong>Duration:</strong> {{ $treatment->t_duration }}
                                    </p>
                                </div>

                                <!-- Booking Button -->
                                <div class="mt-4">
                                    <a href="{{ route('Customer.booking', $treatment->treatmentID) }}"
                                       class="block w-full text-center bg-pink-500 hover:bg-pink-600 text-white py-2 rounded-lg font-semibold transition">
                                        Book Treatment
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center col-span-3 text-gray-600">No treatments available at the moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
           <!-- Footer sticks to bottom -->
        @include('layouts.footer')
    
    </div>
</x-app-layout>
