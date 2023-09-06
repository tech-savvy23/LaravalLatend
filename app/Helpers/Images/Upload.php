<?php

namespace App\Helpers\Images;

use Intervention\Image\ImageManagerStatic;

class Upload
{
    public static function resize($image, $width)
    {
        $image    = base64_decode($image);
        $image    = ImageManagerStatic::make($image)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
        return $image;
    }
}
