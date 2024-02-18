<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class JobPositionController extends Controller
{
   // ADD
   public function addPosition(){
   	request()->merge(['status' => 1]);
   	$data = DB::table('positiontbl')->insert(request()->except(['isDisable', 'posID']));
    return Request()->all();
   }

   // UPDATE
   public function updatePosition(){
       DB::table('positiontbl')->where('posID', request('posID'))->update(request()->except(['isDisable']));
       return request()->all();
   }

   public function getPosition(){
        $data = DB::select('select * from positiontbl where status=1 order by posname');
        return $data;
   }


   public function delPosition(){

        $data = DB::select('select empID from employee where posID_ = :posid and status = 1', [request('posID')]);
        if(count($data)>0){

            return ['error'=>count($data)];
        }else{
            DB::table('positiontbl')->where('posID', request('posID'))->delete();
            return $data;
        }
    }
}
