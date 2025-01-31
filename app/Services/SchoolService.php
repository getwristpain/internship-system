<?php

namespace App\Services;

use App\Models\School;
use App\Helpers\Logger;
use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

use function Livewire\of;

class SchoolService
{
    /**
     * Retrieve the first school or create a new one.
     *
     * @return School
     */
    public static function firstSchool(): School
    {
        return School::firstOrNew();
    }

    /**
     * Store or update the school data.
     *
     * @param array $schoolData
     * @param UploadedFile|null $logo
     * @return School|null
     */
    public static function storeSchool(array $schoolData, ?UploadedFile $logo): ?School
    {
        if (!self::isValidSchoolData($schoolData, $logo)) {
            return null;
        }

        $schoolData['logo'] = self::processLogo($logo, $schoolData['logo'] ?? null);

        return self::saveSchoolData($schoolData);
    }

    /**
     * Validate the school data.
     *
     * @param array $schoolData
     * @param UploadedFile|null $logo
     * @return bool
     */
    private static function isValidSchoolData(array $schoolData, ?UploadedFile $logo): bool
    {
        $validator = Validator::make(
            array_merge($schoolData, ['logo_file' => $logo]),
            self::validationRules()
        );

        if ($validator->fails()) {
            Logger::handle('error', 'Validation failed for school data.', new \Exception(json_encode($validator->errors()->getMessages())));
            return false;
        }

        return true;
    }

    /**
     * Get validation rules for school data.
     *
     * @return array
     */
    private static function validationRules(): array
    {
        return [
            'name' => 'required|string|min:5|max:255',
            'logo_file' => 'nullable|file|mimes:png,jpg,webp|max:10240',
            'email' => 'required|email|min:5|max:255',
            'principal_name' => 'required|string|min:5|max:255',
            'address' => 'required|string|min:10|max:255',
            'postcode' => 'required|regex:/^\d{5,10}$/',
            'telp' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
            'fax' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
        ];
    }

    /**
     * Process the logo file.
     *
     * @param UploadedFile|null $logo
     * @param string|null $currentLogo
     * @return string|null
     */
    private static function processLogo(?UploadedFile $logo, ?string $currentLogo): ?string
    {
        if ($logo) {
            return FileHelper::storeAsWebp($logo, 'logo');
        }

        return $currentLogo;
    }

    /**
     * Save school data.
     *
     * @param array $schoolData
     * @return School
     */
    private static function saveSchoolData(array $schoolData): School
    {
        $school = self::firstSchool();
        $school->fill($schoolData)->save();

        return $school;
    }
}
