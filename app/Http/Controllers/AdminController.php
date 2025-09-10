<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
        $this->middleware('auth');
    }

    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied.');
        }

        $managerApplications = User::where('manager_status', 'pending')
            ->orderBy('manager_applied_at', 'desc')
            ->get();

        $allAdmins = User::where('role', 'admin')->get();
        $canManageAdmins = auth()->user()->isSuperAdmin();

        return view('admin.index', compact('managerApplications', 'allAdmins', 'canManageAdmins'));
    }

    public function createAdmin(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admins can create new admins.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'is_super_admin' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $admin = $this->adminService->createAdmin($request->all(), auth()->user());
            return back()->with('success', 'Admin user created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function promoteUser(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admins can promote users.');
        }

        try {
            $this->adminService->promoteToAdmin($user, auth()->user());
            return back()->with('success', "User {$user->name} promoted to admin successfully!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function demoteAdmin(Request $request, User $admin)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admins can demote admins.');
        }

        try {
            $this->adminService->demoteAdmin($admin, auth()->user());
            return back()->with('success', "Admin {$admin->name} demoted successfully!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
