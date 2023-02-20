<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Roles;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\Users;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class UsersController extends Controller
{
    protected $UserRepo;
    protected $RoleRepo;
    public function __construct(UserRepositoryInterface $userRepo, RoleRepositoryInterface $roleRepo)
    {
        $this->UserRepo = $userRepo;
        $this->RoleRepo = $roleRepo;
    }
    public function index()
    {
        $users= Users::all();
        return view('users.index', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrStatus = collect([
            ['id' => '0',  'name' => 'Chờ duyệt'],
            ['id' => '1', 'name' => 'Tạm dừng'],
            ['id' => '2',  'name' => 'Đang hoạt động']
        ]);
        $listStatus = $arrStatus->pluck('name', 'id');
        $listRole = Roles::all()->pluck('name','id');
        $allRole = $this->RoleRepo->getAll();

        return view('users.create', ['listRole' => $listRole,'listStatus' => $listStatus]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->UserRepo->create([
            'userName' => $request->input('userName'),
            'password' => $request->input('password'),
            'fullName' => $request->input('fullName'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'statusId' => $request->input('statusId'),
            'roleId' => $request->input('roleId'),
            'createBy' => $request->input('createBy'),
            'updateBy' => $request->input('updateBy')
        ]);
//        $user = new Users;
//		$user->userName = $request->input('userName');
//		$user->password = $request->input('password');
//		$user->fullName = $request->input('fullName');
//		$user->email = $request->input('email');
//		$user->mobile = $request->input('mobile');
//		$user->statusId = $request->input('statusId');
//		$user->roleId = $request->input('roleId');
//        $user->createBy = $request->input('createBy');
//        $user->updateBy = $request->input('updateBy');
//        $user->save();
        //
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Users::findOrFail($id);
        return view('users.show',['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Users::findOrFail($id);
        return view('users.edit',['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = Users::findOrFail($id);
		$user->userName = $request->input('userName');
		$user->password = $request->input('password');
		$user->fullName = $request->input('fullName');
		$user->email = $request->input('email');
		$user->mobile = $request->input('mobile');
		$user->statusId = $request->input('statusId');
		$user->roleId = $request->input('roleId');
        $user->save();

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Users::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index');
    }
}
