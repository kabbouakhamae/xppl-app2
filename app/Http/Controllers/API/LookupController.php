<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LookupController extends Controller
{
    public function country(){
        $country = DB::select("select code as value, code as label from lookup_code where category ='country' and used = 1");
        return $country;
    }

    public function province(){
        $prov = DB::select("select code as value, code as label from lookup_code where category ='province' and used = 1");
        return $prov;
    }
    
    public function district(Request $request){
        $dist = DB::select("select code as value, code as label from lookup_code where category ='district' and descr = ? and used = 1", [$request->prov]);
        return $dist;
    }

    public function position(){
        $position = DB::select("select code as value, code as label from lookup_code where category ='position' and used = 1");
        return $position;
    }

    public function status(){
        $status = DB::select("select code as value, code as label from lookup_code where category ='employee status' and used = 1");
        return $status;
    }

    public function contract(){
        $contract = DB::select("select code as value, code as label from lookup_code where category ='contract' and used = 1");
        return $contract;
    }

    public function levels(){
        $levels = DB::select("select code as value, code as label from lookup_code where category ='levels' and used = 1");
        return $levels;
    }

    public function roster(){
        $roster = DB::select("select code as value, code as label from lookup_code where category ='roster' and used = 1");
        return $roster;
    }

    public function scantimes(){
        $scantimes = DB::select("select code as value, code as label from lookup_code where category ='scan times' and used = 1");
        return $scantimes;
    }

    public function site(){
        $site = DB::select("select code as value, code as label from lookup_code where category ='site' and used = 1");
        return $site;
    }

    public function dept(){
        $dept = DB::select("select department as value, department as label from department");
        return $dept;
    }

    public function depts(){
        $dept = DB::select("select department as value, department as label from department where password is not null");
        return $dept;
    }

    public function section(){
        $section = DB::select("select code as value, code as label from lookup_code where category ='section' and used = 1");
        return $section;
    }
    public function crew(){
        $crew = DB::select("select code as value, code as label from lookup_code where category ='crew' and used = 1");
        return $crew;
    }

    public function name(){
        $name = DB::select('exec uspEmpName');
        return $name;
    }

    public function relate(){
        $relate = DB::select("select code as value, code as label from lookup_code where category = 'Relationship' and used = 1");
        return $relate;
    }

    public function bank(){
        $bank = DB::select("select code as value, code as label from lookup_code where category = 'Bank Name' and used = 1");
        return $bank;
    }

    public function card(){
        $card = DB::select("select code as value, code as label from lookup_code where category = 'Card Type' and used = 1");
        return $card;
    }

    public function year(){
        $year = DB::select("select code as value, code as label from lookup_code where category = 'year' and used = 1");
        return $year;
    }

    public function month(){
        $month = DB::select("select code as value, code as label from lookup_code where category = 'month' and used = 1 order by id");
        return $month;
    }

    public function fueldept(){
        $fdept = DB::select("select code as value, code as label from fuel_lookup where category in('cost center', 'io') and used = 1 order by category, code");
        return $fdept;
    }

    public function costtype(){
        $ftype = DB::select("select code as value, code as label from fuel_lookup where category = 'cost type' and used = 1 order by code");
        return $ftype;
    }

    public function costno(Request $request){
        $costno = DB::table('fuel_lookup')
                        ->select(DB::raw('cast(descr as float) as value, category'))
                        ->where('code', $request->dept)
                        ->where('used', '1')
                        ->first();
        return $costno;
    }

    public function wbs(Request $request){
        $wbs = DB::select("select code as value, code as label from fuel_lookup where category ='wbs' and used = 1 and dept = ?", [$request->dept]);
        return $wbs;
    }

    public function approve(Request $request){
        $approve = DB::select("select code as value, code as label from fuel_lookup where category ='approve by' and used = 1 and dept = ?", [$request->dept]);
        return $approve;
    }

    public function equipno(){
        $equip = DB::select('select equip_no as value, equip_no as label from fuel_equipments order by equip_no');
        return $equip;
    }

    public function material(){
        $material = DB::select("select code as value, descr as label from fuel_lookup where category = 'material code'");
        return $material;
    }

    public function equipdescr(Request $request){
        $equip_descr = DB::table("fuel_equipments")
                    ->where('equip_no', $request->equip_no)
                    ->first();
        
        return $equip_descr;
    }

    public function workorder(Request $request){
        $workorder = DB::table('fuel_workorders')
                        ->where('equip_no', $request->equip_no)
                        ->where('year_mont', $request->year_mont)
                        ->first();

        return $workorder;
    }

    public function driver(Request $request){
        $driver = DB::select("select code as value, code as label from fuel_lookup where category ='driver' and used = 1 and dept = ?", [$request->dept]);
        return $driver;
    }

    public function driverAdd(Request $request){
        $now = now("Asia/Bangkok")->toDateTimeString();
        $user = Str::lower(auth()->user()->username);

        DB::table('fuel_lookup')
            ->insert([
                'dept' => $request->dept,
                'category' => 'Driver',
                'code' => $request->driver,
                'created_at' => $now,
                'created_by' => $user
            ]);
        
        $drivers = DB::select("select code as value, code as label from fuel_lookup where category ='driver' and used = 1 and dept = ?", [$request->dept]);
        return $drivers;
    }

    public function empList(Request $request){
        $param = [
            $request->dept,
            $request->search.'%'
        ];
        $emplist2 = DB::select("select a.id, case when a.surname is null then name else name +' '+ a.surname end as name, 0 as sel from emp_names a, 
                               dbo.utfEmpDetails() b where a.id = b.userid and b.status = 'current' and b.department = ? and a.name like ? order by a.name", $param);
        return $emplist2;
    }

}
