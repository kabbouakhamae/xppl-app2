<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function prodDate(Request $request){
        $mon = $request->mon;
        $year = $request->year;

        $date = DB::select("select * from XPPL_PRODUCTION.dbo.tbl_MinedDate where format(minedDate,'MMMM') = ? and year(minedDate) = ? order by minedDate", [$mon, $year]);
        return $date;
    }

    public function prodData(Request $request){

        $data = DB::select("select row_number() over (order by recid) as no, * from XPPL_PRODUCTION.dbo.tbl_MinedProduction where minedDate = ? order by recid", [$request->date]);
        return $data;
    }

}
