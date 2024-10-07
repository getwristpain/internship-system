@props(['users', 'identifier'])

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    <!-- Search Input -->
    <div class="flex items-center justify-between gap-4">
        <div class="grow">
            <x-input-text name="search" type="text" model="search" placeholder="Cari berdasarkan nama atau email..." />
        </div>
        <div>
            <button class="btn btn-neutral"
                @click="$dispatch('open-add-user-modal', {identifier: '{{ $identifier }}'})">
                + Tambah Baru
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <!-- Table Header -->
            <thead>
                <tr>
                    <th>Akun</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody>
                @forelse ($users as $user)
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-200 {{ collect($user['roles'])->pluck('name')->contains('owner')? 'italic font-medium bg-gray-100': '' }}"
                        :key="$user['id']">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="w-12 h-12 rounded-full">
                                        @if (optional($user['profile'])->avatar)
                                            <img class="no-drag no-select" src="{{ $user['profile']['avatar'] }}"
                                                alt="Avatar" />
                                        @else
                                            <x-no-image class="opacity-20" />
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span class="font-bold">{{ $user['name'] }}</span>
                                    <span class="block text-sm text-gray-500">{{ $user['email'] }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- Display user's role -->
                            <div class="badge badge-outline badge-neutral">
                                {{ optional($user['roles']->first())->name ? Str::title($user['roles']->first()->name) : 'N/A' }}
                            </div>
                        </td>

                        <td>
                            <!-- Display user's status -->
                            <div class="{{ $user['status']['badgeClass'] }}">
                                {{ Str::title(optional($user['status'])->name) ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            @unless (collect($user['roles'])->pluck('name')->contains('owner'))
                                <!-- Actions -->
                                <button
                                    @click="$dispatch('open-edit-user-modal', { userId: '{{ $user['id'] }}', identifier: '{{ $identifier }}' })"
                                    class="btn btn-sm btn-outline btn-neutral">
                                    <iconify-icon icon="mdi:edit"></iconify-icon>
                                    <span class="hidden md:inline-block">Edit</span>
                                </button>
                                <button
                                    @click="$dispatch('open-delete-user-modal', { userId: '{{ $user['id'] }}', identifier: '{{ $identifier }}' })"
                                    class="btn btn-sm btn-outline btn-error">
                                    <iconify-icon icon="mdi:delete"></iconify-icon>
                                    <span class="hidden md:inline-block">Hapus</span>
                                </button>
                            @else
                                <span class="italic font-medium opacity-80">Owner</span>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500">Tidak ada pengguna yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>

            <!-- Table Footer -->
            <tfoot>
                <tr>
                    <th>Akun</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <!-- Pagination -->
        @if ($users->isNotEmpty())
            <div>{{ $users->links() }}</div>
        @endif
    </div>

    <!-- Modals -->
    @livewire('user-manager.add-user-modal')
    @livewire('user-manager.edit-user-modal')
    @livewire('user-manager.delete-user-modal')
</div>
