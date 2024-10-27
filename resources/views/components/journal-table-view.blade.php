@props(['journals' => []])

<div class="space-y-4">
    <div>
        <button wire:click="deleteSelectedJournalsConfirm" class="btn btn-sm btn-error btn-outline"
            {{ $this->countSelectedJournals === 0 ? 'disabled' : '' }}>
            Hapus Terpilih {{ $this->countSelectedJournals > 0 ? '(' . $this->countSelectedJournals . ')' : '' }}
        </button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
                </th>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Kehadiran</th>
                <th>Durasi</th>
                <th>Komentar</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($journals as $index => $journal)
                <tr>
                    <td>
                        <input type="checkbox" wire:model.live.debounce.250ms="selectedJournals"
                            value="{{ $journal['id'] }}">
                    </td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $journal['date'] ?? '' }}</td>
                    <td>{{ $journal['activity'] ?? '' }}</td>
                    <td>
                        <span
                            class="{{ \App\Helpers\StatusBadgeMapper::getStatusBadgeClass($journal['attendance']) }}">{{ Str::title(__('attendance.' . $journal['attendance'])) ?? '' }}</span>
                    </td>
                    <td>{{ $journal['duration'] ?? '' }}</td>
                    <td>{{ $journal['remarks'] ?? '' }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-2">
                            @if ($journal['attachment'])
                                <a href="{{ url($journal['attachment'] ?? '#') }}" target="__blank"
                                    class="btn btn-sm btn-outline">
                                    <iconify-icon icon="quill:attachment" class="scale-125"></iconify-icon>
                                </a>
                            @endif

                            <button class="btn btn-sm btn-outline" title="Edit"
                                wire:click="showAddOrEditJournalModal($journal['id'])">
                                <iconify-icon icon="tabler:edit" class="scale-125"></iconify-icon>
                            </button>

                            <button class="btn btn-sm btn-outline btn-error" title="Hapus"
                                wire:click="deleteJournalConfirmation({{ $journal['id'] }})">
                                <iconify-icon icon="mdi:trash" class="scale-125"></iconify-icon>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="flex items-center justify-center font-medium text-center text-gray-700">
                            <p>Tidak ada kegiatan ditemukan.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>
                    <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
                </th>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Kehadiran</th>
                <th>Durasi</th>
                <th>Komentar</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    @if ($journals->isNotEmpty())
        <div>{{ $journals->links() }}</div>
    @endif
</div>
