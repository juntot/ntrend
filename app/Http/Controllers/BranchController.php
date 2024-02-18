<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;

class BranchController extends Controller
{
    // ADD
    public function addBranch()
    {
    	request()->merge(['status' => 1]);
        $data = DB::table('branchtbl')->insertGetId(request()->except(['isDisable', 'branchID']));

        // merge inserted ID
        request()->merge(['branchID' => $data]);
    	return request()->all();
    }
    // UPDATE
    public function updateBranch()
    {
    	request()->merge(['status' => 1]);
    	$data = DB::table('branchtbl')->where('branchID', request('branchID'))->update(request()->except(['isDisable']));
    	return request()->except(['isDisable']);
    }

    public function getBranch()
    {
    	$data = DB::select('select * from branchtbl where status = 1 order by branchname');
   	 	return $data;
    }


    public function delBranch()
    {
        // $data = DB::table('branchtbl')->where('branchID', request('branchID'))->update(['status' => 0]);
        // return $data;
        $data = DB::select('select empID from employee where branchID_ = :branchID and status = 1', [request('branchID')]);
        if(count($data)>0){

            return ['error'=>count($data)];
        }else{
            DB::table('branchtbl')->where('branchID', request('branchID'))->delete();
            return $data;
        }
    }
}
