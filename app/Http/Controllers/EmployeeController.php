<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use App\Services\UserSession;
use App\Services\CurlService;

class EmployeeController extends Controller
{
    //
    public function defaultForms($deptId = ''){
        $setdefaultforms = [];
        if($deptId == '')
        return $setdefaultforms;


        $deptname = DB::select('select deptname from department where deptid = :deptid', [$deptId]);
        if(count($deptname)>0)
        {

            $deptname = $deptname[0]->deptname;
            $deptname = str_replace(' ','0',$deptname);
            $deptname = str_replace('-','_',$deptname);
            $deptname = str_replace('&','8',$deptname);

            // ordinary users access
            $eforms = DB::select('select navname from eforms where '.$deptname.' = 1');

            foreach ($eforms as $key => $value) {
                $formnav = $value->navname;
                $formnav = str_replace(' ','0', $formnav);
                $formnav = str_replace('-','_', $formnav);
                $formnav = str_replace('&','8', $formnav);
                $setdefaultforms[$formnav] = 1;
            }

            // approvers by set department



        }
        return $setdefaultforms;

    }


    public function addEmp(){
        // return request()->all();
    	$setdefaultforms = [];
        $path = $this->newUserAvatar();

        request()->merge(['avatar' => $path]);
    	$data = DB::table('employee')->insertOrIgnore(request()->except(['isDisable', 'image']));

        // ordinary users access
        $deptname = DB::select('select deptname from department where deptid = :deptid', [request('deptID_')]);
        if(count($deptname)>0)
        {

            $deptname = $deptname[0]->deptname;
            $deptname = str_replace(' ','0',$deptname);
            $deptname = str_replace('-','_',$deptname);
            $deptname = str_replace('&','8',$deptname);

            $eforms = DB::select('select navname from eforms where '.$deptname.' = 1');

            foreach ($eforms as $key => $value) {
                $formnav = $value->navname;
                $formnav = str_replace(' ','0', $formnav);
                $formnav = str_replace('-','_', $formnav);
                $formnav = str_replace('&','8', $formnav);
                $setdefaultforms[$formnav] = 1;
            }

            // approvers by set department

        }

        // USERNAME /IDNO
        $username = strtolower(request('fname').''.request('lname'));
        $username = preg_replace('/\s+/', '', $username);
        DB::table('users')->insertOrIgnore(['empID'=>request('empID'), 'username' => $username, 'email'=>request('email'), 'password'=> Hash::make('1234')]);

        DB::table('eformuser')->insertOrIgnore(['empID_'=>request('empID')]);
        // DB::table('eformuser')->where('empID_', request('empID'))->update($setdefaultforms);
        count($setdefaultforms) > 0 ? DB::table('eformuser')->where('empID_', request('empID'))->update($setdefaultforms):'';
        DB::table('eformapprover')->insertOrIgnore(['empID_'=>request('empID')]);

        DB::table('eform_reportbyuser')->insertOrIgnore(['empID_'=>request('empID')]);



        $postfields = request()->except(['isDisable', 'image']);

        try {
            CurlService::httpCurl(
                'https://ams.northtrend.com/aws/addemp',
                'POST',
                $postfields
            );
        } catch (\Throwable $th) {
            //throw $th;
            // return $result;
        }

        return request()->except(['isDisable']);

    }

    public function uploadXls(){

        // DB::table('employee')->insert(request('xlsData'));
        try{

            $setdefaultforms = '';
            $tbl_Users = [];
            $tbl_fkUser = [];
            $xlsData = request('xlsData');


            foreach ($xlsData as $key => $data) {

                DB::table('employee')->insertOrIgnore($data);

                $setdefaultforms = $this->defaultForms($data['deptID_']);
                $tbl_fkUser[] = ['empID_' => $data['empID'].''];

                // user login
                $username = strtolower($data['fname'].''.$data['lname']);
                $username = preg_replace('/\s+/', '', $username);
                $tbl_Users[] = ['empID' => $data['empID'].'', 'username' => $username, 'email'=>$data['email'], 'password'=> Hash::make('1234')];

                // eform users table
                DB::table('eformuser')->insertOrIgnore(['empID_'=>$data['empID'].'']);
                count($setdefaultforms) > 0 ? DB::table('eformuser')->where('empID_', $data['empID'].'')->update($setdefaultforms):'';
            }




            // user LOGIN
            // return $tbl_Users;
            DB::table('users')->insertOrIgnore($tbl_Users);

            // approvers and reports
            DB::table('eformapprover')->insertOrIgnore($tbl_fkUser);
            DB::table('eform_reportbyuser')->insertOrIgnore($tbl_fkUser);
            return http_response_code(200);
        } catch (Exception $e) {
            return http_response_code(201);
        }
    }
    public function updateEmpAvatar(){
        $path = $this->newAvatar();
        // $path = $this->updateEmpAvatar();
        $empID = UserSession::getSessionID();
        DB::table('employee')->where('empID', $empID)->update(['avatar' => $path]);

        return ['avatar'=>$path];
    }

