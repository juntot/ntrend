<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GlobalController extends Controller
{
    // user details

    public function getGlobalEmployeeInfo(){
        $data = DB::select('
                    select emp.empID, emp.avatar,emp.mname, emp.fname, emp.lname, emp.gender,  emp.email, dhired, user.status,
                        emp.deptID_, emp.branchID_, emp.posID_, emp.mobile, emp.SSS, emp.TIN, emp.HDMF,
                        emp.SL, emp.VL, emp.BL, emp.DL,
                        pos.posname, branch.branchname, dept.deptname
                    from employee emp
                    inner join users user on emp.empID = user.empID
                    inner join positiontbl pos on emp.posID_ = pos.posID
                    inner join department dept on emp.deptID_ = dept.deptID
                    inner join branchtbl branch on emp.branchID_ = branch.branchID
                    where emp.status = 1
                    ');

        return $data;

    }

}
