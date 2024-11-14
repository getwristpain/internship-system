@props([
    'program' => [],
])

<x-card
    class="group h-full min-h-40 transition ease-in-out duration-150 cursor-pointer hover:shadow-xl hover:scale-105 {{ $program['cardClass'] ?? '' }}"
    wire:click="openProgram({{ $program['id'] ?? null }})">
    <x-slot name="content">
        <div class="flex flex-col gap-4">
            <div class="flex-1 min-h-40 flex flex-col gap-2 justify-between">
                <div class="flex flex-col gap-2 w-full">
                    <div class="flex items-center gap-4 min-h-2">
                        @if (isset($program['status']) && isset($program['statusClass']))
                            <x-status label="{{ $program['status'] }}"
                                className="{{ $program['statusCass'] }}"></x-status>
                        @endif
                        @if (isset($program['year']))
                            <span class="ml-auto badge badge-xs badge-outline">{{ $program['year'] }}</span>
                        @endif
                    </div>
                    <span
                        class="relative inline-block text-lg font-medium text-gray-700 transition ease-in-out duration-150
                        after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0
                        after:bg-gray-700 after:transition-all after:duration-300 group-hover:after:w-full">
                        {{ $program['title'] ?? 'Tidak Ada Judul' }}
                    </span>

                </div>

                <div class="flex flex-col text-gray-600">
                    <span class="text-2xl font-bold">{{ $program['total_students'] ?? 0 }}</span>
                    <span class="text-xs font-medium"> Siswa Terdaftar</span>
                </div>

                <div class="flex flex-col text-xs text-gray-600 font-medium gap-1">
                    <div class="flex gap-4 w-full justify-between">
                        <span>Mulai</span>
                        <span>{{ $program['date_start'] ?? '-' }}</span>
                    </div>
                    <div class="flex gap-4 w-full justify-between">
                        <span>Selesai</span>
                        <span>{{ $program['date_finish'] ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <button class="btn btn-outline w-full justify-between">
                    <span>Lihat</span>
                    <iconify-icon icon="icon-park-solid:right-c" class="text-2xl"></iconify-icon>
                </button>
            </div>
        </div>
    </x-slot>
</x-card>