    public function updateEmp(){
        // return request()->all();
        if(request('image') != 'null'){
            $path = $this->newUserAvatar();
            request()->merge(['avatar' => $path]);
        }else{
            request()->request->remove('avatar');
        }

        // return request()->all();

        $deptid = DB::select('select deptID_ from employee where empID = :empid', ['empid'=>request('empID')]);
        DB::table('employee')->where('empID', request('empID'))->update(request()->except(['isDisable', 'empID', 'image']));

        // USER
        $username = strtolower(request('fname').''.request('lname'));
        $username = preg_replace('/\s+/', '', $username);
        DB::table('users')->where('empID', request('empID'))->update(['username' => $username, 'email' => request('email')]);

        if(count($deptid) > 0)
        {
            if($deptid[0]->deptID_ != request('deptID_'))
            {

                $deptname = DB::select('select deptname from department where deptid = :deptid', [request('deptID_')]);
                if(count($deptname)>0)
                {
                    $setdefaultforms = [];
                    $deptname = $deptname[0]->deptname;
                    $deptname = str_replace(' ','0',$deptname);
                    $deptname = str_replace('-','_',$deptname);
                    $deptname = str_replace('&','8',$deptname);

                    $eforms = DB::select('select navname from eforms where '.$deptname.' = 1');

                    foreach ($eforms as $key => $value) {
                        $formnav = $value->navname;
                        $formnav = str_replace(' ','0', $formnav);
                        $formnav = str_replace('-','_', $formnav);
                        $formnav = str_replace('&','8', $formnav);
                        $setdefaultforms[$formnav] = 1;
                    }
                    DB::table('eformuser')->where('empID_', request('empID'))->delete();
                    DB::table('eformuser')->insert(['empID_'=>request('empID')]);

                    count($setdefaultforms) > 0 ? DB::table('eformuser')->where('empID_', request('empID'))->update($setdefaultforms):'';

                }

            }
        }

        return request()->except(['isDisable']);
    }

    public function delEmp(){
        DB::table('employee')->where('empID', request('empID'))->update(['status'=>0]);
        DB::table('users')->where('empID', request('empID'))->update(['status'=>0]);
        return http_response_code(200);
    }
    
    public function activateEmp(){
        DB::table('employee')->where('empID', request('empID'))->update(['status'=>1]);
        DB::table('users')->where('empID', request('empID'))->update(['status'=>1]);
        return http_response_code(200);
    }

    public function delEmpPermanent(){
        $empID = request('empID')."";
        $payslips = DB::select('select pdf_loc from payslips where empID_ = :empid', ['empid'=>request('empID')]);
        if(count($payslips) > 0) {
            foreach ($payslips as $key => $value) {
                UserSession::delAttachment($value->pdf_loc);
            }
        }
        // DB::table('payslips')->where('empID_', request('empID'))->delete();
        // DB::table('employee')->where('empID', request('empID'))->delete();
        // DB::table('users')->where('empID', request('empID'))->delete();
        
        DB::select('CALL sp_DeletePermanentUser(?)', array($empID));
        // DB::select('CALL sp_DeletePermanentUser('.$empID.')');

        return http_response_code(200);
    }

    // store image to local disk
    public function newAvatar(){
        $attachment = null;

    	if(request('image') != 'null' ){
            // $attachment = request()->file('image')->store('public/attachment');
            // $extension = request()->file('image')->extension();


            $empID = UserSession::getSessionID();
            \Storage::delete('public/avatar/'.$empID);
	        $attachment = request()->file('image')->storeAs('public/avatar', $empID);
	     }
       return $attachment;
    }

    // SET AVATAR PER USER ADMIN FUNCTION
    public function newUserAvatar(){
        $attachment = null;

    	if(request('image') != 'null' ){
            // $attachment = request()->file('image')->store('public/attachment');
            // $extension = request()->file('image')->extension();


            $empID = request('empID');
            \Storage::delete('public/avatar/'.$empID);
	        $attachment = request()->file('image')->storeAs('public/avatar', $empID);
	     }
       return $attachment;
    }


