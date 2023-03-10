<?php
namespace App\Repositories\Seller;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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

    public function getByUserId($userId)
    {
        $query = DB::table('seller')
            ->select('id', 'sellerName');
        if($userId>0)
        {
            $query->where('userId', '=', $userId);
        }
        $data = $query->get();
        return $data;
    }
}
