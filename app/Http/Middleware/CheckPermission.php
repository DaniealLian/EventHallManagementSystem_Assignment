<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\UserService;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }


    public function handle(Request $request, Closure $next, string $permission)
    {
        if(!auth()->check()){
            return redirect('login');
        }

        if(!$this->userService->canUserPerform(auth()->user(), $permission)){
            abort(403, 'You have insufficient permission/authority to do this action');
        }

        return $next($request);
    }
}
