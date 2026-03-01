<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    /**
     * Upload an image to a specific directory.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory Subdirectory under public/images/
     * @return string URL of the uploaded image
     */
    public function uploadImage($file, $directory)
    {
        $destinationPath = public_path('images/' . $directory);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $filename);

        // Return the path relative to the public directory for storage in DB
        return 'images/' . $directory . '/' . $filename;
    }

    /**
     * Delete an image from the filesystem.
     *
     * @param string $path URL or relative path stored in DB
     */
    public function deleteImage($path)
    {
        if (!$path) return;

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
