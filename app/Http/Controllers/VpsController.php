<?php

namespace App\Http\Controllers;

use App\Http\Requests\VpsRequest;
use App\Models\Users;
use App\Models\Vps;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Vps\VpsRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class VpsController extends Controller
{
    protected $UserRepo;
    protected $VpsRepo;
    protected $SellerRepo;
    public function __construct(UserRepositoryInterface $userRepo, VpsRepositoryInterface $vpsRepo,
                                SellerRepositoryInterface $sellerRepo)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role != 'admin') {
                abort(403, 'Bạn không có quyền truy cập.');
            }
            return $next($request);
        });
        $this->UserRepo = $userRepo;
        $this->VpsRepo = $vpsRepo;
        $this->SellerRepo = $sellerRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listUser= $this->UserRepo->getAll();
        $listVps = $this->VpsRepo->getAll();
        $listSeller = $this->SellerRepo->getAll();
        $userIdFilter = request('userId');
        if($userIdFilter>0)
        {
            $listVps = $listVps->where('userId', '=', $userIdFilter);
        }
        $newUser = new Users(['id' => '0',  'userName' => 'Chọn tài khoản...']);
        $listUserAdd = $listUser->prepend($newUser);
        $listUserPluck = $listUserAdd->pluck('userName','id');

        return view('vps.index', ['listUser'=>$listUser, 'listVps' => $listVps,
            'listUserPluck' =>$listUserPluck,'listSeller' =>$listSeller]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listUser = $this->UserRepo->getAll();
        $userId = $listUser[0]->id;
        $listUserPluck = $listUser->pluck('userName','id');
        $listSeller = $this->SellerRepo->getAll();
        $listSeller = $listSeller->where('userId', '=', $userId);
        $listSellerPluck = $listSeller->pluck('sellerName','id');
        return view('vps.create', ['listUser' => $listUserPluck,'listSeller' => $listSellerPluck]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  VpsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VpsRequest $request)
    {
        $this->VpsRepo->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'userId' => $request->input('userId'),
            'sellerId' => $request->input('sellerId')
        ]);
        return redirect()->route('vps.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vps = $this->VpsRepo->find($id);
        return view('vps.show',['vps'=>$vps]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vps = $this->VpsRepo->find($id);
        $listUser = $this->UserRepo->getAll()->pluck('userName','id');
        $listSeller = $this->SellerRepo->getAll();
        if($vps->userId >0)
        {
            $listSeller = $listSeller->where('userId', '=', $vps->userId);
        }
        $listSellerPluck = $listSeller->pluck('sellerName','id');
        return view('vps.edit',['vps'=>$vps,'listUser' => $listUser,'listSeller' => $listSellerPluck]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  VpsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VpsRequest $request, $id)
    {
        $this->VpsRepo->update($id,[
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'userId' => $request->input('userId'),
            'sellerId' => $request->input('sellerId')
        ]);
        return redirect()->route('vps.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->VpsRepo->delete($id);
        return redirect()->route('vps.index');
    }
}
