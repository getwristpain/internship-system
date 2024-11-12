@props([
    'program' => [],
    'cardClass' => '',
])

<x-card class="h-full min-h-40 {{ $cardClass }}">
    <x-slot name="content">
        <div class="flex flex-col gap-4">
            <div class="flex-1 min-h-40 flex flex-col gap-2 justify-between">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between gap-4">
                        <x-status label="Pending" className="text-yellow-500"></x-status>

                        @if (isset($program['year']))
                            <span class="badge badge-xs badge-outline">{{ $program['year'] }}</span>
                        @endif
                    </div>
                    <span class="text-lg font-medium text-gray-700">{{ $program['title'] ?? 'Tidak Ada Judul' }}</span>
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
