<?php


namespace App\Helper;


use Intervention\Image\Facades\Image as Image;

class FileUploadHelper
{
    public static function saveImage($image)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if ($image && in_array($image->getClientOriginalExtension(), $allowedExtensions)) {
            // Đặt tên file ảnh
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Lưu ảnh gốc vào thư mục 'images'
            $path = public_path('upload/original/' . $filename);
            Image::make($image->getRealPath())->save($path);

            list($width, $height) = getimagesize($path);


            $newheight = (float)($width / 300) * $height;
            // Tạo phiên bản ảnh với kích thước 300x300
            $thumbPath = public_path('upload/thumbnail/' . $filename);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbPath);
            //Image::make($image->getRealPath())->fit(300, $newheight)->save($thumbPath);

            $newheight = (float)($width / 600) * $height;
            // Tạo phiên bản ảnh với kích thước 600x600
            $mediumPath = public_path('upload/medium/' . $filename);
            Image::make($image->getRealPath())->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($mediumPath);
            //Image::make($image->getRealPath())->fit(600, $newheight)->save($mediumPath);
            return $filename;
        }
        return null;
    }
}
