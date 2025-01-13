<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Arahkan ke langkah berikutnya.
     *
     * @return void
     */
    public function next(): void
    {
        // Arahkan ke langkah pertama instalasi
        $this->redirect(route('install.step1'), navigate: true);
    }
};

?>

<div class="size-full space-y-12">
    <div class="flex flex-col md:flex-row gap-20">
        <div class="w-full flex justify-center items-center">
            <img src="{{ asset('img/welcome_4207.png') }}" alt="Welcome" class="w-3/4 h-auto">
        </div>
        <div class="w-full flex flex-col gap-4 text-neutral-900">
            <div>
                <p class="text-sm font-semibold text-gray-600">Selamat datang di</p>
                <h1 class="text-4xl font-extrabold text-yellow-500">Sistem Informasi Manajemen Praktik Kerja Lapangan
                    (PKL)!</h1>
                <p class="text-lg font-semibold text-gray-800">Platform yang memudahkan pengelolaan dan optimasi
                    pengalaman PKL Anda.</p>
            </div>
            <div class="flex flex-col gap-4">
                <div class="flex gap-4 items-center">
                    <iconify-icon icon="mdi:clipboard-check-outline" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Pengelolaan PKL yang Mudah</p>
                        <p class="text-sm text-gray-700">Pantau, kelola, dan atur PKL secara real-time hanya dengan
                            beberapa
                            klik. Organisasi data siswa, perusahaan, dan jadwal jadi lebih efisien.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-center">
                    <iconify-icon icon="mdi:message-alert-outline" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Kolaborasi Secara Real-Time</p>
                        <p class="text-sm text-gray-700">Tetap terhubung dengan supervisor, siswa, dan staf. Bagikan
                            umpan
                            balik, pembaruan, dan laporan secara instan untuk komunikasi yang lancar.</p>
                    </div>
                </div>
                <div class="flex gap-4 items-center">
                    <iconify-icon icon="mdi:chart-line" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Insight Berbasis Data</p>
                        <p class="text-sm text-gray-700">Analisis kinerja PKL dengan alat visualisasi data yang kuat.
                            Ambil
                            keputusan berdasarkan laporan dan pemantauan progres yang mudah diakses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end items-center gap-4">
        <p class="text-neutral-500 font-medium">{{ config('app.version') }}</p>
        <x-button-primary label="Instal" action="next" />
    </div>
</div>
