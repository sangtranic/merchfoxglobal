<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerRequest;
use App\Models\Users;
use App\Models\Seller;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Seller\SellerRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    protected $UserRepo;
    protected $SellerRepo;
    public function __construct(UserRepositoryInterface $userRepo, SellerRepositoryInterface $sellerRepo)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role != 'admin') {
                abort(403, 'Bạn không có quyền truy cập.');
            }
            return $next($request);
        });
        $this->UserRepo = $userRepo;
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
        $listSeller = $this->SellerRepo->getAll();
        $userIdFilter = request('userId');
        if($userIdFilter>0)
        {
            $listSeller = $listSeller->where('userId', '=', $userIdFilter);
        }
        $newUser = new Users(['id' => '0',  'userName' => 'Chọn tài khoản...']);
        $listUserAdd = $listUser->prepend($newUser);
        $listUserPluck = $listUserAdd->pluck('userName','id');

        return view('seller.index', ['listUser'=>$listUser, 'listSeller' => $listSeller,'listUserPluck' =>$listUserPluck]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listUser = $this->UserRepo->getAll()->pluck('userName','id');
        return view('seller.create', ['listUser' => $listUser]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SellerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerRequest $request)
    {
        $this->SellerRepo->create([
            'sellerName' => $request->input('sellerName'),
            'userId' => $request->input('userId')
        ]);
        return redirect()->route('seller.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seller = $this->SellerRepo->find($id);
        return view('seller.show',['seller'=>$seller]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seller = $this->SellerRepo->find($id);
        $listUser = $this->UserRepo->getAll()->pluck('userName','id');
        return view('seller.edit',['seller'=>$seller,'listUser' => $listUser]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SellerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SellerRequest $request, $id)
    {
        $this->SellerRepo->update($id,[
            'sellerName' => $request->input('sellerName'),
            'userId' => $request->input('userId')
        ]);
        return redirect()->route('seller.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->SellerRepo->delete($id);
        return redirect()->route('seller.index');
    }
}
