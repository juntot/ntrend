<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DirectoryController extends Controller
{
    //
    public function getDirectory(){

        $data = DB::select('select emp.empID, concat(emp.fname,"  ",emp.lname) as fullname, emp.email, emp.mobile, emp.avatar as img, emp.dhired,
                            dept.deptname, pos.posname,
                            branch.branchname
                                from employee emp
                            inner join department dept
                                on emp.deptID_ = dept.deptID
                            inner join positiontbl pos
                                on emp.posID_ = pos.posID
                            inner join branchtbl branch
                                on emp.branchID_ = branch.branchID
                            where emp.status = 1
                            ');
        return $data;
    }
}
