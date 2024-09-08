@props(['type' => 'info', 'message' => ''])

@php
    // Define alert classes based on type
    $alertClasses = [
        'info' => 'alert-info',
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'error' => 'alert-error',
        'default' => 'bg-gray-200 text-inherit border-gray-300',
    ];

    // Define SVG icon paths for each alert type
    $iconPaths = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' =>
            'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    ];

    // Select the appropriate icon and class based on the type
    $iconPath = $iconPaths[$type] ?? $iconPaths['info'];
    $alertClass = $alertClasses[$type] ?? $alertClasses['default'];
@endphp

@if ($message)
    <div x-data="{ open: true }" x-show="open" role="alert" class="alert {{ $alertClass }} rounded-md">
        @if ($iconPath)
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="w-6 h-6 stroke-current shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
                </svg>
            </div>
        @endif
        <div class="flex-1">
            <p>{{ $message }}</p>
        </div>
        <div>
            <button type="button" @click="open = false" class="scale-125 text-gray-500 hover:text-gray-700">
                <iconify-icon icon="mdi:close" />
            </button>
        </div>
    </div>
@endif
