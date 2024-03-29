<?php

// namespace App\Repository;
namespace App\Services;
use DB;

class UserSession{

    public static function getSessionID(){
        return session()->get('auth.empID');
    }


    public static function getEmpKey()
    {

        return DB::select('select empID, email, concat(fname," ",lname) as fullname, deptID_, branchID_, posID_, compID_
            from employee where empID = :session'
            ,[
                 session()->get('auth.empID')
            ]);
    }

    public static function formatDate($str_date, $format = "Y-m-d")
    {
        return date($format, strtotime($str_date));
    }

    public static function delDirectory($path){
        \File::deleteDirectory('storage/app/'.$path);
    }

    public static function delAttachment($path){
        if(\Storage::disk('local')->exists($path))
            \Storage::delete($path);
    }

    public static function postAttachment($path = ''){
        $avatar_path = null;
    	if(request('file') != 'null' ){
	        $avatar_path = request()->file('file')->store('public/attachment'.$path);
	     }
       return $avatar_path;
    }

    public static function fileAttachments_company_profile($folder = ''){
        $files = request()->file('attachment');
        $file_path = [];
        if(request()->has('attachment') && $files)
        {
            foreach(request()->file('attachment') as $image)
            {
                // $filename = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
                $filename = $image->getClientOriginalName();
                // $ext = $image->getClientOriginalExtension();


                if(\Storage::disk('local')->exists('public/'.$folder.'/'.$filename)){
                    \Storage::delete('public/'.$folder.'/'.$filename);
                    $path = $image->storeAs('public/'.$folder, $filename);
                    // $path = $image->store('public/'.$folder);
                    // $file_path[] = ['pdf_loc' => $path, 'empID_' => pathinfo($filename, PATHINFO_FILENAME)];
                }else{
                    $path = $image->storeAs('public/'.$folder, $filename);
                    // $path = $image->store('public/'.$folder);
                    $file_path[] = ['pdf_loc' => $path, 'empID_' => pathinfo($filename, PATHINFO_FILENAME)];
                }
            }
        }
        return $file_path;
    }


    public static function fileAttachments_policy($folder = ''){
        $files = request()->file('attachment');
        $file_path = [];
        if(request()->has('attachment') && $files)
        {
            foreach(request()->file('attachment') as $image)
            {
                $filename = $image->getClientOriginalName();
                // $ext = $image->getClientOriginalExtension();


                if(\Storage::disk('local')->exists('public/'.$folder.'/'.$filename)){
                    \Storage::delete('public/'.$folder.'/'.$filename);
                    $path = $image->storeAs('public/'.$folder, $filename);

                    // $file_path[] = ['pdf_loc' => $path, 'empID_' => pathinfo($filename, PATHINFO_FILENAME)];
                }else{
                    $path = $image->storeAs('public/'.$folder, $filename);
                    $file_path[] = ['pdf_loc' => $path, 'empID_' => pathinfo($filename, PATHINFO_FILENAME)];
                }
            }
        }
        return $file_path;
    }


    public static function fileAttachments($folder = '', $rand = false){
        $files = request()->file('attachment');
        $file_path = [];
        
        if(request()->has('attachment') && $files)
        {
            
            foreach($files as $image)
            {

                if($rand){
                    $path = $image->store('public/'.$folder);
                    $file_path[] = ['file_loc' => $path];
                }
                else{
                    $filename = $image->getClientOriginalName();
                    // $ext = $image->getClientOriginalExtension();
                    
                    if(\Storage::disk('local')->exists('public/'.$folder.'/'.$filename)){
                        \Storage::delete('public/'.$folder.'/'.$filename);
                        $path = $image->storeAs('public/'.$folder, $filename);

                        // $file_path[] = ['pdf_loc' => $path, 'empID_' => pathinfo($filename, PATHINFO_FILENAME)];
                    }else{
                        $path = $image->storeAs('public/'.$folder, $filename);
                        $file_path[] = ['pdf_loc' => $path, 'empID_' => pathinfo($filename, PATHINFO_FILENAME)];
                    }
                }
                
            }
        }
        return $file_path;

    // 	if(request('file') != 'null' ){
	//         $avatar_path = request()->file('file')->store('public/'.$folder);
	//      }
    //    return $avatar_path;
    }



    public static function IMG_Attachment($path = '', $helperLocation = ''){
        $files = request()->file('attachment');
        $file_path = [];
        $old_path = $path;
    
        if(request()->hasFile('attachment') && $files)
        {   
            if(!$helperLocation)
            \File::deleteDirectory('storage/app/public/'.$old_path);

            $count = 0;
            foreach($files as $image)
            {
                // helperLocation contains old location of images - use for array of images and look for exact location of specific each attachment
                if($helperLocation)
                \Storage::delete($helperLocation[$count]);
                
                $path = $image->store('public/'.$old_path);
                $file_path[$count] = $path ;
                
                $count++;

            }
        }
        return $file_path;
    }


/*
    public static function IMG_Attachment($path = ''){
        $files = request()->file('attachment');
        $file_path = [];
        $old_path = $path;
        // return $files;

        if(request()->hasFile('attachment') && $files)
        {
            \File::deleteDirectory('storage/app/public/'.$old_path);
            $count = 0;
            foreach($files as $image)
            {

                // use laravel random name
                // $path = $image->store('public/'.$path);

                // define filename
                // $filename = $image->getClientOriginalName();
                // if(\Storage::disk('local')->exists('public/'.$path.'/'.$filename)){
                //     \Storage::delete('public/'.$path.'/'.$filename);
                //     $path = $image->storeAs('public/'.$path, $filename);
                // }else{
                //     $path = $image->storeAs('public/'.$path, $filename);

                // }

                
                $path = $image->store('public/'.$old_path);
                $file_path[$count] = $path ;
                
                $count++;

            }
        }
        return $file_path;
    }
*/








    // public function newUserAvatar(){
    //     $attachment = null;

    // 	if(request('image') != 'null' ){
    //         // $attachment = request()->file('image')->store('public/attachment');
    //         // $extension = request()->file('image')->extension();


    //         $empID = request('empID');
    //         \Storage::delete('public/avatar/'.$empID);
	//         $attachment = request()->file('image')->storeAs('public/avatar', $empID);
	//      }
    //    return $attachment;
    // }

}
