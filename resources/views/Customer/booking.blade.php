<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book Treatment') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-pink-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Booking Info --}}
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4">Booking Details</h3>
                <input type="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                <input type="text" value="{{ Auth::user()->name }}" class="mt-2 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                <input type="text" value="{{ $treatment->t_name ?? 'N/A' }}" class="mt-2 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
            </div>

            {{-- Date Selection --}}
            <form method="GET" action="{{ route('Customer.booking', ['id' => $treatment->treatmentID]) }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Select Date</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" onchange="this.form.submit()" required>
                    <small class="text-sm text-red-500">Booking not allowed on Mondays.</small>
                </div>
            </form>

            {{-- Booking Form --}}
            @if($selectedDate)
                <form method="POST" action="{{ route('booking.store') }}" class="bg-white p-6 rounded-lg shadow">
                    @csrf
                    <input type="hidden" name="userID" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="treatmentID" value="{{ $treatment->treatmentID }}">
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="date" value="{{ $selectedDate }}">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>

                    {{-- Optional Staff Selection --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Choose Staff (Optional)</label>
                        <select name="staffName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="" selected>Any available staff</option>
                            @foreach($staffList as $staff)
                                <option value="{{ $staff->name }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Slots --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Available Slots on {{ $selectedDate }}</label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-2">
                            @forelse ($slotList as $slot)
                                <label class="slot-label flex justify-center items-center bg-pink-200 rounded-lg p-4 cursor-pointer select-none font-semibold">
                                    <input type="radio" name="slotTime" value="{{ $slot }}" class="hidden" required>
                                    <span>{{ $slot }}</span>
                                </label>
                            @empty
                                <div class="text-red-500">No slots available for this date.</div>
                            @endforelse
                        </div>
                    </div>

                    <button type="submit" class="mt-4 bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md">
                        Confirm Booking
                    </button>
                </form>
            @endif
        </div>

        @include('layouts.footer')
    </div>

    {{-- Styles --}}
    <style>
        .slot-label {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Highlight selected slot
        document.querySelectorAll('.slot-label input[type="radio"]').forEach(input => {
            input.addEventListener('change', () => {
                document.querySelectorAll('.slot-label').forEach(label => {
                    label.classList.remove('bg-pink-600', 'text-white', 'font-bold');
                    label.classList.add('bg-pink-200', 'text-black', 'font-semibold');
                });
                const checkedInput = document.querySelector('.slot-label input[type="radio"]:checked');
                if (checkedInput) {
                    checkedInput.parentElement.classList.add('bg-pink-600', 'text-white', 'font-bold');
                    checkedInput.parentElement.classList.remove('bg-pink-200', 'text-black', 'font-semibold');
                }
            });

            input.parentElement.addEventListener('keydown', e => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    input.checked = true;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });

        // SweetAlert2 Popups for Flash Messages
        @if(session('success'))
            Swal.fire({
                title: 'Booking Confirmed!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true,
                background: '#fff0f5',
                iconColor: '#0bd21fff',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Booking Failed!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true,
                background: '#ffe5e5',
                iconColor: '#dc2626',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            });
        @endif

        @if($errors->any())
            Swal.fire({
                title: 'Validation Error!',
                html: `@foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach`,
                icon: 'warning',
                confirmButtonText: 'OK',
                background: '#fff4e5',
                iconColor: '#f97316',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            });
        @endif
    </script>
</x-app-layout>
