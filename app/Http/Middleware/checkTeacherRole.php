<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{LogsModel, School};

class checkTeacherRole
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
        $user = Auth::user();
        if ($user->usertype != 'teacher') {
            return redirect(route('signout'));
        } else {
            if ($user) {
                $last_logged_session = LogsModel::where(['userid' => $user->id, 'action' => 'login'])->take(2)->orderByDesc('id')->get()->pluck('session_id');
                $session_id = session()->getId();
                $school_data = School::find($user->school_id);
                if ($school_data->is_demo == 0) {
                    if ((count($last_logged_session) > 0) && $last_logged_session->doesntContain($session_id)) {
                        return redirect(route('access.denied'));
                    }
                }
                // dd($last_logged_session,$session_id);
            }
        }
        return $next($request);
    }
}
