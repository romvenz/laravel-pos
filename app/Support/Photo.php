<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Photo
{
    public static function save(
        UploadedFile $file,
        string $directory,
        string $field = 'image'
    ): string {
        $imageInfo = @getimagesize($file->getRealPath());

        if (
            ! $imageInfo ||
            ! in_array(
                $imageInfo['mime'],
                ['image/jpeg', 'image/png', 'image/webp'],
                true
            ) ||
            $imageInfo[0] * $imageInfo[1] > 20_000_000
        ) {
            throw ValidationException::withMessages([
                $field => 'Use a JPEG, PNG, or WebP image of reasonable dimensions.',
            ]);
        }

        if (! extension_loaded('gd')) {
            throw new \RuntimeException(
                'The PHP GD extension is required for image processing.'
            );
        }

        $source = @imagecreatefromstring(
            file_get_contents($file->getRealPath())
        );

        if (! $source) {
            throw ValidationException::withMessages([
                $field => 'The image could not be opened.',
            ]);
        }

        try {
            $width = imagesx($source);
            $height = imagesy($source);
            $scale = min(1, 800 / max($width, $height));

            $newWidth = max(1, (int) round($width * $scale));
            $newHeight = max(1, (int) round($height * $scale));

            $output = imagecreatetruecolor($newWidth, $newHeight);

            imagefill(
                $output,
                0,
                0,
                imagecolorallocate($output, 255, 255, 255)
            );

            imagecopyresampled(
                $output,
                $source,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );

            ob_start();
            imagejpeg($output, null, 85);
            $bytes = ob_get_clean();

            imagedestroy($output);
        } finally {
            imagedestroy($source);
        }

        $path = $directory . '/'
            . (string) \Illuminate\Support\Str::uuid()
            . '.jpg';

        if (! Storage::disk('public')->put($path, $bytes)) {
            throw new \RuntimeException('Unable to save the image.');
        }

        return $path;
    }

    public static function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}