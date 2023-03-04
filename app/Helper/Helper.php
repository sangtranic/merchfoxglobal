<?php

namespace App\Helper;

class Helper
{
    public static $IMG_DEFAULT = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    public static $RoleKey= "MerchfoxUserRole";
    public static function getListStatus()
    {
        $listStatus = collect([
            ['id' => '0', 'name' => 'Chọn trạng thái...'],
            ['id' => '1', 'name' => 'Chờ duyệt'],
            ['id' => '2', 'name' => 'Tạm dừng'],
            ['id' => '3', 'name' => 'Đang hoạt động']
        ]);
        return $listStatus;
    }
    public static function getRoleStr()
    {
        return session()->get(Helper::$RoleKey,"user");
    }
    public static function setRoleStr($value)
    {
        return session()->session()->put(Helper::$RoleKey, $value);
    }

    public static function IsNullOrEmptyString($str)
    {
        return ($str === null || trim($str) === '');
    }
    public static function getImageUrlPath($imageName, $folder='original', $withDomain = false)
    {
        if (strlen($imageName) == 0){
            return $imageName;
        }
        if (str_starts_with($imageName, config('app.url'))){
            $url = $imageName;
            if (str_contains($imageName, $folder)){
                return $imageName;
            }else if (str_contains($imageName, 'thumbnail')){
                return  str_replace('thumbnail', $folder, $imageName);
            }else if (str_contains($imageName, 'medium')){
                return  str_replace('medium', $folder, $imageName);
            }
        }else{

            $basePath = public_path('upload/' . $folder);
            if ($withDomain){
                $baseUrl = config('app.url') . '/upload/' . $folder;
                $url = $baseUrl . '/' . $imageName;
            }else{

                $url = $basePath . '/' . $imageName;
            }
        }
        return $url;
    }

}
