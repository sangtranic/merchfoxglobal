<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Roles;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\Users;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

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
        $users= $this->UserRepo->getAll();
        $listStatus = collect([
            ['id' => '0',  'name' => 'Chờ duyệt'],
            ['id' => '1', 'name' => 'Tạm dừng'],
            ['id' => '2',  'name' => 'Đang hoạt động']
        ]);
        $listRole = $this->RoleRepo->getAll();
        return view('users.index', ['users'=>$users,'listStatus' => $listStatus,'listRole' => $listRole]);
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
        $listRole = $this->RoleRepo->getAll()->pluck('name','id');
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
        $password = Hash::make($request->input('password'));
        $this->UserRepo->create([
            'userName' => $request->input('userName'),
            'password' => $password,
            'fullName' => $request->input('fullName'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'statusId' => $request->input('statusId'),
            'roleId' => $request->input('roleId'),
            'createBy' => $request->input('createBy'),
            'updateBy' => $request->input('updateBy')
        ]);
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
        $user = $this->UserRepo->find($id);
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
        $user = $this->UserRepo->find($id);
        $arrStatus = collect([
            ['id' => '0',  'name' => 'Chờ duyệt'],
            ['id' => '1', 'name' => 'Tạm dừng'],
            ['id' => '2',  'name' => 'Đang hoạt động']
        ]);
        $listStatus = $arrStatus->pluck('name', 'id');
        $listRole = $this->RoleRepo->getAll()->pluck('name','id');
        return view('users.edit',['user'=>$user,'listRole' => $listRole,'listStatus' => $listStatus]);
    }

    public function changepassword($id)
    {
        $user = $this->UserRepo->find($id);
        return view('users.changepassword',['user'=>$user]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatepassword(UserRequest $request, $id)
    {
        //$idEdit= $request->input('id');
        $newPassword = Hash::make($request->input('password'));
        $user = $this->UserRepo->find($id);
		$user->password = $request->input('password');
        $user->save();
        return view('users.show',['user'=>$user]);
//        //$user = $this->UserRepo->find($idEdit);
//
//        $this->UserRepo->update($idEdit,[
//            'userName' => $request->input('userName'),
//            'password' => $newPassword,
//            'fullName' => $request->input('fullName'),
//            'email' => $request->input('email'),
//            'mobile' => $request->input('mobile'),
//            'statusId' => $request->input('statusId'),
//            'roleId' => $request->input('roleId'),
//            'createBy' => $request->input('createBy'),
//            'updateBy' => $request->input('updateBy')
//        ]);
        //return redirect()->route('users.index');
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
        $user = $this->UserRepo->find($id);
        $password = $user->password;
        if($request->input('newpassword') != null)
        {
            $password = Hash::make($request->input('newpassword'));
        }
        $this->UserRepo->update($id,[
            'userName' => $request->input('userName'),
            'fullName' => $request->input('fullName'),
            'password' => $password,
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'statusId' => $request->input('statusId'),
            'roleId' => $request->input('roleId'),
            'createBy' => $request->input('createBy'),
            'updateBy' => $request->input('updateBy')
        ]);
        //$user = $this->UserRepo->find($id);
//		$user->userName = $request->input('userName');
//		$user->password = $request->input('password');
//		$user->fullName = $request->input('fullName');
//		$user->email = $request->input('email');
//		$user->mobile = $request->input('mobile');
//		$user->statusId = $request->input('statusId');
//		$user->roleId = $request->input('roleId');
//        $user->save();

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
