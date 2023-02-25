<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;


class AccountController extends Controller
{
    protected $UserRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {

        $this->UserRepo = $userRepo;
    }

    public function login()
    {
        dump(bcrypt('Ad@min*666'));
        $errors = session('errors');
        return view('account.login')->withErrors($errors);
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'userName' => 'required',
            'password' => 'required|min:6'
        ]);
        $remember = $request->boolean('remember');
        $userExit = $this->UserRepo->getByUserNameAndStatus($request->input('userName'),3);
        if ($userExit->count() > 0)
        {
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
        }
        return back()->withErrors([
            'userName' => 'Hãy kiểm tra lại tài khoản hoặc mật khẩu.',
        ]);
    }

    public function forgotPassword()
    {
        $errors = session('errors');
        return view('account.forgotpassword')->withErrors($errors);
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->string('email');
        $userExit = $this->UserRepo->getByEmail($email);
        if ($userExit->count() > 0) {
            $user = $userExit->first();
            $length = 6;
            $newPass =  Str::random($length);
            $user->password = bcrypt($newPass);
            $user->save();
            $testMailData = [
                'title' => 'Xin chào '.$user->userName,
                'body' => 'Mật khẩu mới của bạn là: <b>'.$newPass.'</b>'
            ];
            Mail::to($user->email)->send(new SendMail($testMailData));
            return redirect()->route('login')->with('status', 'Success! Email has been sent successfully.');
        }
        return back()->withErrors(['email' => 'Email không tồn tại!']);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
