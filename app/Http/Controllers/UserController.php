<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;

class UserController extends Controller
{
    //
    public function Home()
    {

      if(session()->get('auth.empID') && session()->get('auth.type') == 1){
        return view('admin');
      }
      if(session()->get('auth.empID') && session()->get('auth.type') == 0){
        return view('authuser');
      }

    }

    // LOGIN VIEW
   public function login()
   {

      if(session()->get('auth.empID')){
        return redirect('/');
      }else{
        return view('/login');
      }

   }
  //  AUTH LOGIN
   public function authLogin()
   {

		   	$credentials = [
		        'empID' => request('empid'),
		        'password' => request('password')
		    ];


      // $user = DB::table('users')->where('empID', request('empid'))->orWhere('username', request('empid'))->get();
      $user = DB::select('select * from users where (empID = :emp or username = :uname or email = :email) and status = 1', [request('empid'), request('empid'), request('empid')]);

      if(count($user) > 0 && Hash::check(request('password'), ($user[0]->password)))
      {
          session(['auth.empID' => $user[0]->empID, 'auth.type' => $user[0]->isAdmin]);
          return redirect()->route('home');
      }


      request()->flash();
      return redirect()->back()->withErrors(['Invalid Employe ID or Password']);

  }

  // LOGOUT
  public function logout()
  {
      session()->flush();
      return redirect()->route('login');
  }

  // CHANGE PASS
  public function changePass(){


      $user = DB::table('users')->where('empID', request('empID'))->get();
      if(count($user) > 0 && Hash::check(request('oldpass'), ($user[0]->password)))
      {
          return DB::table('users')
          ->where('empID', request('empID'))
          ->update(['password'=> Hash::make(request('password'))]);
      }
      return response()->json(['err' => 'old password not match']);


  }

  // RESET PASSWORD
  public function resetPass(){
    DB::table('users')->where('empID', request('empID'))->update(['password'=> Hash::make('1234')]);
  }

  // GET SESSION
  public function getSession()
  {
    if (request()->session()->has('auth')) {

      return session()->get('auth');
    }
    return false;
  }



  public function updateUserFormRoles($empID = null, $type = null, $role = null)
  {
      DB::table('users')
          ->where('empID', $empID)
          ->update([$type => $role]);
  }

 // ISADMIN ETC.
  public function updateUserRole($empID = null, $role = null, $val = null)
  {
      DB::table('users')
          ->where('empID', $empID)
          ->update([$role => $val]);
  }

}
