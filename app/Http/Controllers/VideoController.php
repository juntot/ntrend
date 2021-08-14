<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\UserSession;
use DB;

class VideoController extends Controller
{
    // ADD
    public function addVid()
    {
        $path = $this->newVideo();

        request()->merge(['vidpath'=> $path]);
        DB::table('compvideos')->insert(['title' => request('title'), 'vidpath' => $path, 'uploadedby_' => UserSession::getSessionID()]);
        return $path;

    }
    // GET
    public function getVid(){
        $data = DB::select('select * from compvideos order by vidID desc');
        return $data;
    }
    // DELETE
    public function delVid($name = null){
        $path = 'public/videos/'.$name;
        DB::table('compvideos')->where('vidpath', $path)->delete();
        Storage::disk('local')->delete($path);
    }

    public function updateVid(){

        $response = DB::table('compvideos')
        ->where('vidID', request('vidID'))
        ->update(request()->except(['vidID']));
         return $response;
    }

    // GET DEPTS
    public function getDeptNames(){
        $data = DB::select('select deptnames from department');
        return $data;
    }
    // store image to local disk
    public function newVideo(){
        $vid_path = null;
    	if(request('vid') != 'null' ){
	        $vid_path = request()->file('video')->store('public/videos');
	     }
       return $vid_path;
    }
}
