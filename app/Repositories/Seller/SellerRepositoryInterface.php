<?php
namespace App\Repositories\Seller;

use App\Repositories\RepositoryInterface;

interface SellerRepositoryInterface extends RepositoryInterface
{
    public function get($id);
}
