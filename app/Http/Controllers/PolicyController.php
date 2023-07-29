<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;

class PolicyController extends Controller
{
    // Policy Nav
    function getPolicyNav(){
        $data = DB::select('SELECT pl.*, pd.policy_name, pd.detail_id FROM `policy_tbl` pl
                        inner join policy_details_tbl pd
                        on pl.id = pd.policy_id');
        $policy_nav = [];
        foreach ($data as $key=>$value) {
            $keyval = $value->catname;
            if(array_key_exists($keyval, $policy_nav)){
                $policy_nav[$keyval][]=$value;
            }
            else{
                $policy_nav[$keyval] = [$value];
            }

        }

        return $policy_nav;

    }
    // Add Policy
    function addPolicy(){
        if(request()->has('clear')){
            //    clear all policy details which match the policy ID
            DB::table('policy_details_tbl')->where(['policy_id' => request('policy_id')])->delete();
            $path = 'public/policy/'.request('policy_id');
            if (\Storage::disk('local')->exists($path)) \Storage::deleteDirectory($path);
        }


        $yyyy = request('policy_id');
        $arr_file_path = UserSession::fileAttachments_policy('policy/'.$yyyy);
       $bulk_data = [];
        if(count($arr_file_path) > 0 ){
            foreach ($arr_file_path as $key => $value) {
                $bulk_data[]=['policy_id' => request('policy_id'), 'policy_name'=>$value['empID_'], 'pdf_loc' => $value['pdf_loc']];

                DB::table('policy_details_tbl')->insert(['policy_id' => request('policy_id'), 'policy_name'=>$value['empID_'], 'pdf_loc' => $value['pdf_loc']]);
            }
        }
        return $bulk_data;
    }

    // get policy category ALL
    function getPolicyCategoryAll(){
        $data = DB::select('select * from policy_tbl where status = 1');
        return $data;
    }


    // add policy category
    function addpolicyCategory(){
        $data = DB::table('policy_tbl')->insertGetId(request()->all());
        return $data;
    }

    // delete policy category
    function delpolicyCategory($id= ''){
        $data = DB::table('policy_tbl')
                ->where('id', $id)
                ->delete();
                // ->update(['status' => 0]);

        return $data;
    }


    // DETAILS TABLE ===============================================================================
    // get ALL policy category details
    function getPolicyCategoryDetails($id = ''){
        $data = DB::select('select * from policy_details_tbl where policy_id =:id and status = 1',[
            'id'=>$id
        ]);
        return $data;
    }
    // get policy per details
    function policyPerDetail($id){
        $data = DB::select('
            select d.pdf_loc, p.catname
            from policy_details_tbl d
            inner join policy_tbl p
                on d.policy_id = p.id
            where d.detail_id =:id and d.status = 1',
            [
                'id'=>$id
            ]);
        return $data;
    }

    // delete policy details
    function delPolicyPerDetail(){
        $detail_id = request('detail_id');
        $folder = request('folder');
        $filename = request('filename');
        $filename = $filename.'.pdf';
        // return [$folder, $filename];
        if(\Storage::disk('local')->exists('public/policy/'.$folder.'/'.$filename))
            \Storage::delete('public/policy/'.$folder.'/'.$filename);

        $data = DB::table('policy_details_tbl')->where('detail_id', '=', $detail_id)->delete();
        return $data;
    }

}
