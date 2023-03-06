<?php


namespace App\Helper;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $heading;
    protected $data;

    public function __construct(array $heading, array $data)
    {
        $this->heading = $heading;
        $this->data = $data;
    }
    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->heading;
    }
}
