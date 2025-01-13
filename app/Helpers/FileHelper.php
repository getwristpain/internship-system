<?php

namespace App\Helpers;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileHelper
{

    public static function findFile(string $path): ?UploadedFile
    {
        try {
            // format path
            $formattedPath = storage_path('app/public/' . $path);

            // Check if the file exists
            if (file_exists($formattedPath) && is_file($formattedPath)) {
                // Convert path to UploadedFile
                return new UploadedFile(
                    $formattedPath,
                    basename($formattedPath),
                    mime_content_type($formattedPath),
                    null,
                    true
                );
            }

            return null;
        } catch (\Throwable $th) {
            Exception::handle(__('system.error.format_failed', ['context' => 'berkas']), $th);
            return null;
        }
    }

    /**
     * Format file size to MB
     *
     * @param int $size
     * @return string
     */
    public static function formatFileSize(int $size = 0): string
    {
        try {
            // 1. Format file size in MB.
            $fileSize = number_format($size / (1024 * 1024), 2);

            // 2. If successful, return the formatted file size.
            return $fileSize . 'MB';
        } catch (Throwable $th) {
            // 3. If failed, log the error message
            Exception::handle(__('system.format_failed', ['context' => 'file size']), $th);

            // 4. Return message if there's an error
            return '0 MB';
        }
    }

    /**
     * Generate a unique file name with the given extension
     *
     * @param string $extension
     * @return string
     */
    protected static function generateFileName(string $extension): ?string
    {
        try {
            // 1. Initialize values.
            $today = Carbon::now();
            $randomString = Str::random(16);

            // 2. Return a formatted file name with the random string.
            return $today->format('Y-m-d') . '-' . $randomString . '.' . $extension;
        } catch (Throwable $th) {
            // 3. Jika gagal, tangani pesan kesalahan.
            Exception::handle(__('system.generate_failed', ['context' => 'file name']), $th);
            return null;
        }
    }

    /**
     * Store attachment as compressed WEBP image
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string
     */
    public static function storeAsWebp(UploadedFile $file, string $path = ''): ?string
    {
        try {
            $path = rtrim($path, '/') . '/';

            // Generate new file name with .webp extension
            $fileName = self::generateFileName('webp');
            $fileLocation = 'uploads/images/' . $path . $fileName;

            // Convert file to webp format and compress the image
            $image = Image::make($file)->encode('webp', 60);

            // Store the compressed image in the public disk
            Storage::disk('public')->put($fileLocation, $image);

            return $fileLocation;
        } catch (Throwable $th) {
            Exception::handle(__('system.error.upload_failed', ['context' => 'file as WEBP']), $th);
            return null;
        }
    }

    /**
     * Store document
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public static function storeDoc($file): string
    {
        try {
            $fileName = self::generateFileName($file->getClientOriginalExtension());
            $fileLocation = 'uploads/documents/' . $fileName;

            Storage::disk('public')->putFileAs('uploads/documents', $file, $fileName);

            return $fileLocation;
        } catch (Throwable $th) {
            Exception::handle(__('system.error.store_failed'), $th);
        }
    }

    /**
     * Delete file based on the given path
     *
     * @param string $filePath
     * @return void
     */
    public static function deleteFile($filePath): void
    {
        try {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        } catch (Throwable $th) {
            Exception::handle('Error deleting file', $th);
        }
    }
}
