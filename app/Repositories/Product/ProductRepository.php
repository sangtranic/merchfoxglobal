<?php
namespace App\Repositories\Post;

use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Products::class;
    }

    public function getAllProduct()
    {
        return \App\Models\Products::all();
    }
}