    //  get employee information base from session
    public function getEmpInfo(){

        $empid = UserSession::getEmpKey();
        if(count($empid) <= 0)
        return;

         $data = DB::select('select emp.empID, emp.avatar, emp.mname, emp.fname, emp.lname, emp.gender,  emp.email,
                    emp.SSS, emp.TIN, emp.PhHealth, emp.HDMF, emp.mobile, emp.birthdate, emp.SL, emp.VL, emp.BL, emp.DL,
                    emp.emergency_contactperson, emp.emergency_contactnum, emp.employee_status,
                    DATE_FORMAT(emp.dhired, "%M %e, %Y") as dhired, emp.birthdate,
                    user.canPost, user.isAdmin, user.addDept, user.addPos, user.addBranch, user.addEmp, user.addPayslip, 
                    user.uploadDtr, user.viewDTRReport, user.viewReports, user.addDelivery, user.status,
                    emp.deptID_, emp.branchID_, pos.posID, emp.compID_, pos.posname, branch.branchname, dept.deptname, comp.compname
                    from employee emp
                    inner join users user on emp.empID = user.empID
                    inner join positiontbl pos on emp.posID_ = pos.posID
                    inner join department dept on emp.deptID_ = dept.deptID
                    inner join branchtbl branch on emp.branchID_ = branch.branchID
                    left join companytbl comp on emp.compID_ = comp.compID
                    where emp.empID = :id
        ', [$empid[0]->empID]);

        return $data;

    }

    // get ALL employees
    public function getEmployees()
    {
        $data = DB::select('select emp.empID, emp.avatar,emp.mname, emp.fname, emp.lname, emp.gender,  emp.email, emp.dhired, emp.birthdate,
                    user.canPost, user.isAdmin, user.addDept, user.addPos, user.addBranch, user.addEmp, user.addPayslip, 
                    user.uploadDtr, user.viewDTRReport, user.viewReports, user.addDelivery, user.status, 
                    emp.deptID_, emp.branchID_, emp.posID_, emp.compID_, emp.mobile,
                    emp.SSS, emp.TIN, emp.PhHealth, emp.HDMF, emp.SL, emp.VL, emp.BL, emp.DL,
                    emp.emergency_contactperson, emp.emergency_contactnum, emp.employee_status,
                    pos.posname, branch.branchname, dept.deptname, comp.compname
                    from employee emp
                    inner join users user on emp.empID = user.empID
                    inner join positiontbl pos on emp.posID_ = pos.posID
                    inner join department dept on emp.deptID_ = dept.deptID
                    inner join branchtbl branch on emp.branchID_ = branch.branchID
                    left join companytbl comp on emp.compID_ = comp.compID
                    where
                    emp.status = 1
                    ');

        return $data;
    }

    // get ALL RESIGNED employees
    public function getEmployeesInactive()
    {
        $data = DB::select('select emp.empID, emp.avatar,emp.mname, emp.fname, emp.lname, emp.gender,  emp.email, emp.dhired, emp.birthdate,
                    user.canPost, user.isAdmin, user.addDept, user.addPos, user.addBranch, user.addEmp, user.addPayslip, user.viewReports, user.status,
                    emp.deptID_, emp.branchID_, emp.posID_, emp.compID_, emp.mobile,
                    emp.SSS, emp.TIN, emp.PhHealth, emp.HDMF, emp.SL, emp.VL, emp.BL, emp.DL,
                    emp.emergency_contactperson, emp.emergency_contactnum, emp.employee_status,
                    pos.posname, branch.branchname, dept.deptname, comp.compname
                    from employee emp
                    inner join users user on emp.empID = user.empID
                    inner join positiontbl pos on emp.posID_ = pos.posID
                    inner join department dept on emp.deptID_ = dept.deptID
                    inner join branchtbl branch on emp.branchID_ = branch.branchID
                    left join companytbl comp on emp.compID_ = comp.compID
                    where
                    emp.status = 0
                    ');

        return $data;
    }

    // get all employee that belong to the same Department
    public function getEmpDepartment(){

        $deptid = UserSession::getEmpKey();

        $data = DB::select('select emp.empID as empID_, emp.fname, emp.lname from employee emp
                    inner join Department dept on emp.deptID_ = dept.deptID
                    where dept.deptID = :id
            ', [ $deptid[0]->deptID_ ]);

      return $data;
    }

    // get all employee that belong to the same Branch
    public function getEmpBranch(){

    }


    // public function getEmpKey()
    // {

    //     return DB::select('select empID, deptID_, branchID_, posID_ from employee where empID = :session'
    //         // ,[ session()->get('auth.empID') ]);
    //         ,[
    //             // '00001'
    //              session()->get('auth.empID')
    //             ]);
    // }


    public function getColumns(){
        return DB::select('select * from employeecolumns');
    }


}
