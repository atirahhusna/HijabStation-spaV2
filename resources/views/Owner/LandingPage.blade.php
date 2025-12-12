<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Owner Dashboard 🌸') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-bold mb-4 text-pink-700">Today's Bookings</h3>

                    @if ($todayBookings->isEmpty())
                        <p class="text-center text-gray-500 mb-6">No bookings scheduled for today.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($todayBookings as $booking)
                                <div class="bg-pink-100 rounded-lg p-4 shadow">
                                    <h3 class="font-semibold text-pink-700">Customer: {{ $booking->user->name }}</h3>
                                    <p class="text-gray-700">Phone: {{ $booking->phone }}</p>
                                    <p class="text-gray-700">Treatment: {{ $booking->treatment->t_name ?? 'N/A' }}</p>
                                    <p class="text-gray-700">Slot: {{ $booking->slotTime }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
    @include('layouts.footer')
    </div>

</x-app-layout>
