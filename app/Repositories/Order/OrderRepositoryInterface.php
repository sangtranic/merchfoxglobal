<?php
namespace App\Repositories\Order;

use App\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function get($id);
    public function search($dateFrom, $dateTo,$userId,$productCateId);
}
