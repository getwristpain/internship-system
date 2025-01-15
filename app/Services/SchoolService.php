<?php

namespace App\Services;

use App\Models\School;
use App\Helpers\Exception;
use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;

class SchoolService
{
    /**
     * Mengambil data sekolah.
     *
     * @return School|null
     */
    public static function getSchoolData(): ?School
    {

        try {
            // 1. Ambil data sekolah.
            return School::first();
        } catch (\Throwable $th) {
            // 2. Jika gagal, tangani pesan kesalahan.
            Exception::handle(__('system.error.not_found', ['context' => 'Data sekolah']), $th);

            // 3. Kembalikan nilai null
            return null;
        }
    }

    public static function store(array $schoolData, ?UploadedFile $logo): bool
    {
        try {
            // 1. Penyiapan data sekolah dari atribut
            $school = self::getSchoolData();

            // 2. Jika terdapat file logo, simpan logo ke storage
            if ($logo) {
                $schoolData['logo'] = FileHelper::storeAsWebp($logo, 'logo');
            }

            // 3. Perbarui data sekolah, kembalikan nilai true
            $school->update($schoolData);
            return true;
        } catch (\Throwable $th) {
            // 5. Jika gagal, tangani pesan kesalahan.
            Exception::handle(__('system.store_failed', ['context' => 'Data Sekolah']), $th);

            // 6. Kembalikan nilai false
            return false;
        }
    }
}
