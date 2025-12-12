<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List Of Booking 🌸') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-pink-500 py-12 text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="p-4 mb-4 text-green-800 bg-green-100 rounded-lg text-black">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Booking Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($upcomingBookings as $booking)
                    <div class="p-4 bg-gray-100 rounded-lg text-gray-900 shadow hover:shadow-lg transition-shadow duration-300">
                        <p><strong>Customer:</strong> {{ $booking->user->name }}</p>
                        <p><strong>Phone:</strong> {{ $booking->phone }}</p>
                        <p><strong>Treatment:</strong> {{ $booking->treatment->t_name }}</p>
                        <p><strong>Date:</strong> {{ $booking->date }}</p>
                        <p><strong>Time:</strong> {{ $booking->slotTime }}</p>

                        <form method="POST" action="{{ route('booking.done', $booking->BookingID) }}" class="mt-4">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-full">
                                Done
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

        </div>

        {{-- Footer --}}
        @include('layouts.footer')
    </div>
</x-app-layout>
