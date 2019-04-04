<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use App\Model\User\GroupIndex;
use App\User;
use Auth;
use Closure;

class RoleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role)
    {
        $arRole = explode('|', $role);
        $groups = DB::table('groups')->whereIn('gname',$arRole)->get();
        foreach ($groups as $group) {
            $group = GroupIndex::find($group->id);
            foreach ($group->users as $user) {
                if( $user->id === Auth::id()){
                    return $next($request);
                }
            }
        }
        return redirect('/')->with('msg','Không đủ quyền');
        
    }
}
