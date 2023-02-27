<?php
namespace App\Repositories\Seller;

use App\Repositories\BaseRepository;

class SellerRepository extends BaseRepository implements SellerRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Seller::class;
    }

    public function get($id)
    {
        return \App\Models\Seller::find($id);
    }
}
