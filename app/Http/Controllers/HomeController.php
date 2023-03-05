<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Roles;
use App\Models\Users;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Vps\VpsRepositoryInterface;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $UserRepo;
    protected $RoleRepo;
    protected $OrderRepo;
    protected $VpsRepo;
    public function __construct(UserRepositoryInterface $userRepo, RoleRepositoryInterface $roleRepo,
                                OrderRepositoryInterface $orderRepo, VpsRepositoryInterface $vpsRepo)
    {
        $this->middleware('auth');
        $this->UserRepo = $userRepo;
        $this->RoleRepo = $roleRepo;
        $this->OrderRepo = $orderRepo;
        $this->VpsRepo = $vpsRepo;
    }

    public function index()
    {
        $dateTo = Carbon::now();
        $dateFrom = Carbon::now()->addDays(-30);

        $listUser= $this->UserRepo->getAll();
        $listUser = $listUser->where('statusId', '=', 3);
        $listVps = $this->VpsRepo->getAll();
        if(Auth::user()->role != "admin")
        {
            $listUserAdd = $listUser->where('id', '=', Auth::user()->id);
            $listOrder= $this->OrderRepo->search($dateFrom->format('Y-m-d H:i:s.u'),$dateTo->format('Y-m-d H:i:s.u'),Auth::user()->id);
            $listVps = $listVps->where('userId', '=', Auth::user()->id);
        }else{
            $listUserAdd = $listUser;
            $listOrder= $this->OrderRepo->search($dateFrom->format('Y-m-d H:i:s.u'),$dateTo->format('Y-m-d H:i:s.u'),0);
        }

        $dateOrders = collect([]);
        if(isset($listOrder) && count($listOrder)>0)
        {
            $dateOrders = $listOrder->map(function ($order) {
                return [
                    'id' => $order->id,
                    'orderNumber' => $order->orderNumber,
                    'vpsId' => $order->vpsId,
                    'userId' => $order->userId,
                    'userName' => $order->userName,
                    'created_at' => date('Y-m-d', strtotime($order->created_at))
                ];
            });
        }

        return view('home.index', ['listUser'=>$listUserAdd,'listOrder'=>$dateOrders,'arrOrder'=>$listOrder->toArray(),'listVps'=>$listVps]);
    }
}
