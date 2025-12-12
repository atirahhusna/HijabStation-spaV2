<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Add User Form -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-10 p-6">
                <h3 class="text-2xl font-bold mb-6">Add New User</h3>

                <form method="POST" action="{{ route('owner.register.user') }}">
                    @csrf
                    <x-input-label value="Name" />
                    <x-text-input class="block mt-1 w-full" name="name" required />

                    <x-input-label value="Email" class="mt-4" />
                    <x-text-input class="block mt-1 w-full" name="email" type="email" required />

                    <x-input-label value="Role" class="mt-4" />
                    <select name="role" required
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-pink-400">
                        <option value="" disabled selected>Select Role</option>
                        <option value="staff">Staff</option>
                        <option value="owner">Owner</option>
                    </select>

                    <x-input-label value="Password" class="mt-4" />
                    <x-text-input class="block mt-1 w-full" name="password" type="password" required />

                    <x-input-label value="Confirm Password" class="mt-4" />
                    <x-text-input class="block mt-1 w-full" name="password_confirmation" type="password" required />

                    <div class="flex justify-end mt-6">
                        <x-primary-button class="bg-pink-500 hover:bg-pink-600">
                            Register User
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Owners Table -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-10 p-6">
                <h3 class="text-2xl font-bold mb-4">Owners</h3>
                <input type="text" id="owner-search" placeholder="Search Owners..." 
                    class="mb-4 rounded-md border-gray-300 px-4 py-2 w-full shadow-sm">
                <div id="owner-table">
                    @include('owner.user-table', ['users' => $owners])
                </div>
            </div>

            <!-- Staff Table -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-10 p-6">
                <h3 class="text-2xl font-bold mb-4">Staff</h3>
                <input type="text" id="staff-search" placeholder="Search Staff..." 
                    class="mb-4 rounded-md border-gray-300 px-4 py-2 w-full shadow-sm">
                <div id="staff-table">
                    @include('owner.user-table', ['users' => $staffs])
                </div>
            </div>

        </div>
        @include('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>

         // Flash Messages
        @if(session('success'))
            Swal.fire({
                title: 'Registration Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true,
                background: '#fff0f5',
                iconColor: '#0bd21fff'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true,
                background: '#ffe5e5',
                iconColor: '#dc2626'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                title: 'Validation Error!',
                html: `@foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach`,
                icon: 'warning',
                confirmButtonText: 'OK',
                background: '#fff4e5',
                iconColor: '#f97316'
            });
        @endif


        // Delete Confirmation
        function attachDeleteEvents() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    const name = form.querySelector('.delete-btn').dataset.name;
                    Swal.fire({
                        title: '⚠️ Terminate Account?',
                        html: `You are about to permanently remove <strong class="text-red-600">${name}</strong>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, terminate',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#9ca3af'
                    }).then(result => { if(result.isConfirmed) form.submit(); });
                });
            });
        }

        attachDeleteEvents();

        // Live Search Owners
        document.getElementById('owner-search').addEventListener('input', function(){
            let query = this.value;
            axios.get('{{ route("users.owners.ajax") }}', { params: { search: query } })
                .then(res => {
                    document.getElementById('owner-table').innerHTML = res.data;
                    attachDeleteEvents();
                });
        });

        // Live Search Staff
        document.getElementById('staff-search').addEventListener('input', function(){
            let query = this.value;
            axios.get('{{ route("users.staffs.ajax") }}', { params: { search: query } })
                .then(res => {
                    document.getElementById('staff-table').innerHTML = res.data;
                    attachDeleteEvents();
                });
        });

        // Handle pagination links dynamically
        document.addEventListener('click', function(e){
            if(e.target.closest('#owner-table .pagination a')){
                e.preventDefault();
                axios.get(e.target.href).then(res => {
                    document.getElementById('owner-table').innerHTML = res.data;
                    attachDeleteEvents();
                });
            }
            if(e.target.closest('#staff-table .pagination a')){
                e.preventDefault();
                axios.get(e.target.href).then(res => {
                    document.getElementById('staff-table').innerHTML = res.data;
                    attachDeleteEvents();
                });
            }
        });
    </script>
</x-app-layout>
