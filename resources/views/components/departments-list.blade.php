@props([
    'departments' => null,
])

<div class="flex flex-col h-full gap-4">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2 font-bold">
            <span class="text-neutral-500">Jurusan</span>
            <span class="p-1 text-xs rounded-full bg-neutral-100 text-neutral-500">{{ $departments->count() }}</span>
        </div>
    </div>

    @foreach ($departments as $department)
        <div class="flex flex-col gap-4 p-4 border shadow-sm rounded-xl">
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <span class="font-medium">{{ $department->name }}</span>
                    <div class="flex text-xs">
                        <x-button-link action="$dispatch('open-classroom-form-modal', ['{{ $department->id }}'])"
                            class="text-neutral-500 hover:text-neutral-900">Tambah Kelas</x-button-link>
                        <span class="divider divider-horizontal"></span>
                        <x-button-link action="$dispatch('delete-department', ['{{ $department->id }}'])" class="text-red-500 hover:text-red-900">Hapus Jurusan</x-button-link>
                    </div>
                </div>
                <x-button-link action="$dispatch('show-department-classrooms')"
                    icon="{{ $department->classrooms->count() !== 0 ? 'mingcute:down-fill' : '' }}"
                    class="text-neutral-500 hover:text-neutral-900">
                    <span>{{ $department->classrooms->count() }} Kelas</span>
                </x-button-link>
            </div>
        </div>
    @endforeach

    <div class="flex flex-col gap-4 p-4 border shadow-sm rounded-xl">
        <div class="flex items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <span class="font-medium">Teknik Sistem Informasi Jaringan dan Aplikasi</span>
                <div class="flex text-xs">
                    <x-button-link class="text-neutral-500 hover:text-neutral-900">Tambah Kelas</x-button-link>
                    <span class="divider divider-horizontal"></span>
                    <x-button-link class="text-red-500 hover:text-red-900">Hapus Jurusan</x-button-link>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-neutral-500">
                <span>2 Kelas</span>
                <iconify-icon icon="mingcute:down-fill" class="scale-125" />
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-4 p-4 border shadow-sm rounded-xl">
        <div class="flex items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <span class="font-medium">Teknik Konstruksi Gedung Sanitasi dan Perawatan</span>
                <div class="flex text-xs">
                    <x-button-link class="text-neutral-500 hover:text-neutral-900">Tambah Kelas</x-button-link>
                    <span class="divider divider-horizontal"></span>
                    <x-button-link class="text-red-500 hover:text-red-900">Hapus Jurusan</x-button-link>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-neutral-500">
                <span>2 Kelas</span>
                <iconify-icon icon="mingcute:down-fill" class="scale-125 rotate-180" />
            </div>
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between gap-4 p-4 border rounded-xl">
                <span>Kelas A</span>
                <x-button icon="mdi:trash" class="text-red-500 btn-sm btn-error btn-outline"></x-button>
            </div>
            <div class="flex items-center justify-between gap-4 p-4 border rounded-xl">
                Kelas B
                <x-button icon="mdi:trash" class="text-red-500 btn-sm btn-error btn-outline"></x-button>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center">
        <x-button action="$dispatch('open-department-form-modal')"
            class="w-full border-2 border-dashed border-neutral-400 bg-neutral-100 hover:bg-neutral-200 text-neutral-500">
            Tambah Jurusan
        </x-button>
    </div>

    @livewire('components.departments.department-form-modal', key(App\Helpers\Formatter::uniqid('department-key')))
    @livewire('components.classrooms.classroom-form-modal', key(App\Helpers\Formatter::uniqid('classroom-key')))
</div>
