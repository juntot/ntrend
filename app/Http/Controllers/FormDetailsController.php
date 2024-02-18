<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FormDetailsController extends Controller
{
    //
    public function addFormByDept($formid = null, $deptid = null){
            $data = DB::select('select formID_ from eformsdetails where formID_ = :formid and deptID_ = :deptid', 
                ['formid'=>$formid, 'deptid'=>$deptid]);
           
            if(count($data) <= 0)
            {
                $data = DB::table('eformsdetails')->insert(['formID_' => $formid, 'deptID_' => $deptid]);
            }else{
                DB::table('eformsdetails')
                    ->where('formID_', $formid)
                    ->where('deptID_', $deptid)
                    ->update(['status' => 1]);
            }
    }

    public function unCheckFormByDept($formid = null, $deptid = null){
        DB::table('eformsdetails')
        ->where('formID_', $formid)
        ->where('deptID_', $deptid)
        ->update(['status' => 0]);
    }
}
