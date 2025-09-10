<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function showRegister ()
    {
        return view("auth.register");
    }

    public function register(Request $request)
    {
        // debug code
        // dd($request->all());

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
        ]);

        $validatedData['role'] = 'customer';

        $user = $this->userService->register($validatedData);

        Auth::login($user);
        return redirect()->intended('dashboard')->with('success', 'Registration successful! Please login.');
    }

    public function showlogin ()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return back()-> withErrors($validator);
        }

        $user = $this->userService->login($request->only('email', 'password'));

        if($user){
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email'=>'Invalid credentials']);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
