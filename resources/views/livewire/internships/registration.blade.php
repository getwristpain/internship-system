<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="grid grid-cols-12 gap-4">
    <div class="col-span-4 flex flex-col gap-4 h-full">
        <x-card>
            <div class="flex gap-4 items-center">
                <div class="h-full square">
                    <iconify-icon
                        class="flex justify-items-center h-full square bg-slate-100 text-slate-300 text-4xl p-2 rounded-xl"
                        icon="mage:image-cross"></iconify-icon>
                </div>
                <div>
                    <span class="font-medium line-clamp-1">Desainer Grafis CV. Grapik Indie</span>
                    <div class="flex flex-col w-full text-xs">
                        <p>CV. Grapik Indie</p>
                        <p>Klaten Utara (WFO)</p>
                    </div>
                    <div class="text-[0.7rem] text-gray-600">6 Bulan</div>
                </div>
            </div>
        </x-card>
        <x-card>
            <div class="flex gap-4 items-center">
                <div class="h-full square">
                    <iconify-icon
                        class="flex justify-items-center h-full square bg-slate-100 text-slate-300 text-4xl p-2 rounded-xl"
                        icon="mage:image-cross"></iconify-icon>
                </div>
                <div>
                    <span class="font-medium line-clamp-1">Desainer Grafis CV. Grapik Indie</span>
                    <div class="flex flex-col w-full text-xs">
                        <p>CV. Grapik Indie</p>
                        <p>Klaten Utara (WFO)</p>
                    </div>
                    <div class="text-[0.7rem] text-gray-600">6 Bulan</div>
                </div>
            </div>
        </x-card>
    </div>
    <x-card class="col-span-8 h-full min-h-96">
        <x-slot name="heading">
            Pendaftaran Magang
        </x-slot>
    </x-card>
</div>
