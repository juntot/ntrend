<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReportChartController extends Controller
{
    // Incident Report Chart
    public function getIRChart($datefrom = '', $dateto = '', $reporttype = '', $branch = '', $status = '', $company = "")
    {   
        
        // 1 approve main approver
        // 1 approve endorse 1 endorseby has value
        // 2 approve endorse 2 endrseby 2 has value
        // 3 close
        // 4 reject
        $sql = '';
        $pie = '';
        $bar = '';
        if (str_contains($status, '9')) { 
            $status = $status.","."1";
            
        }
        if (str_contains($status, '8')) { 
            $status = $status.","."1".","."2";
            
        }

        $sql = 'select
        SUM(CASE when eIReport.status = 0 AND eIReport.approvedby IS NULL then 1 else 0 end) as pending,
        SUM(CASE when eIReport.status = 1 AND eIReport.endorse2 IS NULL then 1 else 0 end) as approved,
        SUM(CASE when eIReport.status = 1 AND eIReport.endorse1 IS NOT NULL then 1 else 0 end) as `1st endorsement`,
        SUM(CASE when eIReport.status = 2 then 1 else 0 end) as `2nd endorsement`,
        SUM(CASE when eIReport.status = 2 OR eIReport.status = 1 AND eIReport.endorse1 IS NOT NULL then 1 else 0 end) as `further investigation`,
        SUM(CASE when eIReport.status = 3 then 1 else 0 end) as closed,
        SUM(CASE when eIReport.status = 4 then 1 else 0 end) as rejected
        from formincidentreport eIReport right join employee emp
            on emp.empID = eIReport.empID_
        inner join department dept
            on dept.deptID = emp.deptID_
        inner join branchtbl branch
            on branch.branchID = emp.branchID_
        inner join companytbl comp
            on comp.compID = emp.compID_
        WHERE
            (eIReport.datefiled BETWEEN :dateFrom AND :dateTo)
        AND
            emp.branchID_ IN('.$branch.')
        AND
            emp.compID_ IN('.$company.')
        AND
            eIReport.status IN('.$status.')
        AND
            eIReport.recstat != 1
        ';
        
        $pie = DB::select($sql,[$datefrom, $dateto]);

        $sql = 'select LOWER(branch.branchname) as branchname,
        SUM(CASE when eIReport.status = 0 AND eIReport.approvedby IS NULL then 1 else 0 end) as pending,
        SUM(CASE when eIReport.status = 1 AND eIReport.endorse2 IS NULL then 1 else 0 end) as approved,
        SUM(CASE when eIReport.status = 1 AND eIReport.endorse1 IS NOT NULL then 1 else 0 end) as `1st endorsement`,
        SUM(CASE when eIReport.status = 2 then 1 else 0 end) as `2nd endorsement`,
        SUM(CASE when eIReport.status = 2 OR eIReport.status = 1 AND eIReport.endorse1 IS NOT NULL then 1 else 0 end) as `further investigation`,
        SUM(CASE when eIReport.status = 3 then 1 else 0 end) as closed,
        SUM(CASE when eIReport.status = 4 then 1 else 0 end) as rejected
        from formincidentreport eIReport right join employee emp
            on emp.empID = eIReport.empID_
        inner join department dept
            on dept.deptID = emp.deptID_
        inner join branchtbl branch
            on branch.branchID = emp.branchID_
        inner join companytbl comp
            on comp.compID = emp.compID_
        WHERE
            (eIReport.datefiled BETWEEN :dateFrom AND :dateTo)
        AND
            emp.branchID_ IN('.$branch.')
        AND
            emp.compID_ IN('.$company.')
        AND
            eIReport.status IN('.$status.')
        AND
            eIReport.recstat != 1
        GROUP BY branch.branchname
        ';

        $bar = DB::select($sql,[$datefrom, $dateto]);


        if($pie)
        $pie = $pie[0];

        return ['pie'=> $pie , 'bar' => $bar];
        // return request()->all();
    }
}
