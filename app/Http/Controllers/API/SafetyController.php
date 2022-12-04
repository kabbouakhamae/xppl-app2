<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SafetyController extends Controller
{   
    public function incGroup(){
        $incGroup = DB::select("select code as value, code as label from saf_lookup_code where category ='incident group' and active = 1 order by code");
        return $incGroup;
    }

    public function incType(){
        $incType = DB::select("select code as value, code as label from saf_lookup_code where category ='incident type' and active = 1 order by code");
        return $incType;
    }

    public function injuryType(){
        $injType = DB::select("select code as value, descr as label from saf_lookup_code where category ='injury type' and active = 1 order by code");
        return $injType;
    }

    public function injuryGroup(){
        $injGroup = DB::select("select code as value, code as label from saf_lookup_code where category ='injury body group' and active = 1 order by code");
        return $injGroup;
    }

    public function actualSeq(){
        $actSeq = DB::select("select code as value, descr as label from saf_lookup_code where category ='actual consequence' and active = 1 order by code");
        return $actSeq;
    }

    public function potentialSeq(){
        $potSeq = DB::select("select code as value, descr as label from saf_lookup_code where category ='potential consequence' and active = 1 order by code");
        return $potSeq;
    }

    public function investStatus(){
        $invest = DB::select("select code as value, code as label from saf_lookup_code where category ='investigation status' and active = 1 order by code");
        return $invest;
    }

    public function actionStatus(){
        $action = DB::select("select code as value, code as label from saf_lookup_code where category ='action status' and active = 1 order by code");
        return $action;
    }

    public function yesNo(){
        $yesno = DB::select("select code as value, code as label from saf_lookup_code where category ='yes/no' and active = 1 order by code");
        return $yesno;
    }

    public function company(){
        $comp = DB::select("select code as value, code as label from saf_lookup_code where category ='company' and active = 1 order by code");
        return $comp;
    }

    public function incRecord(Request $request){
        $param = [
            $request->year,
            $request->search.'%',
            $request->search.'%',
            $request->search.'%',
            $request->search.'%',
        ];
        
        $incRecord = DB::select("select * from saf_incident_records where year(inc_date) = ? and (inc_id like ? or inc_title like ? or company like ? or dept like ?) order by inc_id", $param);
        return $incRecord;
    }

    public function newIncidentId(){
        $max = DB::table('saf_incident_records')->max('inc_id');
        $newid = $max + 1;
        $incid = substr(str_repeat(0, 4).$newid, - 4);

        return $incid;
    }

    public function addIncident(Request $request){
        $check = DB::table('saf_incident_records')
                    ->where('inc_date', $request->inc_date)
                    ->where('inc_title', $request->inc_title);

        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {

            // $max = DB::table('saf_incident_records')->max('inc_id');
            // $newid = $max + 1;
            // $incid = substr(str_repeat(0, 4).$newid, - 4);
            $rating =  $request->act_seq * $request->pot_seq;

            DB::table('saf_incident_records')
                ->insert([
                    'inc_id' => $request->inc_id,
                    'inc_date' => $request->inc_date,
                    'inc_title' => $request->inc_title,
                    'company' => $request->company,
                    'dept' => $request->dept,
                    'inc_group' => $request->inc_group,
                    'inc_type' => $request->inc_type,
                    'significant' => $request->significant,
                    'flash_alert' => $request->flash_alert,
                    'injury_type' => $request->inj_type,
                    'injury_group' => $request->inj_group,
                    'injury_part' => $request->inj_part,
                    'actual_severity' => $request->act_seq,
                    'potential_severity' => $request->pot_seq,
                    'risk_rating' => $rating,
                    'invest_status' => $request->invest_status,
                    'invest_lead' => $request->invest_lead,
                    'action_status' => $request->action_status,
                    'action_duedate' => $request->action_duedate,
                    'inc_manager' => $request->inc_manager,
                    'followup_by' => $request->followup_by,
                    'comments' => $request->comments,
                    'inc_descr' => $request->inc_descr
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

    public function editIncident($id){
        $data = DB::table('saf_incident_records')->find($id);
        return $data;
    }

    public function updIncident(Request $request){
        $rating =  $request->act_seq * $request->pot_seq;

        DB::table('saf_incident_records')
            ->where('id', $request->id)
            ->update([
                'inc_date' => $request->inc_date,
                'inc_title' => $request->inc_title,
                'company' => $request->company,
                'dept' => $request->dept,
                'inc_group' => $request->inc_group,
                'inc_type' => $request->inc_type,
                'significant' => $request->significant,
                'flash_alert' => $request->flash_alert,
                'injury_type' => $request->inj_type,
                'injury_group' => $request->inj_group,
                'injury_part' => $request->inj_part,
                'actual_severity' => $request->act_seq,
                'potential_severity' => $request->pot_seq,
                'risk_rating' => $rating,
                'invest_status' => $request->invest_status,
                'invest_lead' => $request->invest_lead,
                'action_status' => $request->action_status,
                'action_duedate' => $request->action_duedate,
                'inc_manager' => $request->inc_manager,
                'followup_by' => $request->followup_by,
                'comments' => $request->comments,
                'inc_descr' => $request->inc_descr
            ]);
    }

    public function delIncident(Request $request){
        DB::delete('delete from saf_incident_records where id = ?', [$request->id]);

        $check = DB::table('saf_files')->where('inc_id', $request->incid);

        if ($check->count()){
            $files = DB::table('saf_files')->where('inc_id', $request->incid)->get();
            
            foreach ($files as $file){
                if(file_exists(public_path('assets/safety/'.$file->file_name))){
                    unlink(public_path('assets/safety/'.$file->file_name));
                }
            }  

            DB::table('saf_files')->where('inc_id', $request->incid)->delete();
        }
    }

    public function editFiles($id){
        $data = DB::table('saf_files')->where('inc_id', $id)->get();
        return $data;
    }

    public function addFiles(Request $request){
        if ($request->hasFile('files')){
            foreach ($request->file('files') as $file){
                
                $filename = $request->incid.'-'.$file->getClientOriginalName();
                $file->move(public_path('assets/safety/'), $filename);

                DB::table('saf_files')->insert([
                    'inc_id' => $request->incid,
                    'file_name' => $filename
                ]);                
            }  
        }
    }

    public function downloadFile($file){

        return response()->download(public_path('assets/safety/'.$file));

    }

    public function delFile($file){
        DB::table('saf_files')->where('file_name', $file)->delete();

        if(file_exists(public_path('assets/safety/'.$file))){
            unlink(public_path('assets/safety/'.$file));
        }
    }

    public function preview(Request $request){
        $prev = DB::select("select a.*, b.descr as inj_type, c.descr as act_seq, d.descr as pot_seq from saf_incident_records a
                            left outer join (select code,  descr + ' ('+ code +')' as descr from dbo.saf_lookup_code where category = 'injury type') as b on b.code = a.injury_type
                            left outer join (select code,  descr + ' ('+ code +')' as descr from dbo.saf_lookup_code where category = 'actual consequence') as c on c.code = a.actual_severity
                            left outer join (select code,  descr + ' ('+ code +')' as descr from dbo.saf_lookup_code where category = 'potential consequence') as d on d.code = a.potential_severity
                            where a.id = ?", [$request->id]);

        return $prev;
    }

    public function category()
    {
        $cate = DB::select('select distinct category from saf_lookup_code order by category');
        return $cate;
    }

    public function code(Request $request)
    {
        $code = DB::select("select * from saf_lookup_code where category = ?", [$request->cate]);
        return $code;
    }

    public function addCode(Request $request)
    {
        $check = DB::table('saf_lookup_code')
                    ->where('category', $request->category)
                    ->where('code', $request->code);
        
        if ($check->count()){
            $success = false;
            $message = 'Duplicate record!';
        } else {
            $datetime = now("Asia/Bangkok")->toDateTimeString();
            $username = Str::lower(auth()->user()->username);
            
            DB::table('saf_lookup_code')
                ->insert([
                    'category' => $request->category,
                    'code' => $request->code,
                    'descr' => $request->descr,
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

    public function editCode($id)
    {
        $data = DB::table('saf_lookup_code')->find($id);
        return $data;
    }

    public function updCode(Request $request)
    {
        $datetime = now("Asia/Bangkok")->toDateTimeString();
        $username = Str::lower(auth()->user()->username);

        DB::table('saf_lookup_code')
            ->where('id', $request->id)
            ->update([
                'category' => $request->category,
                'code' => $request->code,
                'descr' => $request->descr,
                'active' => $request->active,
                'updated_at' => $datetime,
                'updated_by' => $username
            ]);
    }

    public function delCode($id)
    {
        DB::delete('delete from saf_lookup_code where id = ?', [$id]);
    }

    public function exIncident()
    {
        $exInc = DB::table('saf_incident_records')->get();
        return $exInc;
    }

    

}
