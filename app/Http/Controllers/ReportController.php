<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;

class ReportController extends Controller
{
    // check if reports is enabled
    public function hasReport(){
        $data = DB::select('select * from eform_reportbyuser where empID_ = :empid',  //['00001']);
        [ UserSession::getSessionID() ]);

        $forms = [];
        if(count($data)>0):
            foreach ($data[0] as $key=>$value) {
                if($value != 0 && $key != 'empID_'):
                    $navname = str_replace('0', ' ', (str_replace('_' ,'-' , (str_replace('8','&', $key)) )) );
                    $forms[] = ['formtitle' => $navname];
                endif;
            }
            return $forms;
        endif;
    }


    // GET REPORT TYPES
    public function getReportType(){
        // $data = DB::select('select formtitle from eforms where status = 1 order by 1');
        // // $forms = [];
        // // if(count($data)>0):
        // //     foreach ($data[0] as $key=>$value) {
        // //         if($value != 0 && $key != 'empID_'):
        // //             $navname = str_replace('0', ' ', (str_replace('_' ,'-' , (str_replace('8','&', $key)) )) );
        // //             $forms[] = ['navname' => $navname];
        // //         endif;
        // //     }
        // //     return $forms;
        // // endif;
        // return $data;

        $data = DB::select('select * from eform_reportbyuser where empID_ = :empid',  //['00001']);
        [ UserSession::getSessionID() ]);
        $forms = [];
        if(count($data)>0):
            foreach ($data[0] as $key=>$value) {
                if($value != 0 && $key != 'empID_'):
                    $navname = str_replace('0', ' ', (str_replace('_' ,'-' , (str_replace('8','&', $key)) )) );
                    $forms[] = ['formtitle' => $navname];
                endif;
            }
            return $forms;
        endif;

    }

    // MANAGE Type of Reports per USER
    public function manageReport(){
        $data = DB::select('select form.empID_, emp.fname, emp.lname, form.* from eform_reportbyuser form inner join employee emp on form.empID_ = emp.empID');
        return $data;
    }

    // update Type of Reports per user
    public function updateManageReport($empid = null ,$formname = null ,$status = null){
        DB::table('eform_reportbyuser')
            ->where('empID_', $empid)
            ->update([$formname => $status]);
    }


