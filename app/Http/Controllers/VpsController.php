<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\VpsRequest;
use App\Models\Roles;
use App\Models\Vps;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Vps\VpsRepositoryInterface;

class VpsController extends Controller
{
    protected $UserRepo;
    protected $VpsRepo;
    public function __construct(UserRepositoryInterface $userRepo, VpsRepositoryInterface $vpsRepo)
    {
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
        return view('vps.index', ['listUser'=>$listUser, 'listVps' => $listVps]);
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
        $vps = Vps::findOrFail($id);
        $vps->delete();

        return redirect()->route('vps.index');
    }
}
