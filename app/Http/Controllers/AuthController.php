<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserSService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

//USER REGISTRATION FUNCTIONS

    protected $userService;

    public function _construct(userService $userService){
        $this->userService = $userService;
    }

    public function showRegister ()
    {
        return view("auth.register");
    }

    // Handle registration
    public function register(Request $request)
    {
        // debug code
        // dd($request->all());

        $request -> validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
        ]);

        if ($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        // $data =$request->all();
        // $data['role']='customer';

        // $user = $this->userService->register($data);

        // return redirect()->route('login')->with('success', 'Registration successful! Please login.');
                $user = User::create([
            'user_code' => $this->generateUserCode(), // Changed from 'id' to 'user_code'
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        Auth::login($user);

        return redirect()->intended('dashboard')->with('success', 'Registration successful!');
    }

    //USER LOGIN FUNCTIONS
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
        $this->userService->logout();
        $request->session()->logout();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
