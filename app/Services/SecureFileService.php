<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureFileService
{
    /**
     * Upload and encrypt a file to the specified disk.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @param string $type ('photo' or 'file')
     * @return string The path to the stored encrypted file
     * @throws InvalidArgumentException
     */
    public static function uploadSecurely(UploadedFile $file, string $directory, string $disk = 'local', string $type = 'file'): string
    {
        self::validateFileSize($file, $type);

        // Generate a secure, randomized filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = trim($directory, '/') . '/' . $filename;

        // Read file contents and encrypt
        $fileContent = $file->get();
        $encryptedContent = Crypt::encryptString($fileContent);

        // Store the encrypted payload to the designated disk
        Storage::disk($disk)->put($path, $encryptedContent);

        return $path;
    }

    /**
     * Retrieve, decrypt, and serve the file.
     *
     * @param string $path
     * @param string $disk
     * @param string $mimeType
     * @return StreamedResponse
     */
    public static function streamDecryptedFile(string $path, string $disk, string $mimeType = 'application/octet-stream'): StreamedResponse
    {
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        $encryptedContent = Storage::disk($disk)->get($path);

        try {
            $decryptedContent = Crypt::decryptString($encryptedContent);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(500, 'File decryption failed.');
        }

        return response()->stream(function () use ($decryptedContent) {
            echo $decryptedContent;
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => strlen($decryptedContent),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Validate the file size against the global configuration.
     */
    protected static function validateFileSize(UploadedFile $file, string $type): void
    {
        $sizeInMb = $file->getSize() / 1024 / 1024;

        $maxSize = $type === 'photo'
            ? config('app.photo_max_size')
            : config('app.file_max_size');

        if ($sizeInMb > $maxSize) {
            throw new InvalidArgumentException(ucfirst($type) . " exceeds the maximum allowed size of {$maxSize}MB.");
        }
    }
}
