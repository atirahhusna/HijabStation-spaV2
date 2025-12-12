<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Booking 🌸') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-pink-100 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                   <h3 class="text-lg font-bold mb-2 text-gray-700">Booking History</h3>

@if ($pastBookings->isEmpty())
    <p class="text-center text-gray-500">No booking history found.</p>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($pastBookings as $booking)
            <div class="bg-gray-100 rounded-lg p-4 shadow border border-gray-300">
                <h3 class="font-semibold text-gray-800 mb-1">Name: <span class="font-normal">{{ $booking->name }}</span></h3>
                <p class="text-sm text-gray-600">Email: <span class="font-medium">{{ $booking->email }}</span></p>
                <p class="text-sm text-gray-600">Phone: <span class="font-medium">{{ $booking->phone }}</span></p>
                <p class="text-sm text-gray-600">Treatment: <span class="font-medium">{{ $booking->treatment->t_name ?? 'N/A' }}</span></p>
                <p class="text-sm text-gray-600">Date: <span class="font-medium">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</span></p>
                <p class="text-sm text-gray-600">Slot: <span class="font-medium">{{ $booking->slotTime }}</span></p>
                <div class="mt-2 text-xs text-gray-400 italic">Completed</div>
            </div>
        @endforeach
    </div>
@endif


                </div>
            </div>
        </div>
         <!-- Footer sticks to bottom -->
        @include('layouts.footer')
    
    </div>
</x-app-layout>
