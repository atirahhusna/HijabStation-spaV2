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
                    <h3 class="text-lg font-bold mb-6 text-pink-700">Upcoming Bookings</h3>

                    @if ($upcomingBookings->isEmpty())
                        <p class="text-center text-gray-500 mb-6">No upcoming bookings found.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-10">
                            @foreach ($upcomingBookings as $booking)
                                <div class="bg-pink-100 rounded-lg p-4 shadow">
                                    <h3 class="font-semibold text-pink-700">Name: {{ $booking->name }}</h3>
                                    <p class="text-gray-700">Email: {{ $booking->email }}</p>
                                    <p class="text-gray-700">Phone: {{ $booking->phone }}</p>
                                    <p class="text-gray-700">Treatment: {{ $booking->treatment->t_name ?? 'N/A' }}</p>
                                    <p class="text-gray-700">Date: {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</p>
                                    <p class="text-gray-700">Slot: {{ $booking->slotTime }}</p>

                                    @if (\Carbon\Carbon::parse($booking->date)->gt(\Carbon\Carbon::today()))
                                        <form method="POST" action="{{ route('customer.booking.cancel', $booking->BookingID) }}" class="cancel-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="mt-3 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded w-full cancel-button"
                                                data-name="{{ $booking->treatment->t_name ?? 'this booking' }}">
                                                Cancel Booking
                                            </button>
                                        </form>
                                    @endif
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fancy Alert for Session Messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#ec4899',
                background: '#fff0f5',
                customClass: { popup: 'rounded-xl shadow-lg' }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ec4899',
                background: '#fff0f5',
                customClass: { popup: 'rounded-xl shadow-lg' }
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Please fix the following:',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonColor: '#ec4899',
                background: '#fff0f5',
                customClass: { popup: 'rounded-xl shadow-lg' }
            });
        @endif

        // Confirmation for Cancel Button
        document.querySelectorAll('.cancel-button').forEach(button => {
            button.addEventListener('click', function (e) {
                const form = this.closest('.cancel-form');
                const bookingName = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Cancel ${bookingName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, cancel it!',
                    background: '#ffffffff',
                    customClass: { popup: 'rounded-xl shadow-lg' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
  
</x-app-layout>
