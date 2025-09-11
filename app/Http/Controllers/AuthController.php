<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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
    $key = $this->throttleKey($request);
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                $minutes = ceil($seconds / 60);

                return back()->withErrors([
                    'email' => "Too many login attempts. Please try again in {$minutes} minute(s)."
                ])->withInput($request->only('email'));
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return back()->withErrors($validator);
            }

            $user = $this->userService->login($request->only('email', 'password'));

            if($user){
                // Clear the rate limiter on successful login
                RateLimiter::clear($key);

                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }

            // Increment failed attempts
            RateLimiter::hit($key, 15 * 60); // 15 minutes

            return back()->withErrors(['email' => 'Invalid credentials']);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }

}
