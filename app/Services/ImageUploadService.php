<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    /**
     * Upload an image to Cloudinary and return the secure URL.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public function upload(UploadedFile $file, string $folder = 'school_system')
    {
        try {
            // Uploads the file to the specified folder in Cloudinary
            // returns the secure URL (https)
            $result = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder
            ]);

            return $result->getSecurePath();
        } catch (\Exception $e) {
            \Log::error('Cloudinary Upload Error: ' . $e->getMessage());
            throw new \Exception('Failed to upload image. Please try again later.');
        }
    }
}
