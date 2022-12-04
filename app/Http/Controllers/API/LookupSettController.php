<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LookupSettController extends Controller
{
    public function genCategory(){
        $cate = DB::select('select distinct category as value, category as label from lookup_code order by category');
        return $cate;
    }

    public function genLookup(Request $request){
        $lookup = DB::table('lookup_code')
                    ->where('category', $request->cate)
                    ->orderBy('code')
                    ->get();

        return $lookup;
    }

    public function genCodeAdd(Request $request){
        $check = DB::table('lookup_code')
                    ->where('category', $request->category)
                    ->where('code', $request->code);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            DB::table('lookup_code')
                ->insert([
                    'category' => $request->category,
                    'code' => $request->code,
                    'descr' => $request->descr,
                    'descr_lao' => $request->descr_lao,
                    'used' => $request->used
                ]);

            $success = true;
            $message = "Insert completed!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response);
    }

    public function genCodeEdit($id){
        $result = DB::table('lookup_code')->find($id);
        return $result;
    }

    public function genCodeUpd(Request $request){
        DB::table('lookup_code')
            ->where('id', $request->id)
            ->update([
                'code' => $request->code,
                'descr' => $request->descr,
                'descr_lao' => $request->descr_lao,
                'used' => $request->used
            ]);
    }

    public function genCodeDel($id){
        DB::delete('delete from lookup_code where id = ?', [$id]);
    }

    public function fuelCategory(){
        $cate = DB::select('select distinct category as value, category as label from fuel_lookup order by category');
        return $cate;
    }

    public function fuelLookup(Request $request){
        $lookup = DB::table('fuel_lookup')
                    ->where('category', $request->cate)
                    ->get();

        return $lookup;
    }

    public function fuelCodeAdd(Request $request){
        $check = DB::table('fuel_lookup')
                    ->where('dept', $request->dept)
                    ->where('category', $request->category)
                    ->where('code', $request->code);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            $now = now("Asia/Bangkok")->toDateTimeString();
            $user = Str::lower(auth()->user()->username);

            DB::table('fuel_lookup')
                ->insert([
                    'dept' => $request->dept,
                    'category' => $request->category,
                    'code' => $request->code,
                    'descr' => $request->descr,
                    'used' => $request->used,
                    'created_at' => $now,
                    'created_by' => $user
                ]);

            $success = true;
            $message = "Insert completed!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response);
    }

    public function fuelCodeEdit($id){
        $result = DB::table('fuel_lookup')->find($id);
        return $result;
    }

    public function fuelCodeUpd(Request $request){
        $now = now("Asia/Bangkok")->toDateTimeString();
        $user = Str::lower(auth()->user()->username);

        DB::table('fuel_lookup')
            ->where('id', $request->id)
            ->update([
                'dept' => $request->dept,
                'code' => $request->code,
                'descr' => $request->descr,
                'used' => $request->used,
                'updated_at' => $now,
                'updated_by' => $user
            ]);
    }

    public function fuelCodeDel($id){
        DB::delete('delete from fuel_lookup where id = ?', [$id]);
    }

    public function equipment(Request $request){
        $equipNo = '%'.$request->search.'%';

        $equip = DB::table('fuel_equipments')
                    ->where('equip_no', 'like', $equipNo)
                    ->orderBy('equip_name')
                    ->get();
        
        return $equip;
    }

    public function workOrder(Request $request){
        $workno = DB::table('fuel_workorders')
                    ->where('equip_no', $request->equip_no)
                    ->orderBy('basic_date')
                    ->get();

        return $workno;
    }

    public function equipAdd(Request $request){
        $check = DB::table('fuel_equipments')
                    ->where('equip_no', $request->equip_no);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            DB::table('fuel_equipments')
                ->insert([
                    'equip_no' => $request->equip_no,
                    'equip_name' => $request->equip_name,
                    'equip_descr' => $request->equip_descr
                ]);

            $success = true;
            $message = "Insert completed!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response);
    }

    public function equipEdit($id){
        $result = DB::table('fuel_equipments')->find($id);
        return $result;
    }

    public function equipUpd(Request $request){
        DB::table('fuel_equipments')
            ->where('id', $request->id)
            ->update([
                'equip_no' => $request->equip_no,
                'equip_name' => $request->equip_name,
                'equip_descr' => $request->equip_descr
            ]);
    }

    public function equipDel($id){
        DB::delete('delete from fuel_equipments where id = ?', [$id]);
    }

    public function workOrderAdd(Request $request){
        $check = DB::table('fuel_workorders')
                    ->where('equip_no', $request->equip_no)
                    ->where('work_order', $request->work_order);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            DB::table('fuel_workorders')
                ->insert([
                    'equip_no' => $request->equip_no,
                    'work_order' => $request->work_order,
                    'basic_date' => $request->basic_date,
                    'reserve_no' => $request->reserve_no,
                    'year_mont' => $request->year_mont
                ]);

            $success = true;
            $message = "Insert completed!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];
        return response()->json($response);
    }

    public function workOrderEdit($id){
        $result = DB::table('fuel_workorders')->find($id);
        return $result;
    }

    public function workOrderUpd(Request $request){
        DB::table('fuel_workorders')
            ->where('id', $request->id)
            ->update([
                'equip_no' => $request->equip_no,
                'work_order' => $request->work_order,
                'basic_date' => $request->basic_date,
                'reserve_no' => $request->reserve_no,
                'year_mont' => $request->year_mont
            ]);
    }

    public function workOrderDel($id){
        DB::delete('delete from fuel_workorders where id = ?', [$id]);
    }

}
