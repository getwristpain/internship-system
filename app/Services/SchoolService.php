<?php

namespace App\Services;

use App\Models\School;
use App\Helpers\Exception;
use Illuminate\Support\Facades\Session;

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
            // 1. Ambil data pertama dari sekolah
            $school = School::first();

            // 2. Jika berhasil, kembalikan data sekolah yang diambil
            if ($school) {
                return $school;
            }
        } catch (\Throwable $th) {
            // 3. Jika gagal, tampilkan pesan kesalahan
            Session::flash('error', __('system.data_not_found', ['context' => __('school')]));

            // 4. Simpan pesan kesalahan ke Exception
            Exception::handle(__('system.data_not_found', ['context' => __('school')]), $th);

            // 5. Kembalikan nilai null
            return null;
        }
    }
}
