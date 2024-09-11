<?php

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use WithPagination;

    public function with()
    {
        // Paginate users and eager load related roles, status, and profile
        $getPaginatedUsers = User::with(['roles', 'status', 'profile'])->paginate(20);

        return [
            'users' => $getPaginatedUsers,
        ];
    }

    public function placeholder()
    {
        return view('components.skeleton-loading');
    }
}; ?>

<div class="space-y-4 overflow-x-auto">
    <table class="table w-full table-zebra">
        <!-- head -->
        <thead>
            <tr>
                <th>Account</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Iterate over paginated users -->
            @foreach ($users as $user)
                <tr class="transition duration-150 ease-in-out hover:bg-gray-200">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 h-12 rounded-full">
                                    <!-- Display user avatar -->
                                    <img src="{{ $user['profile']['avatar'] ?? 'default-avatar.png' }}" alt="Avatar" />
                                </div>
                            </div>
                            <div>
                                <span class="font-bold">{{ $user['name'] }}</span>
                                <span class="block text-sm text-gray-500">{{ $user['email'] }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <!-- Display user's role -->
                        <div class="badge badge-outline badge-neutral">{{ $user['roles'][0]['name'] ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <!-- Display user's status -->
                        <div class="badge {{ $user['status']['name'] == 'Active' ? 'badge-success' : 'badge-error' }}">
                            {{ $user['status']['name'] ?? 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <!-- Actions -->
                        <button class="btn btn-sm btn-outline btn-neutral">Edit</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <!-- foot -->
        <tfoot>
            <tr>
                <th>Account</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </tfoot>
    </table>

    <div>
        {{ $users->links() }}
    </div>
</div>
