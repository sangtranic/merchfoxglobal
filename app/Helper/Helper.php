<?php
namespace App\Helper;

class Helper
{
    public static function getListStatus()
    {
        $listStatus = collect([
            ['id' => '0',  'name' => 'Chọn trạng thái...'],
            ['id' => '1',  'name' => 'Chờ duyệt'],
            ['id' => '2', 'name' => 'Tạm dừng'],
            ['id' => '3',  'name' => 'Đang hoạt động']
        ]);
        return $listStatus;
    }
}
