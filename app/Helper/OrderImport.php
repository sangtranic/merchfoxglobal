<?php


namespace App\Helper;
use App\Models\Orders;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrderImport implements ToModel
{
    public function model(array $row)
    {
        dump($row);
        return new Orders([
            'id' => $row[0],
            'fulfillCode'     => $row[1],
            'trackingCode'    => $row[2],
            'carrier'    => $row[3],
            'syncStoreStatusId' => !Helper::IsNullOrEmptyString($row[4]) && strpos($row[4], 'yes') ? 1 : 0,
            'note' => $row[5]
        ]);
    }
}
