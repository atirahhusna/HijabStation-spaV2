<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Treatment') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-200 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('Owner.Treatment.Update', $treatment->treatmentID) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Treatment Name</label>
                        <input type="text" name="name" value="{{ old('name', $treatment->t_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Price (RM)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $treatment->t_price) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Duration (hours)</label>
                        <input type="text" name="duration" value="{{ old('duration', $treatment->t_duration) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('description', $treatment->t_desc) }}</textarea>
                    </div>

                       <!-- Slot Availability -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Set Available Time Slots</label>

    @php
        $slots = [
            '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM',
            '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
            '12:00 PM', '02:00 PM', '02:30 PM', '03:00 PM',
            '03:30 PM', '04:00 PM', '04:30 PM',
        ];

        // Decode stored slotTime JSON (or array)
        $existingSlots = is_array($treatment->slotTime)
            ? $treatment->slotTime
            : (json_decode($treatment->slotTime, true) ?? []);
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($slots as $index => $slot)
            @php
                $isChecked = array_key_exists($slot, $existingSlots);
                $countValue = $isChecked ? $existingSlots[$slot] : 1;
            @endphp
            <div class="flex items-center space-x-2">
                <input
                    type="checkbox"
                    id="slot_{{ $index }}"
                    name="slots[{{ $slot }}][enabled]"
                    value="1"
                    {{ $isChecked ? 'checked' : '' }}
                    class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500"
                >
                <label for="slot_{{ $index }}" class="text-sm text-gray-700 dark:text-gray-300">{{ $slot }}</label>
                <input
                    type="number"
                    name="slots[{{ $slot }}][count]"
                    min="1"
                    max="15"
                    value="{{ $countValue }}"
                    class="w-16 rounded-md border-gray-300 text-sm dark:bg-gray-700 dark:text-white"
                    placeholder="Count"
                >
            </div>
        @endforeach
    </div>
    <p class="text-sm text-gray-500 mt-2">Select slot(s) and assign how many bookings (max 15 per slot).</p>
</div>


                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Current Picture</label><br>
                        @if ($treatment->t_pic)
                            <img src="{{ asset('storage/' . $treatment->t_pic) }}" class="w-40 h-auto mb-2 rounded">
                        @else
                            <p>No picture available.</p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Update Picture</label>
                        <input type="file" name="picture" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg">
                            Update Treatment
                        </button>
                    </div>
                </form>
            </div>
        </div>
           <!-- Footer sticks to bottom -->
        @include('layouts.footer')
    
    </div>
</x-app-layout>
