<?php

// namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Support\Facades\Storage;

class SigninController extends Mgr
{
    use AuthenticatesUsers;

    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        
        $validated = $request->validate([
            'ID_number' => 'required',
            'password' => 'required',
        ]);
       
        
        $credentials = $request->only('ID_number', 'password');
        // print_r($credentials);exit;
        if (Auth::guard('mgr')->attempt($credentials)) {
            Storage::disk('local')->put('log.txt', $request->input('email').": Login\n");

            if (Auth::guard('mgr')->user()->status == 'off') {
                Storage::disk('local')->put('log.txt', $request->input('email').": blocked\n");
                $this->logout($request);
            }
            return redirect()->route('mgr.home');
        }
        
        return redirect()->route("mgr.login")->with('error', '帳號/密碼錯誤');
    }

    public function showLoginForm(Request $request)
    {
        return view('mgr/auth/login', ['error'=>$request->input('error')]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('mgr.home');
    }
}
