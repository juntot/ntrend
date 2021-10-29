<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class GreetingController extends Controller
{
    //Birthday greetings
    function birthdayGreeting(){
       $result = DB::select('select * from birthdategreetings'); 
       return $result;
    }
    
}
