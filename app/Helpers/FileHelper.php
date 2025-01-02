<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
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
    public static function storeAsWebp(UploadedFile $file, string $path = ''): string
    {
        $path = rtrim($path, '/') . '/';

        // Generate a new filename with .webp extension
        $fileName = self::generateFileName('webp');
        $filePath = 'uploads/images/' . $path . $fileName;

        // Convert to webp format and compress the image
        $image = Image::make($file)->encode('webp', 60);

        // Store the compressed image to the public disk
        Storage::disk('public')->put($filePath, $image);

        return $filePath;
    }

    public static function storeDoc($file): string
    {
        $fileName = self::generateFileName($file->getClientOriginalExtension());
        $filePath = 'uploads/documents/' . $fileName;

        Storage::disk('public')->putFileAs('uploads/documents', $file, $fileName);

        return $filePath;
    }

    public static function deleteFile($filePath): string
    {
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            return 'File deleted successfully.';
        } else {
            return 'File not found.';
        }
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

    public static function formatFileSize(int $size = 0)
    {
        $fileSize = number_format($size / (1024 * 1024), 2);
        $formattedSize = $fileSize . 'MB';

        return $formattedSize ?? '0 MB';
    }
}
