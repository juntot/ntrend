<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;
use Carbon\Carbon;

class PaySlipController extends Controller
{
    // ADD
    public function addPaySlip(){


        // DB::table('payslips')->insertOrIgnore(request()->only([]));
        // // LOGS
        // $obj = json_encode(request()->all());
        // DB::table('payslip_log')->insert(['jsonOBJ'=> $obj, 'uploadedby_' => UserSession::getSessionID() ]); //session()->get('auth.empID')]);
        // return request()->all();
        // if(request()->has('images'))
        // {
        //     foreach(request()->file('images') as $image)
        //     {
        //         $image->store('public/payslip');
        //     }
        // }

        // if(request()->has('clear')){
        //     //    clear all policy details which match the policy ID
        //     DB::table('policy_details_tbl')->where(['policy_id' => request('policy_id')])->delete();
        //     $path = 'public/policy/'.request('policy_id');
        //     if (\Storage::disk('local')->exists($path)) \Storage::deleteDirectory($path);
        // }


       if(request()->has('clear')){
            //    clear all policy details which match the policy ID
            DB::table('payslips')->where(['cutoffenddate' => request('cutoffenddate')])->delete();
            $path = 'public/payslip/'.request('cutoffenddate');
            if (\Storage::disk('local')->exists($path)) \Storage::deleteDirectory($path);
       }

    // return;
       $arr_file_path = UserSession::fileAttachments('payslip/'.request('cutoffenddate'));

       $bulk_data = [];
        if(count($arr_file_path) > 0 ){
            foreach ($arr_file_path as $key => $value) {
                $bulk_data[]=['empID_'=>$value['empID_'], 'pdf_loc' => $value['pdf_loc'], 'cutoffenddate'=> request('cutoffenddate')];

                DB::table('payslips')->updateOrInsert(
                    ['empID_'=>$value['empID_'], 'cutoffenddate'=> request('cutoffenddate')],
                    ['pdf_loc' => $value['pdf_loc']]
                );
            }
        }
        // DB::table('payslips')->insertOrIgnore($bulk_data);

        return $bulk_data;
    }
    public function deletePayslip(){
        DB::table('payslips')->where(['cutoffenddate' => request('cutoffenddate')])->delete();

        $path = 'public/payslip/'.request('cutoffenddate');
        if (\Storage::disk('local')->exists($path))
        \Storage::deleteDirectory($path);

        return http_response_code(200);
    }
    // GET ALL PAYSLIP
    public function getAllPaySlip($date=''){
        $data = [];
        if($date){
            // $data = DB::select('select emp.fname, emp.empID, emp.lname, dept.deptname, pos.posname, pslip.*
            //         from payslips pslip
            //             inner join employee emp on pslip.empID_ = emp.empID
            //             inner join department dept on dept.deptID = emp.deptID_
            //             inner join positiontbl pos on pos.posID = emp.posID_
            //         where cutoffenddate = :date
            // ',[
            //     $date
            // ]);
            $data = DB::select('select emp.fname, emp.empID, emp.lname, dept.deptname, pos.posname,
                    pslip.empID_, pslip.cutoffenddate
                    from payslips pslip
                        inner join employee emp on pslip.empID_ = emp.empID
                        inner join department dept on dept.deptID = emp.deptID_
                        inner join positiontbl pos on pos.posID = emp.posID_
                    where cutoffenddate = :date
            ',[
                $date
            ]);
        }else{
            $latest_date = DB::select('select cutoffenddate from payslips order by 1 desc limit 1');
            if($latest_date){
                $latest_date = $latest_date[0]->cutoffenddate;
                $data = DB::select('select emp.fname, emp.empID, emp.lname, dept.deptname, pos.posname,
                pslip.empID_, pslip.cutoffenddate
                    from payslips pslip
                        inner join employee emp on pslip.empID_ = emp.empID
                        inner join department dept on dept.deptID = emp.deptID_
                        inner join positiontbl pos on pos.posID = emp.posID_
                    where cutoffenddate = :date
                ',[
                    $latest_date
                ]);
            }
        }

        return $data;
    }

    // GET EMP PAYSLIP
    public function getPaySlipByEmployee(){

        $data = DB::select('select *
                from payslips pslip
                where empID_ = :sessionID
                order by cutoffenddate desc', [ UserSession::getSessionID() ]);
        return $data;
    }

    // GET CUTOFF DATES PAYSLIPS
    public function getCutoffDates(){
        $date = DB::select('select distinct cutoffenddate from payslips order by 1 desc');
        return $date;
        $result = [];
        foreach ($date as $key => $value) {
            $carb = $value->cutoffenddate;

            $result[] =Carbon::parse($carb, 'Europe/London')->toATOMString();
        }
        return ['dates'=>$result];
    }


}
