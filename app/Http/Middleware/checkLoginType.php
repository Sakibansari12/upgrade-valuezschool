<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkLoginType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->usertype == 'superadmin' || $user->usertype == 'contentadmin') {
                return redirect(route('admin-dashboard'));
            } else if ($user->usertype == 'teacher') {
                return redirect(route('teacher.class.list'));
            } else if ($user->usertype == 'admin') {
                return redirect(route('school.teacher.list'));
            }else if ($user->usertype == 'student') {
                return redirect(route('student.grade.list'));
            }
        } else {
            return $next($request);
        }
    }
}
