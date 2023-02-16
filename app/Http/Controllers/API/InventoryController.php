<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function prRecord(Request $request){
        $param = [
            $request->year,
            $request->search.'%',
        ];
        
        $prRecord = DB::select("select * from inv_purchase_request where year(created_at) = ? and pr_no like ? order by pr_id", $param);
        return $prRecord;
    }

}
