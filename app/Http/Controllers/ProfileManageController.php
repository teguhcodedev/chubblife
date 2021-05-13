<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use App\Activity;
use App\MasterUserAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileManageController extends Controller
{
    // Open View Profile
    public function viewProfile()
    {
    	// $activity = Activity::where('id_user', Auth::id())
    	// ->latest()
    	// ->take(3)
    	// ->get();
    	// $activities = Activity::where('id_user', Auth::id())
    	// ->latest()
    	// ->get();

    	// return view('profile', compact('activity', 'activities'));
        $id_login = Auth::id();
        $MUA = MasterUserAgent::where('id',$id_login)->first();

    
        return view('profile',compact('MUA'));
    }

    // Change Profile Data
    public function changeData(Request $req){
    	$id = Auth::id();
    	$user_account = MasterUserAgent::find($id);
    	$check_phone = MasterUserAgent::all()
        ->where('AGENT_DN', $req->email)
        ->count();
        $check_username = MasterUserAgent::all()
        ->where('AGENT_NAME', $req->username)
        ->count();

        $user = User::find($id);

        if(($check_phone == 0 && $check_username == 0) || ($user_account->AGENT_DN == $req->dn && $user_account->username == $req->username) || ($check_phone == 0 && $user_account->username == $req->username) || ($user_account->AGENT_DN == $req->dn && $check_username == 0))
        {
	    	$user_account->AGENT_USERNAME = $req->username;
	    	$user_account->AGENT_DN = $req->dn;
	    	$user_account->AGENT_NAME = $req->fullname;
	    	$user_account->save();


            $user->username = $req->username;
	    	$user->fullname = $req->fullname;
	    	$user->save();

	    	Session::flash('update_success', 'Profil berhasil diubah');

            return redirect('/profile');
	    }
	    else if($check_phone != 0 && $check_username != 0 && $user_account->AGENT_DN != $req->dn && $user_account->username != $req->username)
        {
            Session::flash('update_error', 'Dial Number and this username has been already, please try again');

            return back();
        }
        else if($check_phone != 0 && $user_account->AGENT_DN != $req->dn)
        {
            Session::flash('update_error', 'Dial Number  has been already, please ctry again with other Dial Number');

            return back();
        }
        else if($check_username != 0 && $user_account->AGENT_USERNAME != $req->username)
        {
            Session::flash('update_error', 'this username has been already, please try again with other Dial Number');

            return back();
        }
    }

    // Change Profile Picture
    public function changePicture(Request $req){
		$user = User::find(Auth::id());
        $MUA = MasterUserAgent::find(Auth::id());

    	$foto = $req->file('foto');
        $user->foto = $foto->getClientOriginalName();
        
        $MUA->AGENT_PHOTO = $foto->getClientOriginalName();
        $foto->move(public_path('chubb_foto/'), $foto->getClientOriginalName());
        $user->save();
        $MUA->save();

        Session::flash('update_success', 'Photo profile has been updated');

        return redirect('/profile');
    }

    // Change Profile Password
    public function changePassword(Request $req)
    {
    	$users = User::find(Auth::id());
        if(Hash::check($req->old_password, $users->password)){
            User::where('id', '=', Auth::id())
            ->update(['password' => Hash::make($req->new_password)]);

            MasterUserAgent::where('id', '=', Auth::id())
            ->update(['AGENT_PWD' => Hash::make($req->new_password)]);
            
            Session::flash('update_success', 'Password berhasil diubah');

            return redirect('/profile');

        }else{
            Session::flash('update_error', 'Password lama tidak sesuai');

            return back();
        }
    }
}