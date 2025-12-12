<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Set pink background -->
    <div class="py-12 bg-pink-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                 
                    <!-- Flash Message -->
                    @if(session('success') || $errors->any())
                        <div 
                            id="flash-message"
                            class="mb-4 p-4 rounded-md shadow 
                            {{ session('success') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                        >
                            @if(session('success'))
                                {{ session('success') }}
                            @endif

                            @if($errors->any())
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                  <!-- Main card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Add Treatment</h3>

                    <!-- Treatment Form -->
                    <form action="{{ route('Treatment.Store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Treatment Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Treatment Name</label>
                            <input type="text" name="name" id="name" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Price -->
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (RM)</label>
                            <input type="number" name="price" id="price" step="0.01" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Duration -->
                        <div class="mb-4">
                            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (e.g., 1 hour)</label>
                            <input type="text" name="duration" id="duration" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>

                        <!-- Picture Upload -->
                        <div class="mb-6">
                            <label for="picture" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Picture</label>
                            <input type="file" name="picture" id="picture" accept="image/*" required
                                   class="mt-1 block w-full text-sm text-gray-700 dark:text-white file:bg-pink-100 file:border-0 file:px-4 file:py-2 file:rounded-md file:text-pink-700 file:font-semibold hover:file:bg-pink-200">
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
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($slots as $index => $slot)
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="slot_{{ $index }}" name="slots[{{ $slot }}][enabled]" value="1"
                       class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500">
                <label for="slot_{{ $index }}" class="text-sm text-gray-700 dark:text-gray-300">{{ $slot }}</label>
                <input type="number" name="slots[{{ $slot }}][count]" min="1" max="15" value="1"
                       class="w-16 rounded-md border-gray-300 text-sm dark:bg-gray-700 dark:text-white"
                       placeholder="Count">
            </div>
        @endforeach
    </div>
    <p class="text-sm text-gray-500 mt-2">Select slot(s) and assign how many bookings (max 15 per slot).</p>
</div>


                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-pink-500 border border-transparent rounded-md font-semibold text-white hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-400">
                                Submit Treatment
                            </button>
                        </div>
                    </form>   

                </div>
            </div>
        </div>
         
    </div>
       <!-- Footer sticks to bottom -->
        @include('layouts.footer')
      
     <!-- Flash auto-hide script -->
    <script>
        setTimeout(function () {
            let flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.display = 'none';
            }
        }, 3000);
    </script>
</x-app-layout>


