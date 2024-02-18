<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CompanyController extends Controller
{
    // ADD
    public function addCompany(){
    	request()->merge(['status' => 1]);
        $data = DB::table('companytbl')->insertGetId(request()->except(['isDisable', 'compID']));

        request()->merge(['compID' => $data]);
    	return Request()->all();
    }
    // UPDATE
    public function updateCompany(){
        $data = DB::table('companytbl')->where('compID',  request('compID'))->update(request()->except(['isDisable']));

    	return Request()->all();
    }


    public function getCompany(){
    	$data = DB::select('select compID, compname from companytbl where status = 1 order by compname');
    	return $data;
    }

    public function delCompany(){
        // $data = DB::table('companytbl')->where('compID',  request('compID'))->update(['status' => 0]);
        // return $data;

        $data = DB::select('select empID from employee where compID_ = :compid and status = 1', [request('compID')]);
        if(count($data)>0){

            return ['error'=>count($data)];
        }else{
            DB::table('companytbl')->where('compID', request('compID'))->delete();
            return $data;
        }
    }
}
