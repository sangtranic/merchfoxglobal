<?php


namespace App\Helper;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as Image;

class FileUploadHelper
{
    public static function saveImage($image)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if ($image && in_array($image->getClientOriginalExtension(), $allowedExtensions)) {

            $date = date('Y/m/d');
//            $path = public_path('upload/' . $date);
//            if (!file_exists($path)) {
//                mkdir($path, 0777, true);
//            }
//            $file = $request->file('file');
//            $file->move($path, $filename);


            // Đặt tên file ảnh
            //$filename = time() . '.' . $image->getClientOriginalExtension();
            $filename = $image->getClientOriginalName();
            // Lưu ảnh gốc vào thư mục 'images'
            //$path = public_path('upload/original/' . $filename);

            $path = public_path('upload/original/'.$date);
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            //kiem tra anh da ton tai hay chua. neu ton tai roi thi them thoi gian vao ten anh
            $checkPath = $path."/". $filename;
            if (File::exists($checkPath)) {
                $filename = time().$filename;
            }
            $path= $path."/". $filename;
            Image::make($image->getRealPath())->save($path);

            list($width, $height) = getimagesize($path);


            $newheight = (float)($width / 300) * $height;
            // Tạo phiên bản ảnh với kích thước 300x300
            $thumbPath = public_path('upload/thumbnail/'.$date);
            if (!File::exists($thumbPath)) {
                File::makeDirectory($thumbPath, $mode = 0777, true, true);
            }
            $thumbPath= $thumbPath."/". $filename;

            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbPath);
            //Image::make($image->getRealPath())->fit(300, $newheight)->save($thumbPath);

            $newheight = (float)($width / 600) * $height;
            // Tạo phiên bản ảnh với kích thước 600x600
            $mediumPath = public_path('upload/medium/'.$date);
            if (!File::exists($mediumPath)) {
                File::makeDirectory($mediumPath, $mode = 0777, true, true);
            }
            $mediumPath= $mediumPath."/". $filename;
            Image::make($image->getRealPath())->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($mediumPath);
            //Image::make($image->getRealPath())->fit(600, $newheight)->save($mediumPath);
            return $date."/".$filename;
        }
        return null;
    }
}
