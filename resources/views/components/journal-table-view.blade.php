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
                <td>{{ $journal['attachment'] ?? '' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">
                    <div class="flex items-center justify-center text-center text-gray-700">
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
