<?php

namespace App\Http\Controllers;

use App\Http\Requests\VpsRequest;
use App\Models\Users;
use App\Models\Vps;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Vps\VpsRepositoryInterface;

class VpsController extends Controller
{
    protected $UserRepo;
    protected $VpsRepo;
    public function __construct(UserRepositoryInterface $userRepo, VpsRepositoryInterface $vpsRepo)
    {
        $this->middleware('auth');
        $this->UserRepo = $userRepo;
        $this->VpsRepo = $vpsRepo;
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
        $userIdFilter = request('userId');
        if($userIdFilter>0)
        {
            $listVps = $listVps->where('userId', '=', $userIdFilter);
        }
        $newUser = new Users(['id' => '0',  'userName' => 'Chọn tài khoản...']);
        $listUserAdd = $listUser->prepend($newUser);
        $listUserPluck = $listUserAdd->pluck('userName','id');

        return view('vps.index', ['listUser'=>$listUser, 'listVps' => $listVps,'listUserPluck' =>$listUserPluck]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listUser = $this->UserRepo->getAll()->pluck('userName','id');
        return view('vps.create', ['listUser' => $listUser]);
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
            'userId' => $request->input('userId')
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
        return view('vps.edit',['vps'=>$vps,'listUser' => $listUser]);
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
            'userId' => $request->input('userId')
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
