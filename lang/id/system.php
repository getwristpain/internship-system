<?php

return [
    'context' => array_merge(
        require __DIR__ . '/attribute.php',
    ),

    // Error Messages
    'error' => [
        'backup_failed' => ':context gagal dicadangkan.',
        'create_failed' => ':context gagal dibuat.',
        'delete_failed' => ':context gagal dihapus.',
        'download_failed' => ':context gagal diunduh.',
        'duplicate' => ':context sudah ada.',
        'fetch_failed' => ':context gagal diambil.',
        'format_failed' => ':context gagal diformat.',
        'generate_failed' => 'Gagal saat menghasilkan :context.',
        'proccess_failed' => 'Gagal saat menjalankan :context.',
        'in_use' => ':context sedang digunakan dan tidak dapat dihapus.',
        'invalid' => ':context tidak valid.',
        'lock_failed' => ':context gagal dikunci.',
        'message' => ':context mengalami error.',
        'method_not_found' => 'Metode :method tidak ditemukan.',
        'missing' => ':context yang diperlukan tidak ditemukan.',
        'not_found' => ':context tidak ditemukan.',
        'receive_failed' => ':context gagal diterima.',
        'send_failed' => ':context gagal dikirim.',
        'store_failed' => ':context gagal disimpan.',
        'unauthorized' => 'Anda tidak memiliki izin untuk :context.',
        'unlock_failed' => ':context gagal dibuka kuncinya.',
        'update_failed' => ':context gagal diperbarui.',
        'upload_failed' => ':context gagal diunggah.',
    ],

    // Information Messages
    'info' => [
        'approved' => ':context telah berhasil disetujui.',
        'backed_up' => ':context berhasil dicadangkan.',
        'not_modified' => ':context tidak mengalami perubahan.',
        'syncing' => ':context sedang disinkronisasi, harap tunggu.',
    ],

    // Success Messages
    'success' => [
        'created' => ':context berhasil dibuat.',
        'deleted' => ':context berhasil dihapus.',
        'exported' => ':context berhasil diekspor.',
        'imported' => ':context berhasil diimpor.',
        'locked' => ':context berhasil dikunci.',
        'processed' => ':context berhasil diproses.',
        'saved' => ':context berhasil disimpan.',
        'synced' => ':context berhasil disinkronkan.',
        'unlocked' => ':context berhasil dibuka kuncinya.',
        'updated' => ':context berhasil diperbarui.',
        'uploaded' => ':context berhasil diunggah.',
        'verified' => ':context berhasil diverifikasi.',
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
