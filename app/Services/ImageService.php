<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function deleteImage($image)
    {
        $imagePath = "public/img" . $image;
        
        if (Storage::disk('public_uploads')->exists($imagePath)) {
            Storage::disk('public_uploads')->delete($imagePath);
        }
    }
}
