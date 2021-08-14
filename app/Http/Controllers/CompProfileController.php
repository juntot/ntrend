<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserSession;
use DB;

class CompProfileController extends Controller
{
    // Policy Nav
    function getCompProfileNav(){
        $data = DB::select('SELECT pl.*, pd.profile_name, pd.detail_id FROM `comp_profile` pl
                        inner join comp_profile_details pd
                        on pl.id = pd.profile_id');
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
    function addCompProfile(){
        if(request()->has('clear')){

            //    clear all policy details which match the policy ID
            DB::table('comp_profile_details')->where(['profile_id' => request('profile_id')])->delete();
            $path = 'public/comp_profile/'.request('profile_id');
            if (\Storage::disk('local')->exists($path)) \Storage::deleteDirectory($path);
        }


        $yyyy = request('profile_id');
        $arr_file_path = UserSession::fileAttachments_company_profile('comp_profile/'.$yyyy);


        $bulk_data = [];
        if(count($arr_file_path) > 0 ){
            foreach ($arr_file_path as $key => $value) {
                $bulk_data[]=['profile_id' => request('profile_id'), 'profile_name'=>$value['empID_'], 'pdf_loc' => $value['pdf_loc']];

                DB::table('comp_profile_details')->insert(['profile_id' => request('profile_id'), 'profile_name'=>$value['empID_'], 'pdf_loc' => $value['pdf_loc']]);
            }
        }
        return $bulk_data;
    }

    // get policy category ALL
    function getCompProfileCategoryAll(){
        $data = DB::select('select * from comp_profile where status = 1');
        return $data;
    }


    // add policy category
    function addCompProfileCategory(){
        $data = DB::table('comp_profile')->insertGetId(request()->all());
        return $data;
    }

    // delete policy category
    function delCompProfileCategory($id= ''){
        $data = DB::table('comp_profile')
                ->where('id', $id)
                ->delete();
                // ->update(['status' => 0]);

        return $data;
    }


    // DETAILS TABLE ===================================================================
     // get ALL policy category details
     function getCompProfileCategoryDetails($id = ''){
        $data = DB::select('select * from comp_profile_details where profile_id =:id and status = 1',[
            'id'=>$id
        ]);
        return $data;
    }

    // get policy per details
    function CompProfilePerDetail($id){
        $data = DB::select('
        select c.pdf_loc, p.catname
            from comp_profile_details c
            inner join comp_profile p
                on c.profile_id = p.id
            where c.detail_id =:id and c.status = 1',
        // select pdf_loc from comp_profile_details where detail_id =:id and status = 1
        // ',
        [
            'id'=>$id
        ]);

        return $data;
    }

    // delete comp profile details
    function delCompProfilePerDetail(){
        $folder = request('foldername');
        $filename = request('filename');
        if(\Storage::disk('local')->exists('public/comp_profile/'.$folder.'/'.$filename))
            \Storage::delete('public/comp_profile/'.$folder.'/'.$filename);

        $data = DB::table('comp_profile_details')->where('detail_id', '=', $folder)->delete();
        return $data;
    }

}
