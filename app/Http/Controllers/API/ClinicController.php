<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ClinicController extends Controller
{
    public function RegisterName(Request $request){
        $param = [
            $request->search.'%'
        ];
        
        $namelist = DB::select("select * from cli_registers where name like ?) order by name", $param);
        return $namelist;
    }

    public function NewID(){
        $max = DB::table('cli_registers')->max('userid');
        $newid = $max + 1;
        // $newid = substr(str_repeat(0, 5).$newid, - 5);

        return $newid;
    }


}
