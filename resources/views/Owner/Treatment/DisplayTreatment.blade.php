<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Treatments') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Flash Message -->
            @if(session('success'))
                <div id="successMessage"
                    class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded transition-opacity duration-1000 ease-in-out">
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(() => {
                        const msg = document.getElementById('successMessage');
                        if (msg) {
                            msg.style.opacity = '0';
                            setTimeout(() => msg.style.display = 'none', 1000);
                        }
                    }, 3000);
                </script>
            @endif

            <!-- Main Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- GRID -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                        <!-- Add Treatment Card -->
                        <div onclick="location.href='{{ route('Owner.Treatment.AddTreatment') }}'"
                             class="cursor-pointer border-2 border-pink-400 border-dotted rounded-lg flex items-center justify-center h-56 md:h-64 bg-white hover:bg-pink-200 transition">
                            <span class="text-7xl md:text-8xl text-pink-500 font-light">+</span>
                        </div>

                        <!-- Treatments List -->
                        @forelse ($treatments as $treatment)
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-4 flex flex-col justify-between">

                                {{-- Treatment Image --}}
                                @if ($treatment->t_pic)
                                    <img src="{{ asset('storage/' . $treatment->t_pic) }}"
                                         class="w-full h-32 object-cover rounded-md mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-300 rounded-md mb-2 flex items-center justify-center text-gray-500">
                                        No Image
                                    </div>
                                @endif

                                {{-- Treatment Info --}}
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $treatment->t_name }}
                                    </h3>

                                    <p class="text-gray-700 dark:text-gray-300 text-sm mt-2">
                                        {{ Str::limit($treatment->t_desc, 70) }}<br>
                                        <strong>Price:</strong> RM{{ number_format($treatment->t_price, 2) }}<br>
                                        <strong>Duration:</strong> {{ $treatment->t_duration }}
                                    </p>
                                </div>

                                {{-- Buttons --}}
                                <div class="mt-4 flex justify-center gap-4">
                                    <a href="{{ route('Owner.Treatment.Edit', $treatment->treatmentID) }}"
                                       class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">
                                        Edit
                                    </a>

                                    <button onclick="confirmDelete({{ $treatment->treatmentID }})"
                                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 col-span-3 text-center">No treatments available.</p>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>

        <!-- Delete Modal -->
        <div id="deleteModal"
             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">

            <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 md:w-1/3">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Deletion</h2>
                <p class="text-gray-600 mb-6">Are you sure you want to delete this treatment?</p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-4">
                        <button type="button"
                                onclick="closeModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                            Cancel
                        </button>

                        <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                            Confirm Delete
                        </button>
                    </div>
                </form>
            </div>

        </div>

        @include('layouts.footer')

    </div>

    <script>
        function confirmDelete(id) {
            // Set the form action to match route
            document.getElementById('deleteForm').action = "{{ url('Owner/delete') }}/" + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
