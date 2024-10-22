<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Store the attachment as a compressed WEBP image
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public static function storeAsWebp($file): string
    {
        // Generate a new filename with .webp extension
        $fileName = self::generateFileName('webp');
        $filePath = 'uploads/attachments/' . $fileName;

        // Convert to webp format and compress the image
        $image = Image::make($file)->encode('webp', 60);

        // Store the compressed image to the public disk
        Storage::disk('public')->put($filePath, $image);

        return $filePath;
    }

    /**
     * Generate a unique filename with the given extension
     *
     * @param string $extension
     * @return string
     */
    protected static function generateFileName(string $extension): string
    {
        $today = Carbon::now();
        $randomString = Str::random(16);

        return $today->format('Y-m-d') . '-' . $randomString . '.' . $extension;
    }
}
