<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{

    // public function home(){
    //     $userid = auth()->user()->userid;
    //     $home = DB::table('user_permission')
    //                 ->where('userid', $userid)
    //                 ->where('table_name', 'home')
    //                 ->first();
        
    //     return $home;
    // }

    // public function admin(){
    //     $userid = auth()->user()->userid;
    //     $admin = DB::table('user_permission')
    //                 ->where('userid', $userid)
    //                 ->where('table_name', 'admin_task')
    //                 ->first();
        
    //     return $admin;
    // }

    

    public function addFile(Request $request){
        if ($request->hasFile('file')){
            foreach ($request->file('file') as $file){
                
                $filename = $file->getClientOriginalName();
                $file->move(public_path('assets/macro/'), $filename);
                DB::table('macros')->insert([
                    'macro_name' => $filename
                ]);                
            }  
        }
    }

    public function files(){
        $data = DB::table('macros')->get();
        return $data;
    }

    public function download($file){

        return response()->download(public_path('assets/macro/'.$file));

    }

    public function delete($file){
        DB::table('macros')->where('macro_name', $file)->delete();

        if(file_exists(public_path('assets/macro/'.$file))){
            unlink(public_path('assets/macro/'.$file));
        }
    }
}
