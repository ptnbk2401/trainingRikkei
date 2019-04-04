<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Vadmin\Core\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Socialite;

class AuthController extends Controller
{

    public function postLogin(Request $request){
    	$username = $request->username;
    	$password = $request->password;

    	if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $id_user = Auth::user()->id;
            $objCB = User::getCapbacUser($id_user);
            if($objCB->code == 'khachhang') {
                return redirect()->intended(route('vpublic.core.pcindex.index'));
            }
            return redirect()->intended(route('vadmin.core.index.index'));
        } else {
            $request->session()->flash('msg', 'Sai username hoặc password!');
        	return redirect()->intended(route('auth.auth.login'));
        }
    }

    public function logout(){
        Auth::logout();
        Session::forget('objUser');
        Session::forget('arCodePhongBan');
        Session::forget('arIdPhongBan');
        Session::forget('arCodeChucVu');
        Session::forget('isQuanLy');
        Session::forget('isGiamDoc');

        return redirect()->route('login');
    }

    
}
