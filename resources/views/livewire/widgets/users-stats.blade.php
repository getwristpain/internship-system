<?php

use App\Models\User;
use App\Helpers\NumberFormatter;
use Livewire\Volt\Component;

new class extends Component {
    public $users;
    public array $usersStats = [];

    public function mount()
    {
        $this->loadUsersStats();
    }

    protected function loadUsersStats(): void
    {
        $this->loadAllUsers();
        $this->calculateUsersStats();
    }

    protected function loadAllUsers(): void
    {
        $this->users = User::with(['roles', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    protected function calculateUsersStats(): void
    {
        $roles = ['student', 'teacher', 'supervisor'];

        // Set total users count
        $this->usersStats['total_users'] = [
            'label' => 'Semua Pengguna',
            'count' => NumberFormatter::relative($this->users->count()),
            'icon' => 'mdi:account-group',
            'bgColor' => 'bg-green-400',
        ];

        // Calculate user stats for each role
        foreach ($roles as $role) {
            $count = $this->countUsersByRole($role);

            $this->usersStats["total_{$role}s"] = [
                'count' => NumberFormatter::relative($count),
                'label' => match ($role) {
                    'student' => 'Semua Siswa',
                    'teacher' => 'Semua Guru',
                    'supervisor' => 'Semua Supervisor',
                    default => 'Pengguna',
                },
                'icon' => match ($role) {
                    'student' => 'mdi:account-school',
                    'teacher' => 'mdi:account-tie',
                    'supervisor' => 'mdi:account-check',
                    default => 'mdi:account',
                },
                'bgColor' => match ($role) {
                    'student' => 'bg-blue-400',
                    'teacher' => 'bg-yellow-400',
                    'supervisor' => 'bg-red-400',
                    default => 'bg-gray-400',
                },
            ];
        }
    }

    protected function countUsersByRole(string $role): int
    {
        return $this->users
            ->filter(function ($user) use ($role) {
                return $user->hasRole($role);
            })
            ->count();
    }
};
?>

<div class="grid grid-cols-2 gap-4 md:grid-cols-4">
    @foreach ($usersStats as $role => $data)
        <x-card class="flex items-center h-full gap-4">
            <div class="w-12 h-12 aspect-square rounded-full {{ $data['bgColor'] }} flex justify-center items-center">
                <iconify-icon class="text-xl scale-125" icon="{{ $data['icon'] }}"></iconify-icon>
            </div>
            <div class="flex flex-col text-sm">
                <span class="text-xl">{{ $data['count'] ?? 0 }}</span>
                <span class="break-words text-wrap">{{ $data['label'] }}</span>
            </div>
        </x-card>
    @endforeach
</div>
