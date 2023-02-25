<?php

namespace App\Helper;

class Helper
{
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
}
