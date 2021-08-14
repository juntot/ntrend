<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DepartmentController extends Controller
{
    // ADD
    public function addDept(){
    	request()->merge(['status' => 1]);
        $data = DB::table('department')->insertGetId(request()->except(['isDisable', 'deptID']));
        $deptname = request('deptname');
        $deptname = str_replace(' ','0',$deptname);
        $deptname = str_replace('-','_',$deptname);
        $deptname = str_replace('&','8',$deptname);

        DB::select('ALTER TABLE eforms ADD '.$deptname.'  INT default 0', ['colname'=>request('deptname')]);
        // DB::select("CALL alterTable('$deptname')");

        // merge inserted id
        request()->merge(['deptID' => $data]);
    	return Request()->all();
    }
    // UPDATE
    public function updateDept(){
        // ALTER TABLE `eforms` CHANGE `Accounting` `Accountingx` INT(11) NULL DEFAULT '0';
        request()->merge(['status' => 1]);
        $deptnameold = DB::select('select deptname from department where deptID = :deptid', [request('deptID')]);
        $data = DB::table('department')->where('deptID',  request('deptID'))->update(request()->except(['isDisable']));
        $deptname = request('deptname');
        $deptname = str_replace(' ','0',$deptname);
        $deptname = str_replace('-','_',$deptname);
        $deptname = str_replace('&','8',$deptname);

        $deptnameold = str_replace(' ','0', str_replace('-','_', str_replace('&','8',$deptnameold[0]->deptname)));
        DB::select('ALTER TABLE eforms CHANGE '.$deptnameold.' '.$deptname.' tinyint null default 0');

    	return Request()->all();
    }


    public function getDept(){
    	$data = DB::select('select deptID, deptname from department where status = 1');
    	return $data;
    }

    public function delDept(){
        // ALTER TABLE tbl_Country DROP COLUMN IsDeleted;

        // $data = DB::table('department')->where('deptID',  request('deptID'))->update(['status' => 0]);
        $data = DB::select('select empID from employee where deptID_ = :deptid', [request('deptID')]);
        if(count($data)>0){

            return ['error'=>count($data)];
        }else{
            DB::table('department')->where('deptID', request('deptID'))->delete();


            $deptname = request('deptname');
            $deptname = str_replace(' ','0',$deptname);
            $deptname = str_replace('-','_',$deptname);
            $deptname = str_replace('&','8',$deptname);

            DB::select('ALTER TABLE eforms DROP '.$deptname.'');
            return $data;
        }


    }

}
