<?php

namespace App\Services;

use App\Models\School;
use App\Helpers\Exception;
use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class SchoolService
{
    /**
     * Retrieve the school data.
     *
     * @return School|null
     */
    public static function getSchoolData(): ?School
    {
        try {
            return School::first() ?? null;
        } catch (\Throwable $th) {
            Exception::handle(__('system.error.not_found', ['context' => 'Data sekolah']), $th);
            return null;
        }
    }

    /**
     * Store or update the school data.
     *
     * @param array $schoolData
     * @param UploadedFile|null $logo
     * @return bool
     */
    public static function store(array $schoolData, ?UploadedFile $logo): bool
    {
        try {
            // Validate the school data including logo
            if (!self::validateSchoolData($schoolData, $logo)) {
                return false;
            }

            // Save the logo if provided
            if ($logo) {
                $schoolData['logo'] = FileHelper::storeAsWebp($logo, 'logo');
            }

            // Retrieve the existing school data or create a new instance
            $school = self::getSchoolData() ?? new School();

            // Update or create the school data
            $school->fill($schoolData);
            $school->save();

            return true;
        } catch (\Throwable $th) {
            Exception::handle(__('system.store_failed', ['context' => 'Data Sekolah']), $th);
            return false;
        }
    }

    /**
     * Validate the school data.
     *
     * @param array $schoolData
     * @param UploadedFile|null $logo
     * @return bool
     */
    private static function validateSchoolData(array $schoolData, ?UploadedFile $logo): bool
    {
        // Initialize validation rules for school data
        $rules = [
            'name' => 'required|string|min:5|max:255',
            'email' => 'required|email|min:5|max:255',
            'principal_name' => 'required|string|min:5|max:255',
            'address' => 'required|string|min:10|max:255',
            'postcode' => 'required|regex:/^\d{5,10}$/',
            'telp' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
            'fax' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
        ];

        // If a logo is provided, add validation rules for it
        if ($logo) {
            $schoolData['logo'] = $logo;
            $rules['logo'] = 'file|mimes:png,jpg,webp|max:10240';
        }

        // Perform the validation
        $validator = Validator::make($schoolData, $rules);

        if ($validator->fails()) {
            // Handle validation failure and provide feedback
            Exception::handle(__('system.error.invalid', ['context' => 'Data sekolah']), new \Exception($validator->errors()->first()));
            return false;
        }

        return true;
    }
}
