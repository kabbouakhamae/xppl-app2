<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpFileController extends Controller
{   
    public function data($id){
        $data = DB::table('emp_files')->where('userid', $id)->get();
        return $data;
    }

    public function add(Request $request){
        if ($request->hasFile('files')){
            foreach ($request->file('files') as $file){
                
                $filename = $request->userid.'-'.$file->getClientOriginalName();
                $file->move(public_path('assets/files/'), $filename);
                DB::table('emp_files')->insert([
                    'userid' => $request->userid,
                    'filename' => $filename
                ]);                
            }  
        }
    }


    public function download($file){

        return response()->download(public_path('assets/files/'.$file));

    }

    public function delete($file){
        DB::table('emp_files')->where('filename', $file)->delete();

        if(file_exists(public_path('assets/files/'.$file))){
            unlink(public_path('assets/files/'.$file));
        }
    }

}
