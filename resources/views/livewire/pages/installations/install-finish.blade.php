<?php

use Livewire\Volt\Component;
use App\Services\SystemService;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest')] class extends Component {
    public function finish()
    {
        if (SystemService::markAsInstalled()) {
            return $this->redirect(route('login'), navigate: true);
        }
    }

    public function redirectToHelp()
    {
        return $this->redirect(route('help'), navigate: true);
    }
}; ?>

<div>
    <div>
        <h1>Yey, Instalasi Berhasil! 🎉</h1>
        <p>Selamat! Sistem Informasi Manajemen PKL berhasil diinstal dan siap digunakan.</p>
    </div>
    <div>
        Panduan Singkat Penggunaan Sistem
        Login ke Dashboard
        Masuk menggunakan akun admin untuk mengakses semua fitur manajemen.
        Konfigurasi Data Awal
        Lengkapi data sekolah, jurusan, dan periode PKL di menu Pengaturan.
        Tambah Peserta PKL
        Input data siswa dan atur pembimbing di menu Manajemen Siswa.
        Kelola Laporan & Evaluasi
        Pantau laporan kegiatan siswa dan beri evaluasi langsung melalui dashboard.
        Untuk detail lebih lengkap, kunjungi Pusat Bantuan.
    </div>
    <div>
        Kredit:
        Dibuat oleh: Reas Vyn (@getwristpain)
        Dokumentasi: github.com/getwristpain/internship-system
        Didukung oleh: Universitas Negeri Yogyakarta
    </div>
    <div>
        <x-button action="redirectToHelp">Pusat Bantuan</x-button>
        <x-button-primary action="finish" icon="icon-park-outline:right-c">Masuk</x-button-primary>
    </div>
</div>