    public function getReport($datefrom = '', $dateto = '', $reporttype = '', $branch = '', $status = '', $company = ''){
        // $string="1,2,3,4,5";
        $array=array_map('intval', explode(',', $status));
        $status = implode(",", $array);

        $array2=array_map('intval', explode(',', $branch));
        $branch = implode(",", $array2);

        $array3=array_map('intval', explode(',', $company));
        $company = implode(",", $array3);
        // return $Arrstatus;
        // $query=mysqli_query($conn, "SELECT name FROM users WHERE id IN ('".$array."')");

        $typeOfReports = [
                'formsupplementary',     'formcalllingcard',        'formclearance',
                'formloan',              'formfinancialadvantage',  'formincidentreport',
                'formlaptoprequest',     'formleave',               'formmiss',
                'formprs',               'formprf',                 'formcanvas',
                'formsalarydiscrepancy', 'formaccreditation',       'formtravel',
                'formundertime',         'formurgentcheck',         'formoffset',
                'formworkrequest'
            ];
        $reportsparams = [
            'supplementary',
            'callingcardrequest',
            'clearanceform',
            'companyloan',
            'financialadvantage',
            'incidentreport',
            'laptopform',
            'leaveform',
            'miis',
            'prs',
            'prf',
            'canvas',
            'salarydiscrepancy',
            'supplieraccreditation',
            'travelform',
            'undertimerequest',
            'urgentcheck',
            'offset',
            'workrequest',
            'overtimerequest'
        ];

        switch($reporttype):
            // Supplementary
            // old use datefiled as paramter
            case 'supplementary':
                    $data  = DB::select('select DISTINCT esup.supID, esup.empID_, esup.datefiled, esup.brand, esup.witnesses, esup.remarks, esup.approveddate, esup.remarks, esup.status,
                    DATE_FORMAT(esup.datefiled, "%m/%d/%Y") as datefiled,
                    CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname, comp.compname,
                    (
                        select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                        where subemp.empID = esup.approvedby
                    ) as approvedby
                    from formsupdetails detail
                    left JOIN formsupplementary esup
                        on detail.supID_ = esup.supID
                    right join employee emp
                        on emp.empID = esup.empID_
                    inner join positiontbl pos
                        on pos.posID = emp.posID_
                    inner join department dept
                        on dept.deptID = emp.deptID_
                    inner join branchtbl branch
                        on branch.branchID = emp.branchID_
                    inner join companytbl comp
                        on comp.compID = emp.compID_
                    WHERE
                        (detail.supdate BETWEEN :dateFrom AND :dateTo)
                    AND
                        emp.branchID_ IN('.$branch.')
                    AND
                        emp.compID_ IN('.$company.')
                    AND
                        esup.status IN('.$status.')
                    AND esup.status IN('.$status.')
                    AND esup.recstat != 1',
                    [
                        $datefrom, $dateto,
                    ]);
                if(count($data) > 0)
                {

                    foreach($data as $keys => $value)
                    {
                            $supID = $data[$keys]->supID;
                            // never add supdetailsID to avoid error in update
                            $supdetails = DB::select('select supID_, supdate, timein, timeout, timein2, timeout2, reason
                                            from formsupdetails
                                            WHERE
                                                (supdate BETWEEN :dateFrom AND :dateTo)
                                            AND
                                            supID_ = :supid',
                                            [$datefrom, $dateto, $supID]);
                            $data[$keys]->entries = $supdetails;
                    }

                }

                return $data;
                break;
           /*
            case 'supplementary':
                $data = DB::select('select esup.*,
                            DATE_FORMAT(esup.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname, comp.compname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = esup.approvedby
                            ) as approvedby
                            from formsupplementary esup right join employee emp
                                on emp.empID = esup.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join companytbl comp
                                on comp.compID = emp.compID_
                            WHERE
                                (esup.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                emp.compID_ IN('.$company.')
                            AND
                                esup.status IN('.$status.')
                            AND
                                esup.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);

                if(count($data) > 0)
                {

                    foreach($data as $keys => $value)
                    {
                            $supID = $data[$keys]->supID;
                            // never add supdetailsID to avoid error in update
                            $supdetails = DB::select('select supID_, supdate, timein, timeout, timein2, timeout2, reason from formsupdetails where supID_ = :supid', [$supID]);
                            $data[$keys]->entries = $supdetails;
                    }

                }

                return $data;

                break;
                */


            // Calling Card
            case 'callingcardrequest':
                $data = DB::select('select eccard.*,
                            DATE_FORMAT(eccard.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, emp.mobile, emp.lname, emp.fname, emp.email,
                            branch.branchname, pos.posname, dept.deptname,
                                        (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eccard.approvedby
                            ) as approvedby
                            from formcallingcard eccard right join employee emp
                                on emp.empID = eccard.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            WHERE
                                (eccard.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                eccard.status IN('.$status.')
                            AND
                                eccard.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;
            // Clearance
            case 'clearanceform':

                break;
            // Loan
            case 'companyloan':
                $data = DB::select('select eformloan.*,
                            DATE_FORMAT(eformloan.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname,
                            emp.dhired, emp.mobile,
                            branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eformloan.approvedby
                            ) as approvedby
                            from formloan eformloan right join employee emp
                                on emp.empID = eformloan.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            WHERE
                                (eformloan.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                eformloan.status IN('.$status.')
                            AND
                                eformloan.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;
            // FA
            case 'financialadvance':
                $data = DB::select('select efadvantage.*,
                            DATE_FORMAT(efadvantage.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname , dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = efadvantage.approvedby
                            ) as approvedby
                            from formfinancialadvantage efadvantage right join employee emp
                                on emp.empID = efadvantage.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            WHERE
                                (efadvantage.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                efadvantage.status IN('.$status.')
                            AND
                                efadvantage.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()
                            ]);
                return $data;
                break;
            // Incident Report
            case 'incidentreport':
                $data = DB::select('select eIReport.*,
                            DATE_FORMAT(eIReport.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eIReport.approvedby
                            ) as approvedby
                            from formincidentreport eIReport right join employee emp
                                on emp.empID = eIReport.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            WHERE
                                (eIReport.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                eIReport.status IN('.$status.')
                            AND
                                eIReport.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);

                return $data;
                break;
            // LAPTOP
            case 'laptopform':
                // bug
                // $data = DB::select('select CONCAT(emp.fname," ",emp.lname) as approvers ,
                            // (
                            //     select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                            //     where subemp.empID = eleave.approvedby
                            // ) as approvedby
                //             from eformapproverbyemp eform right join employee emp
                //                 on eform.approverID_ = emp.empID
                //             WHERE
                //                 (eIReport.datefiled BETWEEN :dateFrom AND :dateTo)
                //             AND
                //                 emp.branchID_ IN('.$branch.')
                //             AND
                //                 eIReport.status IN('.$status.')
            //             AND
                //                 eIReport.recstat != 1',
                //             [
                //                 $datefrom, $dateto,
                 //               $branch,
                                // $status
                //                 // UserSession::getSessionID()

                //             ]);
                // return [];
                // return $data;
                break;

            // LEAVE
            case 'leaveform':
                // $data = DB::select('select eleave.*, CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                //             (
                //                 select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                //                 where subemp.empID = eleave.approvedby
                //             ) as approvedby
                //             from formleave eleave right join employee emp
                //                 on emp.empID = eleave.empID_
                //             inner join positiontbl pos
                //                 on pos.posID = emp.posID_
                //             inner join department dept
                //                 on dept.deptID = emp.deptID_
                //             inner join branchtbl branch
                //                 on branch.branchID = emp.branchID_
                //             WHERE
                //                 (eleave.datestart BETWEEN :dateFrom AND :dateTo)
                //             AND
                //                 emp.branchID_ IN('.$branch.')
                //             AND
                //                 eleave.status IN('.$status.')
                //             AND
                //                 eleave.recstat != 1',
                //             [
                //                 $datefrom, $dateto,
                //                $branch,
                                // $status
                //             ]);

                $data = DB::select('select eleave.*,
                DATE_FORMAT(eleave.datefiled, "%m/%d/%Y") as datefiled,
                DATE_FORMAT(eleave.datestart, "%m/%d/%Y") as datestart,
                DATE_FORMAT(eleave.dateend, "%m/%d/%Y") as dateend,
                CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname, comp.compname,
                (
                    select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                    where subemp.empID = eleave.approvedby
                ) as approvedby
                from formleave eleave right join employee emp
                    on emp.empID = eleave.empID_
                inner join positiontbl pos
                    on pos.posID = emp.posID_
                inner join department dept
                    on dept.deptID = emp.deptID_
                inner join branchtbl branch
                    on branch.branchID = emp.branchID_
                inner join companytbl comp
                    on comp.compID = emp.compID_

                WHERE
                    (eleave.datestart BETWEEN :dateFrom AND :dateTo)
                AND
                    emp.branchID_ IN('.$branch.')
                AND
                    emp.compID_ IN('.$company.')
                AND

                    eleave.status IN('.$status.')
                AND
                    eleave.recstat != 1',
                [
                    $datefrom, $dateto,
                    // $branch,
                    // $status
                ]);
                return $data;
                break;
            // MIIS
            case 'miis':
                $data = DB::select('select emiis.*,
                            DATE_FORMAT(emiis.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = emiis.approvedby
                            ) as approvedby
                            from formMIIS emiis right join employee emp
                                on emp.empID = emiis.empID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            WHERE
                                (emiis.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                emiis.status IN('.$status.')
                            AND
                                emiis.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;
            // PRS
            case 'prs':

                break;
            // PRF
            case 'prf':
                $data = DB::select('select eprf.*,
                            DATE_FORMAT(eprf.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eprf.approvedby
                            ) as approvedby
                            from formprf eprf right join employee emp
                                on emp.empID = eprf.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            WHERE
                                (eprf.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                eprf.status IN('.$status.')
                            AND
                                eprf.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);

                // check if data has vale
                if(count($data) > 0)
                {

                    foreach($data as $keys => $value)
                    {
                        $prfID = $data[$keys]->prfID;
                        // never add supdetailsID to avoid error in update
                        $supdetails = DB::select('select prfID_, itemdesc, uom, qty, allocatedbudget, reason, accountableto from formPRFdetails where prfID_ = :prfID', [$prfID]);
                        $data[$keys]->entries = $supdetails;
                    }

                }
                return $data;
                break;
            // Canvas
            case 'canvas':
                $data = DB::select('select ecanvas.*,
                            DATE_FORMAT(ecanvas.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = ecanvas.approvedby
                            ) as approvedby
                            from formcanvas ecanvas right join employee emp
                                on emp.empID = ecanvas.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            WHERE
                            (ecanvas.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                ecanvas.status IN('.$status.')
                            AND
                                ecanvas.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                // check if data has vale
                if(count($data) > 0)
                {

                    foreach($data as $keys => $value)
                    {
                            $canvasID = $data[$keys]->canvasID;
                            // never add supdetailsID to avoid error in update
                            $canvasdetails = DB::select('select canvasID_, itembrand, itemdesc, itemcost, qty, total, itemremarks from formcanvasdetails where canvasID_ = :canvasID', [$canvasID]);
                            $data[$keys]->entries = $canvasdetails;
                    }

                }
                return $data;
                break;
            // Saldisc
            case 'salarydiscrepancy':
                $data = DB::select('select esaldisc.*,
                        DATE_FORMAT(esaldisc.datefiled, "%m/%d/%Y") as datefiled,
                        DATE_FORMAT(esaldisc.discrepancydate, "%m/%d/%Y") as discrepancydate,
                        CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = esaldisc.approvedby
                            ) as approvedby
                        from formsalarydiscrepancy esaldisc right join employee emp
                            on emp.empID = esaldisc.empID_
                        inner join positiontbl pos
                            on pos.posID = emp.posID_
                        inner join department dept
                            on dept.deptID = emp.deptID_
                        inner join branchtbl branch
                            on branch.branchID = emp.branchID_
                        WHERE
                            (esaldisc.discrepancydate BETWEEN :dateFrom AND :dateTo)
                        AND
                            emp.branchID_ IN('.$branch.')
                        AND
                            esaldisc.status IN('.$status.')
                        AND
                            esaldisc.recstat != 1',
                        [
                            $datefrom, $dateto,
                            // $branch,
                            // $status
                            // UserSession::getSessionID()
                        ]);

                return $data;
                break;
            // accreditation
            case 'supplieraccreditation':
                $data = DB::select('select eaccredit.* ,
                    DATE_FORMAT(eaccredit.datefiled, "%m/%d/%Y") as datefiled,
                    CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eaccredit.approvedby
                            ) as approvedby
                    from formaccreditation eaccredit right join employee emp
                        on emp.empID = eaccredit.empID_
                    inner join positiontbl pos
                        on pos.posID = emp.posID_
                    inner join branchtbl branch
                        on branch.branchID = emp.branchID_
                    inner join department dept
                        on dept.deptID = emp.deptID_
                    WHERE
                        (eaccredit.datefiled BETWEEN :dateFrom AND :dateTo)
                    AND
                        emp.branchID_ IN('.$branch.')
                    AND
                        eaccredit.status IN('.$status.')
                    AND
                        eaccredit.recstat != 1',
                    [
                        $datefrom, $dateto,
                        // $branch,
                        // $status
                        // UserSession::getSessionID()

                    ]);
                return $data;
                break;
            // travel
            case 'travelform':
                $data = DB::select('select etravel.*,
                    DATE_FORMAT(etravel.datefiled, "%m/%d/%Y") as datefiled,
                    DATE_FORMAT(etravel.departuredate, "%m/%d/%Y") as departuredate,
                    CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname,
                    dept.deptname, pos.posname, emp.mobile,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = etravel.approvedby
                            ) as approvedby
                            from formtravel etravel right join employee emp
                                on emp.empID = etravel.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            WHERE
                                (etravel.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                etravel.status IN('.$status.')
                            AND
                                etravel.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;
            // undertime
            case 'undertimerequest':
                $data = DB::select('select eundertime.*,
                            DATE_FORMAT(eundertime.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(eundertime.date_undertime, "%m/%d/%Y") as date_undertime,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname, comp.compname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eundertime.approvedby
                            ) as approvedby
                            from formundertime eundertime right join employee emp
                                on emp.empID = eundertime.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join companytbl comp
                                on comp.compID = emp.compID_
                            WHERE
                                (eundertime.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                emp.compID_ IN('.$company.')
                            AND
                                eundertime.status IN('.$status.')
                            AND
                                eundertime.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;
            // urgent check
            case 'urgentcheck':
                $data = DB::select('select eurgent.*,
                            DATE_FORMAT(eurgent.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eurgent.approvedby
                            ) as approvedby
                            from formurgentcheck eurgent right join employee emp
                                on emp.empID = eurgent.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            WHERE
                                (eurgent.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                eurgent.status IN('.$status.')
                            AND
                                eurgent.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;

            // offset
            case 'offset':
                $data = DB::select('select eoffset.*,
                            DATE_FORMAT(eoffset.datefiled, "%m/%d/%Y") as datefiled,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = eoffset.approvedby
                            ) as approvedby
                            from formoffset eoffset right join employee emp
                                on emp.empID = eoffset.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            WHERE
                                (eoffset.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                eoffset.status IN('.$status.')
                            AND
                                eoffset.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);

                // check if data has vale
                if(count($data) > 0)
                {

                    foreach($data as $keys => $value)
                    {
                            $offsetID = $data[$keys]->offsetID;
                            // never add supdetailsID to avoid error in update
                            $supdetails = DB::select('select offsetID_, offsetdate, timein, timeout, reason from formoffsetdetails where offsetID_ = :offsetID', [$offsetID]);
                            $data[$keys]->entries = $supdetails;
                    }

                }

                return $data;
                break;
            // work request
            case 'workrequest':
                $data = DB::select('select ework.*,
                            DATE_FORMAT(ework.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(ework.dateneed, "%m/%d/%Y") as dateneed,
                            CONCAT(emp.fname," ",emp.lname) as fullname, pos.posname, dept.deptname, emp.mobile,
                            branch.branchname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = ework.approvedby
                            ) as approvedby_,
                            (
                                select CONCAT(emp.fname," ",emp.lname) from employee emp
                                where ework.exec_by = emp.empID
                            ) as executedby_
                            from formworkrequest ework right join employee emp
                                on emp.empID = ework.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on emp.branchID_ = branch.branchID
                            WHERE
                                (ework.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                ework.status IN('.$status.')
                            AND
                                ework.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                            ]);

                return $data;
                break;
            case 'overtimerequest':
                $data = DB::select('select overtime.*,
                            DATE_FORMAT(overtime.datefiled, "%m/%d/%Y") as datefiled,
                            DATE_FORMAT(overtime.date_overtime, "%m/%d/%Y") as date_overtime,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname, comp.compname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = overtime.approvedby
                            ) as approvedby
                            from formovertime overtime right join employee emp
                                on emp.empID = overtime.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join companytbl comp
                                on comp.compID = emp.compID_
                            WHERE
                                (overtime.datefiled BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                emp.compID_ IN('.$company.')
                            AND
                                overtime.status IN('.$status.')
                            AND
                                overtime.recstat != 1',
                            [
                                $datefrom, $dateto,
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;

            // override form
            case 'overrideform':
                $data = DB::select('select overtime.*,
                            CONCAT(emp.fname," ",emp.lname) as fullname, branch.branchname, pos.posname, dept.deptname, comp.compname,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = overtime.approvedby
                            ) as approvedby,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = 
                                IF(LOCATE(",",
                                    overtime.endorsedby_),
                                    SUBSTRING_INDEX(overtime.endorsedby_, ",", 1),
                                    overtime.endorsedby_
                                )
                            ) as endorsedby_,
                            (
                                select CONCAT(subemp.fname," ", subemp.lname) from employee subemp
                                where subemp.empID = 
                                IF(LOCATE(",",
                                    overtime.endorsedby_),
                                    SUBSTR(overtime.endorsedby_, ((POSITION("," in overtime.endorsedby_)-1) * -1)),
                                    ""
                                )
                            ) as endorsedby2_,
                            (
                                CONCAT(
                                    IF(overtime.reason IS NOT NULL OR overtime.reason <> "" OR overtime.current_stat IS NOT NULL, "ON HOLD," ,""),
                                    IF(overtime.comment IS NOT NULL OR overtime.comment <> "" OR overtime.overdue_tbl <> "[]", "OVERDUE," ,""),
                                    IF( overtime.excess IS NOT NULL or overtime.excess <> ""
                                        OR
                                        overtime.percent IS NOT NULL or overtime.percent <> ""
                                        OR
                                        overtime.last_cl IS NOT NULL or overtime.last_cl <> ""
                                        OR 
                                        overtime.commit_cl IS NOT NULL or overtime.commit_cl <> ""
                                        , "OVER/NO CREDIT LIMIT,", ""
                                    ),
                                    IF(
                                        overtime.check_type IS NOT NULL
                                        OR
                                        overtime.check_tbl <> "[]"
                                        OR 
                                        overtime.paying_habit IS NOT NULL OR overtime.paying_habit <> "",
                                        "UNSETTLED CHECK MOVEMENT", ""
                                    )
                                )
                            ) as reasons
                            from formoverride overtime right join employee emp
                                on emp.empID = overtime.empID_
                            inner join positiontbl pos
                                on pos.posID = emp.posID_
                            inner join department dept
                                on dept.deptID = emp.deptID_
                            inner join branchtbl branch
                                on branch.branchID = emp.branchID_
                            inner join companytbl comp
                                on comp.compID = emp.compID_
                            WHERE
                                (overtime.dateoverride BETWEEN :dateFrom AND :dateTo)
                            AND
                                emp.branchID_ IN('.$branch.')
                            AND
                                emp.compID_ IN('.$company.')
                            AND
                                overtime.status IN('.$status.')
                            AND
                                overtime.recstat != 1',
                            [
                                $datefrom.' 00:00:00', $dateto.' 23:59:59',
                                // $branch,
                                // $status
                                // UserSession::getSessionID()

                            ]);
                return $data;
                break;
        endswitch;
        // return explode(",",$branch);
        return [];

    }
}
