<?php

use App\Models\User;
use Illuminate\View\View;
use Livewire\Volt\Component;
use Asantibanez\LivewireCharts\Models\PieChartModel;

new class extends Component {
    public $users;

    public function mount()
    {
        $this->users = $this->loadUsersData();
    }

    protected function loadUsersData()
    {
        return User::with('roles', 'status')->join('statuses', 'statuses.id', '=', 'users.status_id')->select('users.*')->get();
    }

    public function rendering(View $view)
    {
        return $view->with([
            'activeUsersData' => $this->generateUserData('active', 'Pengguna Aktif', 'mdi:account-online', 'bg-green-400'),
            'guestUsersData' => $this->generateUserDataWithRole('guest', 'Pengguna Tamu', 'fluent:guest-12-filled', 'bg-gray-200'),
            'usersByStatusPieChartModel' => $this->generatePieChart(),
        ]);
    }

    protected function generateUserData(string $statusName, string $label, string $icon, string $bgColor)
    {
        $count = $this->users->where('status.name', $statusName)->count() ?? 0;

        return [
            'bgColor' => $bgColor,
            'icon' => $icon,
            'count' => $count,
            'label' => $label,
        ];
    }

    protected function generateUserDataWithRole(string $roleName, string $label, string $icon, string $bgColor)
    {
        // Count the users with the specified role
        $count = $this->users
            ->filter(function ($user) use ($roleName) {
                return $user->hasRole($roleName);
            })
            ->count(); // Use filter to count users with the specified role

        return [
            'bgColor' => $bgColor,
            'icon' => $icon,
            'count' => $count,
            'label' => $label,
        ];
    }

    protected function generatePieChart()
    {
        $pieChartModel = (new PieChartModel())->setTitle('Pengguna Berdasarkan Status');

        $this->users->groupBy('status.name')->each(function ($users, $status) use ($pieChartModel) {
            $pieChartModel->addSlice(ucfirst($status), $users->count() ?? 0, $this->getStatusColor($status));
        });

        return $pieChartModel;
    }

    protected function getStatusColor($status)
    {
        return match ($status) {
            'active' => '#22c55e', // Green
            'verified' => '#3b82f6', // Blue
            'pending' => '#f59e0b', // Yellow
            'blocked' => '#ef4444', // Red
            'suspended' => '#f97316', // Orange
            'deactivated' => '#6b7280', // Gray
            'guest' => '#a3a3a3', // Neutral Gray
            default => '#6c757d', // Default Gray
        };
    }
};

?>

<div class="flex flex-col w-full h-full gap-4">
    <div class="flex flex-row gap-4 md:flex-col">
        {{-- Jumlah pengguna aktif --}}
        <x-card>
            <div class="flex items-center h-full gap-4 flex-nowrap">
                <div
                    class="w-12 h-12 aspect-square rounded-full {{ $activeUsersData['bgColor'] }} flex justify-center items-center">
                    <iconify-icon class="text-xl scale-125" icon="{{ $activeUsersData['icon'] }}"></iconify-icon>
                </div>
                <div class="flex flex-col text-sm">
                    <span class="text-xl">{{ $activeUsersData['count'] ?? 0 }}</span>
                    <span class="break-words text-wrap">{{ $activeUsersData['label'] }}</span>
                </div>
            </div>
        </x-card>

        {{-- Jumlah pengguna tamu --}}
        <x-card>
            <div class="flex items-center h-full gap-4 flex-nowrap">
                <div
                    class="w-12 h-12 aspect-square rounded-full {{ $guestUsersData['bgColor'] }} flex justify-center items-center">
                    <iconify-icon class="text-xl scale-125" icon="{{ $guestUsersData['icon'] }}"></iconify-icon>
                </div>
                <div class="flex flex-col text-sm">
                    <span class="text-xl">{{ $guestUsersData['count'] ?? 0 }}</span>
                    <span class="break-words text-wrap">{{ $guestUsersData['label'] }}</span>
                </div>
            </div>
        </x-card>
    </div>

    {{-- Pie chart untuk pengguna berdasarkan status --}}
    <x-card class="flex-1 w-full h-full">
        @livewire('livewire-pie-chart', ['pieChartModel' => $usersByStatusPieChartModel])
    </x-card>
</div>
