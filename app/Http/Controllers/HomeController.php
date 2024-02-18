<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{

    public function chart(){
        // $users = DB::select('SELECT COUNT(empID), dhired FROM employee WHERE YEAR(CURDATE()) GROUP BY  MONTH(dhired)');
        // return $users;
    }
}
