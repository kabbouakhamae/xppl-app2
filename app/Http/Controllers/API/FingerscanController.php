<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FingerscanController extends Controller
{   
    public function dateCol(Request $request){
        $m = explode('-',$request->date)[1];
        $y = explode('-',$request->date)[0];

        $col = DB::select("select 'c'+ convert(varchar, calendar, 112) as 'colid', left(convert(varchar, calendar, 3),2) as colname, format(calendar, 'MMMM-yyyy') as mont from calendar where year(calendar) = ? and month(calendar) = ? order by calendar", [$y, $m]);
        return $col;
    }

    public function summary(Request $request){
        $param = [
            $request->dept,
            $request->date,
            $request->search.'%'
        ];
        
        $summary = DB::select('exec uspEmpScanSummary ?, ?, ?', $param);
        return $summary;
    }

    public function detail(Request $request){
        $param = [
            $request->dept,
            $request->date,
            $request->userid
        ];

        $detail = DB::select('exec uspEmpScanDetail ?, ?, ?', $param);
        return $detail;
    }

    public function acLog(Request $request){
        $param = [
            $request->date,
            $request->userid
        ];

        $aclog = DB::select('exec uspEmpScanAcLog ?, ?', $param);
        return $aclog;
    }

    public function header(Request $request){
        $param = [
            $request->datefr,
            $request->dateto
        ];
        
        $dead = DB::select('exec uspEmpScanSummaryHead ?, ?', $param);
        return $dead;
    }

    public function summary2(Request $request){
        $param = [
            $request->dept,
            $request->datefr,
            $request->dateto,
            $request->search.'%'
        ];
        
        $summary = DB::select('exec uspEmpScanSummary2 ?, ?, ?, ?', $param);
        return $summary;
    }


    public function testProd()
    {
        $test = DB::select("select * from XPPL_PRODUCTION.dbo.tbl_MinedProduction WHERE YEAR(MinedDate) = '2022'");
        return $test;
    }
}
