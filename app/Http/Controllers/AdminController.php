<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['showLogin', 'login']);
    }

    // Show admin login form
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.adminLogin');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            // Update last login
            Auth::guard('admin')->user()->update(['last_login_at' => now()]);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Admin logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    // Admin dashboard
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalManagers = User::where('role', 'manager')->count();
        $pendingApplications = User::where('manager_status', 'pending')->count();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.adminDashboard', compact(
            'totalUsers',
            'totalManagers',
            'pendingApplications',
            'recentUsers'
        ));
    }

    // Show all users
    public function users()
    {
        $users = User::with(['events', 'reservations'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    // Show manager applications
    public function managerApplications()
    {
        $applications = User::where('manager_status', 'pending')
            ->orderBy('manager_applied_at', 'desc')
            ->get();

        return view('admin.managerMngment', compact('applications'));
    }

    // Approve manager application
    public function approveApplication(User $user)
    {
        if ($user->manager_status !== 'pending') {
            return back()->with('error', 'This application is not pending approval.');
        }

        $user->update([
            'role' => 'manager',
            'manager_status' => 'approved',
        ]);

        return back()->with('success', "Manager application for {$user->name} has been approved!");
    }

    public function rejectApplication(User $user)
    {
        if ($user->manager_status !== 'pending') {
            return back()->with('error', 'This application is not pending approval.');
        }

        $user->update([
            'manager_status' => 'rejected',
        ]);

        return back()->with('success', "Manager application for {$user->name} has been rejected.");
    }

    public function deleteUser(User $user)
    {
        $userName = $user->name;
        $user->delete();

        return back()->with('success', "User {$userName} has been deleted successfully.");
    }

    public function promoteToManager(User $user)
    {
        if ($user->role === 'manager') {
            return back()->with('error', 'User is already a manager.');
        }

        $user->update([
            'role' => 'manager',
            'manager_status' => 'approved',
        ]);

        return back()->with('success', "User {$user->name} has been promoted to manager!");
    }

    public function demoteManager(User $user)
    {
        if ($user->role !== 'manager') {
            return back()->with('error', 'User is not a manager.');
        }

        $user->update([
            'role' => 'customer',
            'manager_status' => 'none',
        ]);

        return back()->with('success', "Manager {$user->name} has been demoted to customer.");
    }

}
