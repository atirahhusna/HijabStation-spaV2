<table class="w-full table-fixed border border-gray-400 rounded-lg">
    <thead class="bg-pink-100 border-b border-gray-400">
        <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold border border-gray-400">Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold border border-gray-400">Email</th>
            <th class="px-6 py-3 text-center text-sm font-semibold border border-gray-400">Role</th>
            <th class="px-6 py-3 text-center text-sm font-semibold border border-gray-400">Action</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @forelse($users as $user)
            <tr class="hover:bg-pink-50 transition">
                <td class="px-6 py-4 border border-gray-400">{{ $user->name }}</td>
                <td class="px-6 py-4 text-gray-600 truncate border border-gray-400">{{ $user->email }}</td>
                <td class="px-6 py-4 text-center border border-gray-400">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $user->role === 'owner' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center border border-gray-400">
                    <form method="POST" action="{{ route('owner.user.destroy', $user) }}" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" data-name="{{ $user->name }}"
                            class="delete-btn px-4 py-2 text-sm font-semibold text-white bg-red-500 rounded-md hover:bg-red-600 transition">
                            Terminate
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-6 text-center text-gray-500 border border-gray-400">
                    No users found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $users->links() }}
</div>
