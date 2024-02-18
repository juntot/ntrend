<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;


class WitnessSupController extends Controller
{
     // Check ID if is for witness
     public function getWitnessNav(){
        $data = DB::select('select approverID_ as count from supplementary_witness where approverID_ = :approver limit 1',
                [UserSession::getSessionID()]);
        return $data;
     }

    // get all witness per user
    public function getWitnessList($empID = ''){
        $data = '';
        if($empID){
            $data = DB::select('select witness.*, emp.empID, emp.avatar,emp.mname, emp.fname, emp.lname, emp.email,
            pos.posname, branch.branchname, dept.deptname, comp.compname
            from supplementary_witness witness
            inner join employee emp on witness.approverID_ = emp.empID
            inner join positiontbl pos on emp.posID_ = pos.posID
            inner join department dept on emp.deptID_ = dept.deptID
            inner join branchtbl branch on emp.branchID_ = branch.branchID
            left join companytbl comp on emp.compID_ = comp.compID
            where
            empID_ = :empid',[
                    $empID
                ]);
        }else{
            $data = DB::select('select witness.*, emp.empID, emp.avatar,emp.mname, emp.fname, emp.lname, emp.email,
            pos.posname, branch.branchname, dept.deptname, comp.compname
            from supplementary_witness witness
            inner join employee emp on witness.approverID_ = emp.empID
            inner join positiontbl pos on emp.posID_ = pos.posID
            inner join department dept on emp.deptID_ = dept.deptID
            inner join branchtbl branch on emp.branchID_ = branch.branchID
            left join companytbl comp on emp.compID_ = comp.compID
            where
            empID_ = :empid',
            [UserSession::getSessionID()]);
        }
      return $data;
    }

    public function addWitness(){
        DB::table('supplementary_witness')->insertOrIgnore(request()->all());
        return http_response_code(200);
    }

    public function delWitness(){
        DB::table('supplementary_witness')->where('approverID_', request('approverID_'))->delete();
        return http_response_code(200);
    }
}
