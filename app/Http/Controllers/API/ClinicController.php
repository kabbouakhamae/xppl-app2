<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ClinicController extends Controller
{
    public function Lookup(){
        $lookup = DB::select("select category, code, descr_lao from dbo.cli_lookup_code where active = 1 union
                              select category, code, descr_lao from dbo.lookup_code where category in('position','section') and used = 1 union
                              select 'Department' as category, department as code, department_lao as descr_lao from dbo.department union
                              select 'Medicine' as category, medicine as code, unit as descr_lao from cli_medicines");
        return $lookup;
    }

    public function LKCategory(){
        $cate = DB::select("select distinct category from cli_lookup_code union select 'Medicine' as category");
        return $cate;
    }

    public function LKCode(){
        $code = DB::select('select * from cli_lookup_code');
        return $code;
    }

    public function LKMedicine(){
        $medic = DB::select('select * from cli_medicines');
        return $medic;
    }

    public function PatientList(Request $request){
        $param = [
            $request->search.'%',
            '%'.$request->search.'%'
        ];
        $namelist = DB::select("select * from cli_patient_list where name like ? or empid like ? order by name", $param);
        return $namelist;
    }

    public function TreatmentData(Request $request){
        $treat = DB::table('cli_treatments')->get();
        return $treat;
    }

    public function MedicationData(Request $request){
        $treat = DB::table('cli_medications')->get();
        return $treat;
    }

    public function AddPatient(Request $request){
        $n = $request->name;
        $t = $request->treat;

        $check = DB::table('cli_patient_list')
                    ->where('name', $n['name'])
                    ->where('surname', $n['surname']);

        if ($check->count()){
            $success = false;
            $message = "ຄົນໄຂ້ຊື່: ".$n['name']." ".$n['surname']." ມີຢູ່ໃນຖານຂໍ້ມູນແລ້ວ ກະລຸນາກວດຄືນ!";
        } else {

            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);

            DB::table('cli_patient_list')
                ->insert([
                    'ptid' => $n['ptid'],
                    'sex' => $n['sex'],
                    'name' => $n['name'],
                    'surname' => $n['surname'],
                    'position' => $n['position'],
                    'company' => $n['company'],
                    'dept' => $n['dept'],
                    'section' => $n['section'],
                    'blood_group' => $n['blood_group'],
                    'disease' => $n['disease'],
                    'empid' => $n['empid'],
                    'phone' => $n['phone'],
                    'age' => $n['age'],
                    'address' => $n['address'],
                    'created_at' => $datetime,
                    'created_by' => $username
                ]);

            DB::table('cli_treatments')
                ->insert([
                    'ptid' => $n['ptid'],
                    'tmid' => $t['tmid'],
                    'date_in' => $t['date_in'],
                    'time_in' => $t['time_in'],
                    'medical_type' => $t['medical_type'],
                    'temp_c' => $t['temp_c'],
                    'blood_press' => $t['bp'],
                    'puls' => $t['puls'],
                    'oxigen' => $t['ox'],
                    'weight' => $t['weight'],
                    'syndrome' => $t['syndrome'],
                    'diagnosis' => $t['diagnosis'],
                    'doctor' => $t['doctor'],
                    'patient_type' => $t['patient_type'],
                    'transfer' => $t['transfer'],
                    'hospital' => $t['hospital'],
                    'created_at' => $datetime,
                    'created_by' => $username
                ]);

                foreach($request->medic as $m){
                    DB::table('cli_medications')
                        ->insert([
                            'tmid' => $t['tmid'],
                            'medicine' => $m['medicine'],
                            'qty' => $m['qty'],
                            'unit' => $m['unit'],
                            'remark' => $m['remark'],
                            'created_at' => $datetime,
                            'created_by' => $username
                        ]);
                };   

            $success = true;
            $message = "Insert completed!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function UpdPatient(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);
        
        DB::table('cli_patient_list')
            ->where('ptid', $request->ptid)
            ->update([
                'sex' => $request->sex,
                'name' => $request->name,
                'surname' => $request->surname,
                'position' => $request->position,
                'company' => $request->company,
                'dept' => $request->dept,
                'section' => $request->section,
                'blood_group' => $request->blood_group,
                'disease' => $request->disease,
                'empid' => $request->empid,
                'phone' => $request->phone,
                'age' => $request->age,
                'address' => $request->address,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);
    }

    public function DelPatient($id){
        DB::delete('delete from cli_patient_list where ptid = ?', [$id]);
    }

    public function AddTreatment(Request $request){
        $t = $request->treat;
        $check = DB::table('cli_treatments')
                    ->where('ptid', $request->id)
                    ->where('date_in', $t['date_in']);

        if ($check->count()){
            $success = false;
            $message = "ລາຍການນີ້ມີຢູ່ໃນຖານຂໍ້ມູນແລ້ວ ກະລຸນາກວດເບີ່ງວັນທີຄືນ!";
        } else {

            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);

            DB::table('cli_treatments')
                ->insert([
                    'ptid' => $request->id,
                    'tmid' => $t['tmid'],
                    'date_in' => $t['date_in'],
                    'time_in' => $t['time_in'],
                    'medical_type' => $t['medical_type'],
                    'temp_c' => $t['temp_c'],
                    'blood_press' => $t['bp'],
                    'puls' => $t['puls'],
                    'oxigen' => $t['ox'],
                    'weight' => $t['weight'],
                    'syndrome' => $t['syndrome'],
                    'diagnosis' => $t['diagnosis'],
                    'doctor' => $t['doctor'],
                    'patient_type' => $t['patient_type'],
                    'transfer' => $t['transfer'],
                    'hospital' => $t['hospital'],
                    'created_at' => $datetime,
                    'created_by' => $username
                ]);

                foreach($request->medic as $m){
                    DB::table('cli_medications')
                        ->insert([
                            'tmid' => $t['tmid'],
                            'medicine' => $m['medicine'],
                            'qty' => $m['qty'],
                            'unit' => $m['unit'],
                            'remark' => $m['remark'],
                            'created_at' => $datetime,
                            'created_by' => $username
                        ]);
                };   

            $success = true;
            $message = "Insert completed!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function UpdTreatment(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);
        
        DB::table('cli_treatments')
            ->where('tmid', $request->tmid)
            ->update([
                'date_in' => $request->date_in,
                'time_in' => $request->time_in,
                'medical_type' => $request->medical_type,
                'temp_c' => $request->temp_c,
                'blood_press' => $request->bp,
                'puls' => $request->puls,
                'oxigen' => $request->ox,
                'weight' => $request->weight,
                'syndrome' => $request->syndrome,
                'diagnosis' => $request->diagnosis,
                'doctor' => $request->doctor,
                'patient_type' => $request->patient_type,
                'transfer' => $request->transfer,
                'hospital' => $request->hospital,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);
    }

    public function DelTreatment($id){
        DB::delete('delete from cli_treatments where tmid = ?', [$id]);
    }

    public function AddMedicine(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        DB::table('cli_medications')
            ->insert([
                'tmid' => $request->tmid,
                'medicine' => $request->medicine,
                'qty' => $request->qty,
                'unit' => $request->unit,
                'remark' => $request->remark,
                'created_at' => $datetime,
                'created_by' => $username
            ]);
    }

    public function UpdMedicine(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);
        
        DB::table('cli_medications')
            ->where('id', $request->id)
            ->update([
                'medicine' => $request->medicine,
                'qty' => $request->qty,
                'unit' => $request->unit,
                'remark' => $request->remark,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);
    }

    public function DelMedicine($id){
        DB::delete('delete from cli_medications where id = ?', [$id]);
    }

    public function AddLKCode(Request $request){
        $check = DB::table('cli_lookup_code')
                    ->where('category', $request->category)
                    ->where('code', $request->code);

        if ($check->count()){
            $success = false;
            $message = 'ລາຍການນີ້ມີຢູ່ໃນຖານຂໍ້ມູນແລ້ວ ກະລຸນາກວດຄືນ!';
        } else {

            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);

            DB::table('cli_lookup_code')
                ->insert([
                    'category' => $request->category,
                    'code' => $request->code,
                    'descr_eng' => $request->descr_eng,
                    'descr_lao' => $request->descr_lao,
                    'active' => $request->active,
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

    public function UpdLKCode(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);
        
        DB::table('cli_lookup_code')
            ->where('id', $request->id)
            ->update([
                'code' => $request->code,
                'descr_eng' => $request->descr_eng,
                'descr_lao' => $request->descr_lao,
                'active' => $request->active,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);
    }

    public function DelLKCode($id){
        DB::delete('delete from cli_lookup_code where id = ?', [$id]);
    }

    public function AddLKMedicine(Request $request){
        $check = DB::table('cli_medicines')
                    ->where('medicine', $request->medicine);

        if ($check->count()){
            $success = false;
            $message = 'ລາຍການນີ້ມີຢູ່ໃນຖານຂໍ້ມູນແລ້ວ ກະລຸນາກວດຄືນ!';
        } else {

            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);

            DB::table('cli_medicines')
                ->insert([
                    'medicine' => $request->medicine,
                    'medicine_lao' => $request->medicine_lao,
                    'properties' => $request->properties,
                    'unit' => $request->unit,
                    'unit_lao' => $request->unit_lao,
                    'minimum' => $request->minimum,
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

    public function UpdLKMedicine(Request $request){
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);
        
        DB::table('cli_medicines')
            ->where('id', $request->id)
            ->update([
                'medicine' => $request->medicine,
                'medicine_lao' => $request->medicine_lao,
                'properties' => $request->properties,
                'unit' => $request->unit,
                'unit_lao' => $request->unit_lao,
                'minimum' => $request->minimum,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);
    }

    public function DelLKMedicine($id){
        DB::delete('delete from cli_medicines where id = ?', [$id]);
    }



}
