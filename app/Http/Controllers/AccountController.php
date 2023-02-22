<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $UserRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {

        $this->UserRepo = $userRepo;
    }

    public function login()
    {
        return view('account.login');
    }
    public function doLogin(Request $request) {
        $credentials = $request->validate([
            'userName' =>'required',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        dump($credentials);
//        return back()->withErrors([
//            'email' => 'The provided credentials do not match our records.',
//        ]);
    }

    public function forgotPassword()
    {
        return view('account.forgotpassword');
    }
}
