<?php

$attributes = [
    'access_key' => 'kunci akses',
    'address' => 'alamat',
    'avatar' => 'foto profil',
    'cache_key' => 'kunci cache',
    'department' => [
        'code' => 'kode jurusan',
        'name' => 'nama jurusan',
    ],
    'email' => 'email',
    'gender' => 'jenis kelamin',
    'group' => 'kelas',
    'identifier_number' => 'nomor identitas',
    'internship_report_file' => 'berkas laporan PKL',
    'name' => 'nama',
    'parent_address' => 'alamat orang tua',
    'parent_name' => 'nama orang tua',
    'parent_phone' => 'telepon orang tua',
    'password_confirmation' => 'konfirmasi kata sandi',
    'password' => 'kata sandi',
    'phone' => 'telepon',
    'position' => 'jabatan',
    'program' => [
        'date_finish' => 'tanggal selesai',
        'date_start' => 'tanggal mulai',
        'title' => 'judul program',
        'year' => 'tahun periode',
    ],
    'school' => [
        'name' => 'nama sekolah',
        'logo' => 'logo sekolah',
        'email' => 'email sekolah',
        'address' => 'alamat sekolah',
        'postcode' => 'kode pos sekolah',
        'telp' => 'telepon sekolah',
        'fax' => 'fax sekolah',
        'principal_name' => 'kepala sekolah',
    ],
    'school_year' => 'tahun ajaran',
];

return array_merge($attributes, [
    'form' => $attributes,
    'user' => $attributes,
]);
