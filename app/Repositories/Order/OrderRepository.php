<?php
namespace App\Repositories\Order;

use App\Models\Orders;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Orders::class;
    }

    public function get($id)
    {
        return \App\Models\Orders::find($id);
    }

    public function search($dateFrom, $dateTo,$userId)
    {
        $modelName = get_class($this->model);
        $cacheKey = $modelName.$dateFrom.$dateTo.$userId."Cache";
        $data = Cache::get($cacheKey);
        if (!$data) {
            $query = DB::table('orders')
                ->leftjoin('vps', 'orders.vpsId', '=', 'vps.id')
                ->leftjoin('users', 'vps.userId', '=', 'users.id')
                ->select('orders.id', 'orders.orderNumber', 'orders.vpsId','orders.created_at',  DB::raw('users.id as userId'), 'users.userName');//Orders::query();
            if ($dateFrom && $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            } else if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            } else if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
            if($userId>0)
            {
                $query->where('users.id', '=', $userId);
            }
            $query->where('users.statusId', '=', 3);
            $data = $query->get();

            Cache::remember($cacheKey, 5, function () use ($data) {
                return $data;
            });
        }
        return $data;
    }
}
