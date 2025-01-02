<?php

namespace App\Services;

use App\Models\School;
use App\Helpers\Exception;
use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
            $school = School::first();

            if (!$school) {
                Exception::handle('Data sekolah tidak ditemukan.');
                Session::flash('warning', 'Data sekolah tidak ditemukan.');
                return null;
            }

            Session::flash('success', 'Data sekolah berhasil diambil.');
            return $school;
        } catch (\Throwable $th) {
            Exception::handle('Kesalahan saat mengambil data sekolah.', $th);
            Session::flash('error', 'Terjadi kesalahan saat mengambil data sekolah.');
            return null;
        }
    }

    /**
     * Menyimpan data sekolah beserta logo jika ada.
     *
     * @param array $schoolData
     * @param UploadedFile|null $logo
     * @return bool
     */
    public static function save(array $schoolData = [], ?UploadedFile $logo = null): bool
    {
        try {
            $school = self::getSchoolData();

            if ($school) {
                $school->update($schoolData);

                if ($logo) {
                    $logoPath = self::storeMedia($logo);
                    $school->update(['logo' => $logoPath]);
                }

                Session::flash('success', 'Data sekolah berhasil disimpan.');
                return true;
            }

            Session::flash('warning', 'Data sekolah tidak ditemukan untuk diperbarui.');
            return false;
        } catch (\Throwable $th) {
            Exception::handle('Gagal menyimpan data sekolah.', $th);
            Session::flash('error', 'Terjadi kesalahan saat menyimpan data sekolah. Coba lagi!');
            return false;
        }
    }

    /**
     * Menyimpan file logo dalam format WebP.
     *
     * @param UploadedFile $logo
     * @return string
     */
    private static function storeMedia(UploadedFile $logo): string
    {
        try {
            $path = FileHelper::storeAsWebp($logo, 'logo');

            if (!$path) {
                throw new \Exception('Gagal mengunggah logo.');
            }

            Session::flash('success', 'Logo berhasil diunggah.');
            return $path;
        } catch (\Throwable $th) {
            Exception::handle('Kesalahan saat mengunggah logo.', $th);
            Session::flash('error', 'Terjadi kesalahan saat mengunggah logo.');
            return '';
        }
    }
}
