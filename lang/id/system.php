<?php

return [
    // Error Messages
    'error' => [
        'backup_failed' => 'Terjadi kesalahan saat mencoba mencadangkan :context.',
        'create_failed' => 'Terjadi kesalahan saat membuat :context baru.',
        'delete_failed' => 'Terjadi kesalahan saat menghapus :context.',
        'download_failed' => 'Terjadi kesalahan saat mengunduh :context.',
        'duplicate' => ':context sudah ada.',
        'fetch_failed' => 'Terjadi kesalahan saat mengambil :context.',
        'format_failed' => 'Terjadi kesalahan saat memformat :context.',
        'generate_failed' => 'Terjadi kesalahan saat menggenerasi :context.',
        'in_use' => ':context sedang digunakan dan tidak dapat dihapus.',
        'invalid' => ':context tidak valid.',
        'lock_failed' => 'Terjadi kesalahan saat mengunci :context.',
        'message' => 'Terjadi kesalahan pada :context.',
        'missing' => ':context yang diperlukan tidak ditemukan.',
        'not_found' => ':context tidak ditemukan.',
        'receive_failed' => 'Terjadi kesalahan saat menerima :context.',
        'send_failed' => 'Terjadi kesalahan saat mengirim :context.',
        'store_failed' => 'Terjadi kesalahan saat menyimpan :context.',
        'unauthorized' => 'Anda tidak memiliki izin untuk melakukan aksi ini pada :context.',
        'unlock_failed' => 'Terjadi kesalahan saat membuka kunci :context.',
        'update_failed' => 'Terjadi kesalahan saat memperbarui :context.',
        'upload_failed' => 'Terjadi kesalahan saat mengunggah :context.',
    ],

    // Information Messages
    'info' => [
        'approved' => 'Persetujuan :context berhasil.',
        'backed_up' => ':context telah berhasil dicadangkan.',
        'deleted' => ':context berhasil dihapus.',
        'not_modified' => ':context tidak mengalami perubahan.',
        'saved' => ':context berhasil disimpan.',
        'syncing' => 'Proses sinkronisasi :context sedang berlangsung, harap tunggu.',
        'updated' => ':context berhasil diperbarui.',
        'uploaded' => ':context berhasil diunggah.',
        'verified' => 'Verifikasi :context berhasil.',
    ],

    // Success Messages
    'success' => [
        'create' => ':context berhasil dibuat!',
        'delete' => ':context berhasil dihapus!',
        'exported' => ':context berhasil diekspor.',
        'imported' => ':context berhasil diimpor.',
        'locked' => ':context berhasil dikunci.',
        'processed' => ':context berhasil diproses.',
        'store' => ':context berhasil disimpan!',
        'synced' => ':context berhasil disinkronkan.',
        'unlocked' => ':context berhasil dibuka kuncinya.',
        'update' => ':context berhasil diperbarui!',
    ],

    // Warning Messages
    'warning' => [
        'already_exists' => ':context sudah ada, apakah Anda yakin ingin melanjutkan?',
        'approving' => ':context sedang menunggu persetujuan, harap tunggu proses persetujuan.',
        'incomplete' => ':context tidak lengkap, pastikan semua kolom terisi dengan benar.',
        'not_verified' => ':context belum terverifikasi, lanjutkan dengan hati-hati.',
        'pending' => ':context masih dalam status pending, perubahan tidak dapat dilakukan saat ini.',
    ],
];
