<?php

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, On};

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';

    public function with()
    {
        return [
            'students' => $this->loadStudentsData(),
        ];
    }

    protected function loadStudentsData()
    {
        $students = User::role('student')
            ->with(['roles', 'status', 'profile', 'departments'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(20);

        if ($students->isEmpty()) {
            return collect();
        }

        return $students;
    }

    // Badge class based on user status
    public function statusBadgeClass(string $statusName): string
    {
        return match ($statusName) {
            'active' => 'badge badge-success',
            'pending' => 'badge badge-warning',
            'blocked' => 'badge badge-error',
            'suspended' => 'badge badge-warning',
            'deactivated' => 'badge badge-ghost',
            'guest' => 'badge badge-outline badge-neutral',
            default => 'badge',
        };
    }

    // Open Add Student Modal
    public function openAddStudentModal(): void
    {
        $this->dispatch('openAddStudentModal', true);
    }

    // Open Edit Student Modal
    public function openEditStudentModal(string $userId = ''): void
    {
        $this->dispatch('openEditStudentModal', true, $userId);
    }

    // Open Delete Student Modal
    public function openDeleteStudentModal(string $userId = ''): void
    {
        $this->dispatch('openDeleteStudentModal', true, $userId);
    }
}; ?>

<div class="w-full h-full">
    <x-card class="h-full">
        <!-- Header -->
        <x-slot name="header">
            Manajemen Siswa
        </x-slot>

        <!-- Toolbar -->
        <div class="flex space-x-8 justify-beetween">
            <div class="grow">
                <x-input-text name="search" type="text" model="search" placeholder="Cari berdasarkan nama atau email..."
                    custom="search" />
            </div>
            <div>
                <button class="btn btn-neutral" wire:click="openAddStudentModal">
                    + Tambah Siswa
                </button>
            </div>
        </div>

        <!-- Student Data -->
        <div>
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr class="transition duration-150 ease-in-out hover:bg-gray-200" :key="$student['id']">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-12 h-12 rounded-full">
                                            @if (optional($student['profile'])->avatar)
                                                <img class="no-drag no-select" src="{{ $student['profile']['avatar'] }}"
                                                    alt="Avatar" />
                                            @else
                                                <x-no-image class="opacity-20" />
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <span class="font-bold">{{ $student['name'] }}</span>
                                        <span class="block text-sm text-gray-500">{{ $student['email'] }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <!-- Display student's class -->
                                <div class="badge badge-outline badge-neutral">
                                    {{ $student['group'] ? $student['group'] : 'N/A' }}
                                </div>
                            </td>

                            <td>
                                <!-- Display student's department -->
                                <div class="badge badge-outline badge-neutral">
                                    {{ optional($student['departments']->first())->name ? optional($student['departments']->first())->name : 'N/A' }}
                                </div>
                            </td>

                            <td>
                                <!-- Display student's status -->
                                <div class="{{ $this->statusBadgeClass(optional($student['status'])->name) }}">
                                    {{ Str::title(optional($student['status'])->name) ?? 'N/A' }}
                                </div>
                            </td>

                            <td>
                                <!-- Actions -->
                                <button wire:click="openEditStudentModal('{{ $student['id'] }}')"
                                    class="btn btn-sm btn-outline btn-neutral">
                                    <iconify-icon icon="mdi:edit"></iconify-icon>
                                    <span class="hidden md:inline-block">Edit</span>
                                </button>
                                <button wire:click="openDeleteStudentModal('{{ $student['id'] }}')"
                                    class="btn btn-sm btn-outline btn-error">
                                    <iconify-icon icon="mdi:delete"></iconify-icon>
                                    <span class="hidden md:inline-block">Hapus</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500">Tidak ada siswa yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>Pengguna</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <!-- Pagination -->
            @if ($students->isNotEmpty())
                <div>{{ $students->links() }}</div>
            @endif
        </div>
    </x-card>

    @livewire('student-manager.add-student-modal')
    @livewire('student-manager.edit-student-modal')
    @livewire('student-manager.delete-student-modal')
</div>
