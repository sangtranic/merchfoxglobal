<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Roles;
use App\Models\Users;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $UserRepo;
    protected $RoleRepo;
    protected $OrderRepo;
    public function __construct(UserRepositoryInterface $userRepo, RoleRepositoryInterface $roleRepo, OrderRepositoryInterface $orderRepo)
    {
        $this->middleware('auth');
        $this->UserRepo = $userRepo;
        $this->RoleRepo = $roleRepo;
        $this->OrderRepo = $orderRepo;
    }

    public function index()
    {
        $dateTo = Carbon::now();
        $dateFrom = Carbon::now()->addDays(-30);
        $listOrder= $this->OrderRepo->search($dateFrom->format('Y-m-d H:i:s.u'),$dateTo->format('Y-m-d H:i:s.u'));
        $listUser= $this->UserRepo->getAll();
        $listUser = $listUser->where('statusId', '=', 3);
        $listUserAdd = $listUser;
        if(Auth::user()->role != "admin")
        {
            $listUserAdd = $listUser->where('id', '=', Auth::user()->id);
        }
        return view('home.index', ['listUser'=>$listUserAdd,'listOrder'=>$listOrder]);
    }
}
