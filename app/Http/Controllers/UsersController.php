<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Controllers\Controller;

use App\Models\Roles;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\Users;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use League\Csv\Writer;

class UsersController extends Controller
{
    protected $UserRepo;
    protected $RoleRepo;
    public function __construct(UserRepositoryInterface $userRepo, RoleRepositoryInterface $roleRepo)
    {
        $this->middleware('auth');
        $this->UserRepo = $userRepo;
        $this->RoleRepo = $roleRepo;
    }
    public function index()
    {
        $users= $this->UserRepo->getAll();
        $statusFilter = request('status');
        if($statusFilter>0)
        {
            $users = $users->where('statusId', '=', $statusFilter);
        }
        $roleFilter = request('role');
        if($roleFilter>0)
        {
            $users = $users->where('roleId', '=', $roleFilter);
        }
        $listStatus = Helper::getListStatus();
        $listStatusPluck = $listStatus->pluck('name','id');
        $listRole = $this->RoleRepo->getAll();
        $newRole = new Roles(['id' => '0',  'name' => 'Chọn quyền...']);
        $listRoleAdd = $listRole->prepend($newRole);
        $listRolePluck = $listRoleAdd->pluck('name','id');
        return view('users.index', ['users'=>$users,'listStatus' => $listStatus,'listStatusPluck' =>$listStatusPluck,'listRole' => $listRole,'listRolePluck' =>$listRolePluck]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arrStatus = Helper::getListStatus();
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
        $errors = [];
        if ($request->has('userName')) {
            $userExit = $this->UserRepo->getByUserName($request->input('userName'));

            $roleName = "";
            if ($request->has('roleId')) {
                $listRole = $this->RoleRepo->getAll();
                $role = $listRole->where('id', $request->input('roleId'))->first();
                if($role!=null)
                {
                    $roleName = $role->code;
                }
            }
            if ($userExit->count() == 0)
            {
                $password = bcrypt($request->input('password'));
                $this->UserRepo->create([
                    'userName' => $request->input('userName'),
                    'password' => $password,
                    'fullName' => $request->input('fullName'),
                    'email' => $request->input('email'),
                    'mobile' => $request->input('mobile'),
                    'statusId' => $request->input('statusId'),
                    'roleId' => $request->input('roleId'),
                    'createBy' => $request->input('createBy'),
                    'updateBy' => $request->input('updateBy'),
                    'role' => $roleName
                ]);
                return redirect()->route('users.index');
            }
        }else
        {
            $errors[] = 'userName đã tồn tại';
            return back()->withErrors($errors);
        }
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
        $arrStatus = Helper::getListStatus();

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
        $newPassword = bcrypt($request->input('password'));
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
        if ($request->has('newpassword')) {
            $password = bcrypt($request->input('newpassword'));
        }
        $roleName = "";
        if ($request->has('roleId')) {
            $listRole = $this->RoleRepo->getAll();
            $role = $listRole->where('id', $request->input('roleId'))->first();
            if($role!=null)
            {
                $roleName = $role->code;
            }
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
            'updateBy' => $request->input('updateBy'),
            'role' => $roleName
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

    public function exportToCsv()
    {
        // Array data to export
        $statusFilter = request('status');
        $data = [
            ['Name', 'Email'],
            ['John Doe', 'john@example.com'],
            ['Jane Doe', 'jane@example.com'.$statusFilter],
            ['Bob Smith', 'bob@example.com'],
        ];

        // Create a new CSV writer
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        // Insert the data into the CSV
        $csv->insertAll($data);

        // Set the response headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ];

        // Create the HTTP response with the CSV file
        $response = new Response($csv->__toString(), 200, $headers);

        return $response;
    }
}
