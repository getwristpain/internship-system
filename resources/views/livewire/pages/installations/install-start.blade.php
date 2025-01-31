<?php

use App\Helpers\Exception;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Redirects to the next installation step.
     *
     */
    public function next()
    {
        return $this->redirect(route('install.step1'), navigate: true);
    }
};

?>

<div class="flex flex-col gap-12 items-center justify-between s-full">
    <div class="flex flex-col lg:flex-row gap-20 items-center flex-1">
        <div class="flex w-full align-vh-center">
            <img src="{{ asset('img/welcome_4207.png') }}" alt="Welcome" class="w-3/4 h-auto">
        </div>
        <div class="flex flex-col w-full gap-4 text-neutral-900">
            <div>
                <p class="text-sm font-semibold text-gray-600">Selamat datang di</p>
                <h1 class="text-4xl font-extrabold text-yellow-500">Sistem Informasi Manajemen Praktik Kerja Lapangan
                    (PKL)!</h1>
                <p class="text-lg font-semibold text-gray-800">Platform yang memudahkan pengelolaan dan optimasi
                    pengalaman PKL Anda.</p>
            </div>
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <iconify-icon icon="mdi:clipboard-check-outline" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Pengelolaan PKL yang Mudah</p>
                        <p class="text-sm text-gray-700">Pantau, kelola, dan atur PKL secara real-time hanya dengan
                            beberapa
                            klik. Organisasi data siswa, perusahaan, dan jadwal jadi lebih efisien.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <iconify-icon icon="mdi:message-alert-outline" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Kolaborasi Secara Real-Time</p>
                        <p class="text-sm text-gray-700">Tetap terhubung dengan supervisor, siswa, dan staf. Bagikan
                            umpan
                            balik, pembaruan, dan laporan secara instan untuk komunikasi yang lancar.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <iconify-icon icon="mdi:chart-line" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Insight Berbasis Data</p>
                        <p class="text-sm text-gray-700">Analisis kinerja PKL dengan alat visualisasi data yang
                            kuat.
                            Ambil
                            keputusan berdasarkan laporan dan pemantauan progres yang mudah diakses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex w-full items-center justify-end gap-4">
        <p class="font-medium text-neutral-500">{{ config('app.version') }}</p>
        <x-button-primary action="next">Install</x-button-primary>
    </div>
</div>


{{-- <div class="flex flex-col gap-12">
    <div class="flex-1 flex flex-col gap-20 lg:flex-row">
        <div class="flex w-full align-vh-center">
            <img src="{{ asset('img/welcome_4207.png') }}" alt="Welcome" class="w-3/4 h-auto">
        </div>
        <div class="flex flex-col w-full gap-4 text-neutral-900">
            <div>
                <p class="text-sm font-semibold text-gray-600">Selamat datang di</p>
                <h1 class="text-4xl font-extrabold text-yellow-500">Sistem Informasi Manajemen Praktik Kerja Lapangan
                    (PKL)!</h1>
                <p class="text-lg font-semibold text-gray-800">Platform yang memudahkan pengelolaan dan optimasi
                    pengalaman PKL Anda.</p>
            </div>
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <iconify-icon icon="mdi:clipboard-check-outline" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Pengelolaan PKL yang Mudah</p>
                        <p class="text-sm text-gray-700">Pantau, kelola, dan atur PKL secara real-time hanya dengan
                            beberapa
                            klik. Organisasi data siswa, perusahaan, dan jadwal jadi lebih efisien.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <iconify-icon icon="mdi:message-alert-outline" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Kolaborasi Secara Real-Time</p>
                        <p class="text-sm text-gray-700">Tetap terhubung dengan supervisor, siswa, dan staf. Bagikan
                            umpan
                            balik, pembaruan, dan laporan secara instan untuk komunikasi yang lancar.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <iconify-icon icon="mdi:chart-line" class="text-2xl text-yellow-500"></iconify-icon>
                    <div>
                        <p class="text-xl font-semibold">Insight Berbasis Data</p>
                        <p class="text-sm text-gray-700">Analisis kinerja PKL dengan alat visualisasi data yang
                            kuat.
                            Ambil
                            keputusan berdasarkan laporan dan pemantauan progres yang mudah diakses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex w-full items-center justify-end gap-4">
        <p class="font-medium text-neutral-500">{{ config('app.version') }}</p>
        <x-button-primary action="next">Install</x-button-primary>
    </div>
</div> --}}
