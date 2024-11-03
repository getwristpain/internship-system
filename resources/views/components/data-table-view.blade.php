@props([
    'headers' => [],
    'data' => [],
    'props' => [],
    'countSelectedItems' => 0,
    'searchPlaceholder' => 'Cari...',
])

<div class="flex flex-col gap-4">
    <div class="flex gap-4 justify-end items-center">
        <x-input-form type="search" name="search" model="search" placeholder="{{ $searchPlaceholder }}"></x-input-form>

        <button class="btn btn-neutral" @click="$dispatch('add-item-action')">
            + Tambah Baru
        </button>
    </div>
    <div class="flex flex-col w-full h-full gap-4">
        <div>
            <button @click="$dispatch('bulk-delete-action')" class="btn btn-sm btn-error btn-outline"
                :disabled="{{ $countSelectedItems === 0 ? 'true' : 'false' }}">
                Hapus Terpilih {{ $countSelectedItems > 0 ? "($countSelectedItems)" : '' }}
            </button>
        </div>

        <table class="table w-full">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
                    </th>
                    <th>No</th>
                    @foreach ($headers as $header)
                        <th>{{ ucwords(str_replace('_', ' ', $header['text'] ?? $header)) }}</th>
                    @endforeach
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)
                    <tr class="hover:bg-gray-100">
                        <td>
                            <input type="checkbox" wire:model.live="selectedItems" value="{{ $item['id'] ?? '' }}">
                        </td>
                        <td>{{ $index + 1 }}</td>
                        @foreach ($headers as $key)
                            @continue($key['key'] === 'id')
                            <td>{{ $item[$key['key']] ?? '-' }}</td>
                        @endforeach
                        <td>
                            @if (empty($props['hideEditAction']))
                                <button class="btn btn-sm btn-outline" title="Edit"
                                    @click="$dispatch('edit-item-action', [{{ $item['id'] ?? 'null' }}])">
                                    <iconify-icon icon="tabler:edit" class="scale-125"></iconify-icon>
                                </button>
                            @endif

                            @if (empty($props['hideDeleteButton']))
                                <button class="btn btn-sm btn-outline btn-error" title="Hapus"
                                    @click="$dispatch('delete-item-action', [{{ $item['id'] ?? 'null' }}])">
                                    <iconify-icon icon="mdi:trash" class="scale-125"></iconify-icon>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) + 3 }}" class="text-center">
                            <span class="text-gray-700">Tidak ada data tersedia.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>
                        <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
                    </th>
                    <th>No</th>
                    @foreach ($headers as $header)
                        <th>{{ ucwords(str_replace('_', ' ', $header['text'] ?? $header)) }}</th>
                    @endforeach
                    <th>Aksi</th>
                </tr>
            </tfoot>
        </table>

        @if (method_exists($data, 'link') && $data->isNotEmpty())
            <div>{{ $data->links() }}</div>
        @endif
    </div>
</div>
