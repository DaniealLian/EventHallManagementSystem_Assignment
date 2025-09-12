<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile()
    {
        return view('user.profile', ['user'=>auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id(),
            'password' => 'nullable|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
        ]);

        if($validator->fails())
            {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'email', 'phone_number']);
        if($request->password)
            {
            $data['password'] = $request->password;
        }

        $this->userService->updateProfile(auth()->user(), $data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function showManagerApplication(){
        $user = auth()->user();

        if(!$user->hasPermission('apply_for_manager'))
            {
            return redirect()->route('profile')->with('error', 'You cannot apply for manager role at this time. ');
        }

        if($user->hasManagerApplicationPending())
            {
            return redirect()->route('profile')->with('error','You already have a pending application.');
        }

        return view('auth.eventMngRegister',['user'=>$user]);
    }

    public function submitManagerApplication(Request $request)
    {
        $user = auth()->user();

        if(!$user->hasPermission('apply_for_manager'))
        {
            abort(403, 'You have insufficient permission/authority to do this action');
        }

        if (!$user->canApplyForManager())
            {
            return redirect()->route('profile')->with('error', 'You cannot apply for manager role at this time.');
        }

        $validator = Validator::make($request->all(), [
            'company_address' => 'required|string|max:1000',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'experience' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails())
            {
            return back()->withErrors($validator)->withInput();
        }

        $user->applyForManager($request->only(['company_address', 'company_name', 'company_email', 'experience']));

        return redirect()->route('profile')->with('success', 'Manager application submitted successfully!');
    }

    public function managerApplication(){
        if(!auth()->user()->isAdmin()){
            abort(403, 'You have insufficient permission/authority to do this action');
        }

        $applications = \App\Models\User::where('manager_status', 'pending')
            ->orderBy('manager_applied_at', 'desc')
            ->get();

        return view('admin.manager-applications', compact('applications'));
    }

    public function reviewMangerApplication(Request $request, \App\Models\User $user){
        if(!auth()->user()->isAdmin()){
            abort(403);;
        }

        $validator = Validator::make($request->all(),[
            'action'=>'required|in:approve,reject',
        ]);

        if ($validator->fails()){
            return back()->withErrors($validator);
        }

        if($request->action === 'approve'){
            $user->approveManagerApplication();
            $message = 'Manager application approved successfully!';
        }else {
            $user->rejectManagerApplication();
            $message = 'Manager application rejected.';
        }

        return back()->with('success', $message);
    }
}
