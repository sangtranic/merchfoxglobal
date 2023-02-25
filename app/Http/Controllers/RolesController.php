<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Requests\RoleRequest;
use App\Models\Roles;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Vps\VpsRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    protected $UserRepo;
    protected $RoleRepo;
    public function __construct(RoleRepositoryInterface $roleRepo)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role != 'admin') {
                abort(403, 'Bạn không có quyền truy cập.');
            }
            return $next($request);
        });
        $this->RoleRepo = $roleRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles= $this->RoleRepo->getAll();
        return view('roles.index', ['roles'=>$roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $this->RoleRepo->create([
            'name' => $request->input('name'),
            'code' => $request->input('code')
        ]);
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Roles::findOrFail($id);
        return view('roles.show',['role'=>$role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->RoleRepo->find($id);
        return view('roles.edit',['role'=>$role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $this->RoleRepo->update($id,[
            'name' => $request->input('name'),
            'code' => $request->input('code')
        ]);
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->RoleRepo->delete($id);
        return redirect()->route('roles.index');
    }
}
