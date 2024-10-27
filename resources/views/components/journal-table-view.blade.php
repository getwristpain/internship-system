@props(['journals' => []])

<table class="table">
    <thead>
        <tr>
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
                            @click="$dispatch('openAddOrEditJournalModal', {journalId: {{ $journal['id'] }}})">
                            <iconify-icon icon="tabler:edit" class="scale-125"></iconify-icon>
                        </button>

                        <button class="btn btn-sm btn-outline btn-error" title="Hapus"
                            @click="$dispatch('openDeleteJournalModal', {journalId: {{ $journal['id'] }}})">
                            <iconify-icon icon="mage:trash-fill" class="scale-125"></iconify-icon>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">
                    <div class="flex items-center justify-center font-medium text-center text-gray-700">
                        <p>Tidak ada kegiatan ditemukan.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
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
