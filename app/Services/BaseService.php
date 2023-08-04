<?php

namespace App\Services;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BaseService
{
    /**
     * Đổi tên ảnh vào lưu tại đường dẫn
     */
    static public function renameImage($image, $folder)
    {
        $extension = $image->getClientOriginalExtension();
        $str_random = Str::random(9);
        $img_path = $str_random . time() . '.' . $extension;
        $image->move(public_path("media/$folder"), $img_path);
        return $img_path;
    }

    /**
     * Thay đổi kích thước ảnh
     */
    static public function resizeImage($folder, $image_name, $width = 800, $height = 600)
    {
        $filePath = public_path("media/$folder") . '/' . $image_name;
        $imgFile = Image::make($filePath);
        // resize width or height according to the other
        // if w=null, h=? -> resize w aspect ratio with h=?
        $imgFile->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($filePath);
    }
}
