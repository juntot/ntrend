<?php

namespace App\Http\Controllers\Services;

class User{

    public function getEmpKey()
    {
        
        return DB::select('select empID, deptID_, branchID_, posID_ from employee where empID = :session'
            ,[ session()->get('auth.empID') ]);
            // ,[ '00001' ]);
    }
}
