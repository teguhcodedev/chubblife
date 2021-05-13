<?php

namespace App\Http\Controllers;

use App\MasterUserAgent;
use Auth;
use Session;
use App\User;
use Illuminate\Http\Request;

class AuthManageController extends Controller
{
    // Show View Login
    public function viewLogin()
    {
    	$users = User::all()
    	->count();

    	return view('login', compact('users'));
    }

    // Verify Login
    public function verifyLogin(Request $request)
    {
         

    	if(Auth::attempt($request->only('username', 'password'))){
          
            User::where('username',$request->username)->update(
                ['ip_address'=>\request()->ip()]
            );

         
        
            MasterUserAgent::where('AGENT_USERNAME', $request->username)
                    ->update(
                        ['LOGIN_STATUS' => '1'],
                        ['LOGIN_IP'=>\request()->ip()]
                        
            );

    		return redirect('/chubblife-dashboard');
    	}
    	 Session::flash('login_failed', 'Username atau password salah');
    	
    	return redirect('/login');
    }

    // Logout Process
    public function logoutProcess()
    {
        $user = Auth::user();
        User::where('id',$user->id)->update(
            ['last_login'=>date('Y-m-d H:i:s')]
        );

        MasterUserAgent::where('id', $user->id)
        ->update(
            ['LOGIN_STATUS' => '0'],
            
);

    	Auth::logout();

    	return redirect('/login');
    }
}