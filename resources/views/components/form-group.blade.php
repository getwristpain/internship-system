@props([
    'action' => '',
])

<div {{ $attributes->merge(['class' => 'container py-4 max-w-xl mx-auto']) }}>
    <form wire:submit.prevent="{{ $action }}" class="flex flex-col gap-12">
        <div class="flex flex-col justify-center items-center gap-2 text-center">
            <!-- Form Headers --->
            {{ $formHeader }}
        </div>
        <div class="flex flex-col gap-4">
            <!-- Flash Message --->
            <x-flash-message />

            <!-- Form Inputs --->
            {{ $formInput }}
        </div>
        <div class="flex gap-4 justify-end items-center">
            <!-- Form Actions --->
            {{ $formAction }}
        </div>
    </form>
</div>
