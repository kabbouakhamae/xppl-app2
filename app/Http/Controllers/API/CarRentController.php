<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarRentController extends Controller
{   
    public function carComp(){
        $comp = DB::select("select code as value, code as label from adm_code where category = 'company' and used = 1");
        return $comp;
    }

    public function carType(){
        $carType = DB::select("select code as value, code as label from adm_code where category = 'car type' and used = 1");
        return $carType;
    }

    public function fuelKm(Request $request){
        $km = DB::table('adm_code')
                ->where('code', $request->carType)
                ->first();

        return $km;
    }

    public function equipNo(Request $request){
        $equip = DB::select("select equip_no as value, equip_no as label from fuel_equipments where dept_code = 'adm'");
        return $equip;
    }

    public function departPoint(){
        $from = DB::select("select code as value, code as label from adm_code where category = 'depart point' and used = 1");
        return $from;
    }

    public function destination(){
        $dest = DB::select("select code as value, code as label from adm_code where category = 'destination' and used = 1");
        return $dest;
    }

    public function paidType(){
        $paidType = DB::select("select code as value, code as label from adm_code where category = 'paid type' and used = 1");
        return $paidType;
    }

    public function route(){
        $route = DB::select("select code as value, code as label from adm_code where category = 'route' and used = 1");
        return $route;
    }

    public function routeKm(Request $request){
        $routeKm = DB::table('adm_code')
                    ->where('code', $request->route)
                    ->first();

        return $routeKm;
    }

    public function date(Request $request){
        $mont = $request->mont;
        $year = $request->year;

        $date = DB::select("select * from adm_car_date where format(rent_date,'MMMM') = ? and year(rent_date) = ? order by rent_date", [$mont, $year]);
        return $date;
    }

    public function addDate(Request $request){
        $check = DB::table('adm_car_date')
                    ->where('rent_date', $request->depart_date);

        $date = date('Y-m-d');

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } elseif ($request->depart_date > $date){
            $success = false;
            $message = 'The date is early than today!';
        } else {
            DB::table('adm_car_date')
                ->insert([
                    'rent_date' => $request->depart_date
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

    public function addHead(Request $request){
        $check = DB::table('adm_car_rentals')
                    ->where('equip_no', $request->equip_no)
                    ->where('depart_date', $request->depart_date);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            DB::table('adm_car_rentals')
                ->insert([
                    'company' => $request->company,
                    'car_type' => $request->car_type,
                    'equip_no' => $request->equip_no,
                    'equip_descr' => $request->equip_descr,
                    'depart_date' => $request->depart_date,
                    'return_date' => $request->return_date,
                    'driver' => $request->driver,
                    'depart_point' => $request->depart_point,
                    'destination' => $request->destination,
                    'route' => $request->route,
                    'route_km' => $request->route_km,
                    'trip' => $request->trip,
                    'total_km' => number_format($request->total_km, 2),
                    'total_l' => round($request->total_l, 2),
                    'fuel_per_km' => $request->fuel_per_km,
                    'fuel_price' => $request->fuel_price,
                    'refuel' => $request->refuel,
                    'remarks' => $request->remarks
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

    public function head(Request $request){
        $head = DB::table('adm_car_rentals')
                    ->where('depart_date', $request->date)
                    ->get();
        return $head;
    }

    public function editHead($id){
        $result = DB::table('adm_car_rentals')->find($id);
        return $result;
    }

    public function updHead(Request $request){
        DB::table('adm_car_rentals')
            ->where('id', $request->id)
            ->update([
                'company' => $request->company,
                'car_type' => $request->car_type,
                'equip_no' => $request->equip_no,
                'equip_descr' => $request->equip_descr,
                'return_date' => $request->return_date,
                'driver' => $request->driver,
                'depart_point' => $request->depart_point,
                'destination' => $request->destination,
                'route' => $request->route,
                'route_km' => $request->route_km,
                'trip' => $request->trip,
                'total_km' => number_format($request->total_km, 2),
                'total_l' => round($request->total_l, 2),
                'fuel_per_km' => $request->fuel_per_km,
                'fuel_price' => $request->fuel_price,
                'refuel' => $request->refuel,
                'remarks' => $request->remarks
            ]);
    }

    public function delHead($id){
        DB::delete('delete from adm_car_rentals where id = ?', [$id]);
    }

    public function addDetail(Request $request){
        $check = DB::table('adm_car_otherpaids')
                    ->where('rent_id', $request->rent_id)
                    ->where('paid_type', $request->paid_type);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            DB::table('adm_car_otherpaids')
                ->insert([
                    'rent_id' => $request->rent_id,
                    'paid_type' => $request->paid_type,
                    'amount' => $request->amount,
                    'qty' => $request->qty,
                    'total_amount' => $request->total_amount,
                    'remarks' => $request->remarks
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

    public function Detail(Request $request){
        $head = DB::table('adm_car_otherpaids')
                    ->where('rent_id', $request->rent_id)
                    ->get();
        return $head;
    }

    public function editDetail($id){
        $result = DB::table('adm_car_otherpaids')->find($id);
        return $result;
    }

    public function updDetail(Request $request){
        DB::table('adm_car_otherpaids')
            ->where('id', $request->id)
            ->update([
                'paid_type' => $request->paid_type,
                'amount' => $request->amount,
                'qty' => $request->qty,
                'total_amount' => $request->total_amount,
                'remarks' => $request->remarks
            ]);
    }

    public function delDetail($id){
        DB::delete('delete from adm_car_otherpaids where id = ?', [$id]);
    }


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

    public function category()
    {
        $cate = DB::select('select distinct category from adm_code order by category');
        return $cate;
    }

    public function code(Request $request)
    {
        $code = DB::select("select * from adm_code where category = ?", [$request->cate]);
        return $code;
    }

    public function addCode(Request $request)
    {
        $check = DB::table('adm_code')
                    ->where('category', $request->category)
                    ->where('code', $request->code);
        
        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);
            
            DB::table('adm_code')
                ->insert([
                    'category' => $request->category,
                    'code' => $request->code,
                    'value' => $request->value,
                    'descr' => $request->descr,
                    'used' => $request->used,
                    'created_at' => $datetime,
                    'created_by' => $username
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

    public function editCode($id)
    {
        $data = DB::table('adm_code')->find($id);
        return $data;
    }

    public function updCode(Request $request)
    {
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        DB::table('adm_code')
            ->where('id', $request->id)
            ->update([
                'category' => $request->category,
                'code' => $request->code,
                'value' => $request->value,
                'descr' => $request->descr,
                'used' => $request->used,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);
    }

    public function delCode($id)
    {
        DB::delete('delete from adm_code where id = ?', [$id]);
    }
}
