<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


trait FileUpload
{
    public static $mimes = [
        'png',
        'jpeg',
        'jpg',
        'gif',
        'bmp',
        'webp',
        'PNG',
        'JPEG',
        'JPG',
        'GIF',
        'BMP',
        'WEBP'
    ];

    public static function file($file, $path,)
    {
        // Get the uploaded file instance
        $uploadedImage = $file;
        // Generate a unique image name based on the original name
        $imageName = time() . '_' . $uploadedImage->getClientOriginalName();
        // Read the contents of the image file
        $imageData = file_get_contents($uploadedImage->getRealPath());
        // Determine the file extension dynamically
        $fileExtension = $uploadedImage->getClientOriginalExtension();
        // Store binary data in storage
        $ImagePath = $path . uniqid() . '.' . $fileExtension;
        Storage::put($ImagePath, $imageData);
        // Build the URL for the stored image
        $imageUrl = asset(str_replace('public', 'storage',  $ImagePath));

        return   $imageUrl;
    }
    public static function deleteAndUpload($upload, $path, $delete)
    {
        $filename = self::file($upload, $path);
        self::delete($path, $delete);

        return $filename;
    }
    public static function delete($path, $file)
    {
        if (File::exists($path . '/' . $file)) {
            File::delete($path . '/' . $file);
        };

        return true;
    }
}
