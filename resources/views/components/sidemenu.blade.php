@props([
    'menu' => [],
    'role' => 'Author',
    'title' => '',
])

<div class="sticky top-0 px-4 py-4">
    <div class="flex flex-col items-end w-full font-medium text-right">
        @if ($title)
            <span class="mt-4 mb-2 text-lg font-bold">{{ $title }}</span>
        @endif

        @role($role)
            <div class="flex flex-col divide-y min-w-40">
                @foreach ($menu as $label => $href)
                    <x-sidemenu-item :href="$href" :key="$label">{{ $label }}</x-sidemenu-item>
                @endforeach
            </div>
        @endrole
    </div>
</div>
