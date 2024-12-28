<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="h-full w-full">
    <div class="grid grid-cols-4 gap-4">
        <!-- School Profile Section -->
        <div class="col-span-4 lg:col-span-3">
            @livewire('dashboards.components.school-profile-card')
        </div>

        <!-- App Info Section -->
        <div class="col-span-4 lg:col-span-1 -order-1 md:order-none">
            @livewire('dashboards.components.app-info-card')
        </div>

        <!-- User Stats Section -->
        <div class="col-span-4">
            @livewire('dashboards.components.user-stats')
        </div>
    </div>
</div>
