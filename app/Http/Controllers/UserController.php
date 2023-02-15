<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $UserRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->UserRepo = $userRepo;
    }

    public function index()
    {
        $allUser = $this->UserRepo->getAllUser();

        return view('User.index', ['users' => $allUser]);
    }

    public function show($id)
    {
        $product = $this->UserRepo->find($id);

        return view('User.product', ['product' => $product]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //... Validation here

        $product = $this->UserRepo->create($data);

        return view('User.products');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        //... Validation here

        $product = $this->UserRepo->update($id, $data);

        return view('User.products');
    }

    public function destroy($id)
    {
        $this->UserRepo->delete($id);

        return view('User.products');
    }
}
