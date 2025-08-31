<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;

    public function _construct(UserService $userService)
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
            'phone_number' => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/|confirmed',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'email', 'phone']);
        if($request->password){
            $data['password'] = $request->password;
        }

        $this->userService->updateProfile(auth()->user(), $data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function showManagerApplication(){
        $user = auth()->user();

        if(!$user->canApplyForManager() && !$user->hasManagerApplicationPending()){
            return redirect()->route('profile')->with('error', 'You cannot apply for manager role at this time. ');
        }

        return view('user.manager-application',['user'=>$user]);
    }

    public function submitManagerApplication(Request $request){
        $user = auth()->user();

        if(!$user->caApplyForManager()){
            return redirect()->route('profile')->with('error', 'You cannot apply for manager role at this time. ');
        }

        $validator = Validator::make($request->all(),[
            'reason'=>'required|string|min"50|max:1000',
        ]);

        if ($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $user-->applyForManager($request->reason);

        return redirect()->route('profile')->with('success', 'Manager application submitted successfully!');
    }

    public function managerApplication(){
        if(!auth()->user()->isAdmin()){
            abort(403);;
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
