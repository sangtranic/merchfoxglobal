<?php
namespace App\Repositories\Product;

use App\Models\Products;
use App\Repositories\BaseRepository;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Products::class;
    }

    public function getAllProduct()
    {
        return Products::all();
    }
}
