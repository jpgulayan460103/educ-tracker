<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Client;
use App\Models\ClientSector;
use App\Models\Composition;
use App\Models\FundAllocation;
use App\Models\Payout;
use App\Models\Psgc;
use App\Models\SchoolLevel;
use App\Models\Sector;
use App\Models\SectorOther;
use App\Models\SwadOffice;
use App\Transformers\BeneficiaryTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Writer;

class BeneficiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $user = Auth::user();
        $beneficiaries = Beneficiary::with(
            'composition.father',
            'composition.mother',
            'composition.client.psgc',
            'composition.user',
            'school_level',
            'sector',
            'payout',
            'swad_office',
        );
        if(!$request->wantsJson()){
            abort(403);
        }
        if($user->user_role && $user->user_role != "Admin"){
            $user_id = $user->id;
            if($request->showEncoded == "yes"){
                $beneficiaries->whereHas("composition", function($q) use ($user_id){
                    $q->where("user_id", $user_id);
                });
            }else{
                $beneficiaries->whereHas("composition", function($q) use ($user){
                    $q->where("swad_office_id", $user->swad_office_id);
                });
            }
        }
        $beneficiaries->orderBy('id', 'desc');
        if(request()->has('type') && request('type') != "" && request()->has('keyword') && request('keyword') != ""){
            $keyword = $request->keyword;
            $type = $request->type;
            switch ($type) {
                case 'general':
                    $beneficiaries->where(function ($query) use ($keyword) {
                        $query->orWhereHas("composition.client", function($q) use ($keyword){
                            $keywords = explode(" ", $keyword);
                            $q->where(function ($query) use ($keywords) {
                                foreach ($keywords as $keyword) {
                                    $query->where("full_name", 'like', "%$keyword%");
                                }
                            });
                        });
                        $query->orWhere(function ($query) use ($keyword) {
                            $keywords = explode(" ", $keyword);
                            foreach ($keywords as $keyword) {
                                $query->where("full_name", 'like', "%$keyword%");
                            }
                        });
                    });
                    break;
                case 'control_number':
                    $beneficiaries->where('control_number', 'like', "%$keyword%");
                    break;
                case 'encoded_date':
                    $beneficiaries->whereBetween('created_at', [
                        Carbon::parse($keyword[0]),
                        Carbon::parse($keyword[1])->clone()->addDay()->subSecond()
                    ]);
                    break;
                case 'client':
                    $beneficiaries->whereHas("composition.client", function($q) use ($keyword){
                        $keywords = explode(" ", $keyword);
                        $q->where(function ($query) use ($keywords) {
                            foreach ($keywords as $keyword) {
                                $query->where("full_name", 'like', "%$keyword%");
                            }
                        });
                    });
                    break;
                case 'father':
                    $beneficiaries->whereHas("composition.father", function($q) use ($keyword){
                        $q->where("full_name", 'like', "%$keyword%");
                    });
                    break;
                case 'mother':
                    $beneficiaries->whereHas("composition.mother", function($q) use ($keyword){
                        $q->where("full_name", 'like', "%$keyword%");
                    });
                    break;
                case 'beneficiary':
                    $keywords = explode(" ", $keyword);
                    $beneficiaries->where(function ($query) use ($keywords) {
                        foreach ($keywords as $keyword) {
                            $query->where("full_name", 'like', "%$keyword%");
                        }
                    });
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        if(request()->has('payout_id') && request('payout_id') != ""){
            $beneficiaries->where("beneficiaries.payout_id", $request->payout_id);
        }
        if(request()->has('status') && request('status') != ""){
            $beneficiaries->where("beneficiaries.status", $request->status);
        }
        if(request()->has('swad_office_id') && request('swad_office_id') != ""){
            // $beneficiaries->where("beneficiaries.swad_office_id", $request->swad_office_id);
        }
        if($request->export){
            $num_pages = 250;
        }else{
            $num_pages = 10;
        }
        $beneficiaries = $beneficiaries->paginate($num_pages);
        // return DB::getQueryLog();

        return fractal($beneficiaries, new BeneficiaryTransformer)->parseIncludes('
            composition.father,
            composition.mother,
            composition.client.psgc,
            composition.user,
            school_level,
            sector,
            payout,
            swad_office,
        ')->toArray();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Beneficiary  $beneficiary
     * @return \Illuminate\Http\Response
     */
    public function show(Beneficiary $beneficiary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Beneficiary  $beneficiary
     * @return \Illuminate\Http\Response
     */
    public function edit(Beneficiary $beneficiary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Beneficiary  $beneficiary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Beneficiary $beneficiary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Beneficiary  $beneficiary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Beneficiary $beneficiary)
    {
        //
    }

    public function report(Request $request)
    {

        /*  */
        $report_query = Beneficiary::where('beneficiaries.status', 'Claimed');
        $report_query->leftJoin('compositions', 'beneficiaries.composition_id', '=', 'compositions.id');
        $report_query->leftJoin('clients', 'compositions.client_id', '=', 'clients.id');
        $report_query->leftJoin('swad_offices', 'beneficiaries.swad_office_id', '=', 'swad_offices.id');
        

        $select = [
            DB::raw("count(*) as beneficiary_served"),
            DB::raw("sum(amount_granted) as sum_amount_granted"),
            DB::raw("swad_offices.name as swad_office_name"),
            'beneficiaries.swad_office_id',
            'beneficiaries.school_level_id',
        ];
        $report_query->select($select);
        $report_query->groupBy('beneficiaries.swad_office_id');
        $report_query->groupBy('beneficiaries.school_level_id');

        if($request->payout_id){
            $payout_id = $request->payout_id;
            $report_query->where('beneficiaries.payout_id', $payout_id);
        }
        if($request->swad_office_id){
            $swad_office_id = $request->swad_office_id;
            $report_query->where('beneficiaries.swad_office_id', $swad_office_id);
        }

        if($request->by && $request->by == "district"){
            $report_query->leftJoin('psgcs', 'clients.psgc_id', '=', 'psgcs.id');
            $report_query->addSelect('psgcs.province_name');
            $report_query->addSelect('psgcs.district');
            $report_query->groupBy('psgcs.district');
            $report_query->whereNotNull('psgcs.district');
            
            // $report_query->orderBy('psgcs.district');
            // $report_query->orderBy('psgcs.province_name');
        }else{
            
        }

        $fund_allocations = FundAllocation::query();
        if($request->payout_id){
            $fund_allocations->where('payout_id', $request->payout_id);
        }
        if($request->swad_office_id){
            $fund_allocations->where('swad_office_id', $request->swad_office_id);
        }
        $fund_allocations->select('swad_office_id', DB::raw("SUM(allocated_amount) as total_allocated_amount"));
        $fund_allocations->groupBy('swad_office_id');

        return [
            'beneficiaries' => $report_query->get(),
            'fund_allocations' => $fund_allocations->get(),
        ];
    }

    public function importInitialize(Request $request)
    {
        $datetime = Carbon::now();
        $file = $request->file('file');
        $import_filename = $file->getClientOriginalName() . '-' . $datetime->toDateString() . "-" . $datetime->format('H-i-s');
        $file->move('files/imported/', $import_filename . "." . $file->getClientOriginalExtension());

        $datetime = Carbon::now();
        $processed_filename = "processed-".$import_filename . "." . $file->getClientOriginalExtension();
        $writer = Writer::createFromPath("files/processed/$processed_filename.csv", 'w+');

        return [
            'filename' => $import_filename,
            'processed_filename' => $processed_filename
        ];
    }

    public function extractImportedData(Request $request)
    {
        $filename = $request->filename;
        $reader = Reader::createFromPath(public_path('/files/imported/' . $filename . '.csv'), 'r');
        $results = $reader->getRecords();
        $data = array();
        foreach ($results as $key => $row) {
            $data[] = $row;
        }
        return $this->safe_json_encode($data);
    }

    public function importData(Request $request)
    {
        if($request->importType == "create"){
            return $this->addImportedData($request);
        }else{
            return $this->updateImportData($request);
        }
    }

    public function updateImportData(Request $request)
    {
        $beneficiaries = $request->beneficiaries;
        $processed_filename = $request->processed_filename;
        $columns = $request->columns;
        $writer = Writer::createFromPath("files/processed/$processed_filename.csv", 'a+');
        $columns[] = "Control Number";
        $columns[] = "UUID";
        $imported = [];
        foreach ($beneficiaries as $key => $beneficiary_data) {

            $uuid = $beneficiary_data[0];
            $control_number = $beneficiary_data[1];
            $beneficiary_name = $beneficiary_data[2];
            $school_level_name = $beneficiary_data[3];
            $status = $beneficiary_data[4];
            $payout_date = $beneficiary_data[5];
            $remarks = $beneficiary_data[6];

            $payout = Payout::where('payout_date', $payout_date)->first();
            $school_level = SchoolLevel::where('name', $school_level_name)->first();
            $payout_id = null;
            $school_level_id = null;

            if(!$payout){
                $beneficiary_data[] = "No Payout Schedule";
            }
            if(!$school_level){
                $beneficiary_data[] = "No Educational Level";
            }

            $beneficiary = Beneficiary::where('uuid', $uuid)->where('control_number', $control_number)->first();
            if($beneficiary){
                $beneficiary->status = $status;
                $beneficiary->remarks = $remarks;
                $beneficiary->payout_id = $payout_id;
                $beneficiary->school_level_id = $school_level_id;
                $beneficiary->save();
                $beneficiary_data[] = "Success";    
            }else{
                $beneficiary_data[] = "Failed";    
                $beneficiary_data[] = "No beneficiary found";    
            }

            $data = array();
            foreach ($beneficiary_data as $export_data) {
                $data[] = mb_convert_encoding($export_data, 'UTF-16LE', 'UTF-8');
            }
            $writer->insertOne($data);
            $imported[$key] = $data;
        }
        return [
            'count' => count($beneficiaries),
            'bene' => $imported
        ];
    }

    public function addImportedData(Request $request)
    {
        $beneficiaries = $request->beneficiaries;
        $processed_filename = $request->processed_filename;
        $columns = $request->columns;
        $writer = Writer::createFromPath("files/processed/$processed_filename.csv", 'a+');
        $columns[] = "Control Number";
        $columns[] = "UUID";
        // $writer->insertOne($columns);
        foreach ($beneficiaries as $key => $beneficiary_data) {

            $insert_data = [];
            $number = $beneficiary_data[0];
            $payout_date = $beneficiary_data[1];
            $swad_office_name = $beneficiary_data[2];
            $client_name = $beneficiary_data[4];
            if(trim($client_name) == ""){
                $client_name = $beneficiary_data[5];
            }
            $beneficiary_name = $beneficiary_data[5];
            $relationship_beneficiary = $beneficiary_data[6];
            $street_number = $beneficiary_data[7];
            $municipality = $beneficiary_data[8];
            $school_level_name = $beneficiary_data[9];
            $sector_name = $beneficiary_data[10];
            $sector_other_name = $beneficiary_data[11];
            $status = $beneficiary_data[12];
            $remarks = $beneficiary_data[13];
            $amount_granted = $beneficiary_data[14];
            $beneficiary_last_name = $beneficiary_data[15];
            $beneficiary_first_name = $beneficiary_data[16];
            $beneficiary_middle_name = $beneficiary_data[17];
            $beneficiary_ext_name = $beneficiary_data[18];

            $client_last_name = $beneficiary_data[19];
            $client_first_name = $beneficiary_data[20];
            $client_middle_name = $beneficiary_data[21];
            $client_ext_name = $beneficiary_data[22];
            $mobile_number = $beneficiary_data[23];

            $client_sector_id = null;
            $sector_id = null;
            $sector_other_id = null;
            $psgc_id = null;

            $client_sector = ClientSector::where('name' , trim($sector_name))->first();
            $sector_other = SectorOther::where('name' , trim($sector_other_name))->first();
            if($client_sector){
                $client_sector_id = $client_sector->id;
            }else{
                $sector = Sector::where('name' , trim($sector_name))->first();
                if($sector){
                    $sector_id = $sector->id;
                }
            }
            if($sector_other){
                $sector_other_id = $sector_other->id;
            }


            
            $payout = Payout::where('payout_date', $payout_date)->first();
            $swad_office = SwadOffice::where('name', $swad_office_name)->first();
            if($municipality != ""){
                $psgc = Psgc::where('swad_office_id', $swad_office->id)->where('city_name', $municipality)->first();
                if($psgc){
                    $psgc_id = $psgc->id;
                }
            }
            $client = Client::where('last_name', $client_name)->where('swad_office_id', $swad_office->id)->first();
            if($client && trim($client_name) != "" && $client_last_name == ""){
                $composition = Composition::where('client_id', $client->id)->first();
            }else{

                if($client_name == "" && $client_last_name != ""){
                    $client = Client::create([
                        'last_name' => $client_last_name,
                        'first_name' => $client_first_name,
                        'middle_name' => $client_middle_name,
                        'ext_name' => $client_ext_name,
                        'full_name' => '',
                        'street_number' => $street_number,
                        'psgc_id' => $psgc_id,
                        'swad_office_id' => $swad_office->id,
                        // 'mobile_number' => str_pad($mobile_number, 11, "0", STR_PAD_LEFT),
                        'age' => 0,
                        'gender' => '',
                        'occupation' => '',
                        'monthly_salary' => 0,
                        'relationship_beneficiary' => $relationship_beneficiary,
                        'sector_id' => $sector_id,
                        'client_sector_id' => $client_sector_id,
                        'sector_other_id' => $sector_other_id,
                        'remarks' => $remarks,
                    ]);
                }else{
                    $client = Client::create([
                        'last_name' => $client_name,
                        'first_name' => '',
                        'middle_name' => '',
                        'ext_name' => '',
                        'full_name' => '',
                        'street_number' => $street_number,
                        // 'psgc_id' => $psgc->id,
                        'swad_office_id' => $swad_office->id,
                        'mobile_number' => '',
                        'age' => 0,
                        'gender' => '',
                        'occupation' => '',
                        'monthly_salary' => 0,
                        'relationship_beneficiary' => $relationship_beneficiary,
                        'sector_id' => $sector_id,
                        'client_sector_id' => $client_sector_id,
                        'sector_other_id' => $sector_other_id,
                        'remarks' => $remarks,
                    ]);
                }

                $composition = Composition::create([
                    'client_id' => $client->id,
                    'user_id' => 1
                ]);
            }

            $swad_office = SwadOffice::where('name', $swad_office_name)->first();
            $school_level = SchoolLevel::where('name', $school_level_name)->first();
            $insert_data['swad_office_id'] = $swad_office->id;
            if($school_level){
                $insert_data['school_level_id'] = $school_level->id;
            }
            if($payout){
                $insert_data['payout_id'] = $payout->id;
            }
            $insert_data['composition_id'] = $composition->id;
            if($beneficiary_name != ""){
                $insert_data['last_name'] = $beneficiary_name;
            }else{
                $insert_data['last_name'] = $beneficiary_last_name;
                $insert_data['first_name'] = $beneficiary_first_name;
                $insert_data['middle_name'] = $beneficiary_middle_name;
                $insert_data['ext_name'] = $beneficiary_ext_name;
            }
            $insert_data['status'] = $status;
            $insert_data['amount_granted'] = $status == "Claimed" ? $school_level->amount : 0;
            $format = $swad_office->code."-01-";
            $next_control_number = 1;
            $last_beneficiary = Beneficiary::where('control_number', 'like', "$format%")->orderBy('id','desc')->first();
            if($last_beneficiary){
                $last_control_number_split = explode("-", $last_beneficiary->control_number);
                $last_control_number = end($last_control_number_split);
                $next_control_number = (int)$last_control_number + 1;
            }
            $insert_data['control_number'] = $format.str_pad($next_control_number, 6, "0", STR_PAD_LEFT);
            $beneficiary = Beneficiary::create($insert_data);
            $beneficiary_data[] = $beneficiary->control_number;
            $beneficiary_data[] = $beneficiary->uuid;

            $data = array();
            foreach ($beneficiary_data as $export_data) {
                $data[] = mb_convert_encoding($export_data, 'UTF-16LE', 'UTF-8');
            }
            $writer->insertOne($data);
        }
        return [
            'count' => count($beneficiaries),
        ];
    }

    public function export(Request $request, $type)
    {

        if (strtolower($type) == "create") {
            $datetime = Carbon::now();
            $filename = "aics-tracker-exported-data-" . $datetime->toDateString() . "-" . $datetime->format('H-i-s');
            $writer = Writer::createFromPath("files/exported/$filename.csv", 'w+');

            $headers = array();
            $headers[] = 'ID';
            $headers[] = 'Control Number';
            $headers[] = 'SWAD Office';
            $headers[] = 'Client Name';
            $headers[] = 'Cellphone Number';
            $headers[] = 'Beneficiary Full';
            $headers[] = 'Beneficiary Full';
            $headers[] = 'Beneficiary Last Name';
            $headers[] = 'Beneficiary First Name';
            $headers[] = 'Beneficiary Middle Name';
            $headers[] = 'Beneficiary Ext Name';
            $headers[] = 'Street Number';
            $headers[] = 'Province';
            $headers[] = 'Municipality';
            $headers[] = 'Barangay';
            $headers[] = 'District';
            $headers[] = 'School Level';
            $headers[] = 'Amount Granted';
            $headers[] = 'Status';
            $headers[] = 'Payout Date';
            $headers[] = 'Remarks';
            $headers[] = 'Father Name';
            $headers[] = 'Mother Name';
            $headers[] = 'Encoded By';
            $headers[] = 'Encoded Date time';

            $writer->insertOne($headers);
            $index_data = $this->index($request);
            return [
                'filename' => $filename,
                'pagination' => $index_data['meta']['pagination'],
            ];
        } elseif (strtolower($type) == "write") {
            $index_data = $this->index($request);
            $filename = $request->filename;
            $export = array();
            $writer = Writer::createFromPath("files/exported/$filename.csv", 'a+');
            foreach ($index_data['data'] as $beneficiary) {
                $export = array();
                $export[] = $beneficiary['uuid'];
                $export[] = $beneficiary['control_number'];
                $export[] = $beneficiary['swad_office']['name'];
                $export[] = isset($beneficiary['composition']['client']) ? $beneficiary['composition']['client']['full_name'] : "";
                $mobile_number = isset($beneficiary['composition']['client']) ? $beneficiary['composition']['client']['mobile_number'] : "";
                if($mobile_number != ""){
                    $export[] = "'+639".substr($mobile_number, 2);
                }else{
                    $export[] = "";
                }

                $export[] = $beneficiary['full_name'];
                $export[] = trim($beneficiary['last_name'].", ".$beneficiary['first_name']." ".$beneficiary['middle_name']." ".$beneficiary['ext_name']);
                $export[] = $beneficiary['last_name'];
                $export[] = $beneficiary['first_name'];
                $export[] = $beneficiary['middle_name'];
                $export[] = $beneficiary['ext_name'];
                $export[] = isset($beneficiary['composition']['client']) ? $beneficiary['composition']['client']['street_number'] : "";
                $export[] = isset($beneficiary['composition']['client']) && isset($beneficiary['composition']['client']['psgc']) ? $beneficiary['composition']['client']['psgc']['province_name'] : "";
                $export[] = isset($beneficiary['composition']['client']) && isset($beneficiary['composition']['client']['psgc']) ? $beneficiary['composition']['client']['psgc']['city_name'] : "";
                $export[] = isset($beneficiary['composition']['client']) && isset($beneficiary['composition']['client']['psgc']) ? $beneficiary['composition']['client']['psgc']['brgy_name'] : "";
                $export[] = isset($beneficiary['composition']['client']) && isset($beneficiary['composition']['client']['psgc']) ? $beneficiary['composition']['client']['psgc']['district'] : "";
                $export[] = isset($beneficiary['school_level']) ? $beneficiary['school_level']['name'] : "";
                $export[] = $beneficiary['amount_granted'];
                $export[] = $beneficiary['status'];
                $export[] = isset($beneficiary['payout']) ? $beneficiary['payout']['payout_date'] : "";
                $export[] = $beneficiary['remarks'];
                $export[] = isset($beneficiary['composition']['father']) ? $beneficiary['composition']['father']['full_name'] : "";
                $export[] = isset($beneficiary['composition']['mother']) ? $beneficiary['composition']['mother']['full_name'] : "";
                $export[] = isset($beneficiary['composition']['user']) ? $beneficiary['composition']['user']['full_name'] : "";
                $export[] = $beneficiary['created_at_full'];
                
                $data = array();
                foreach ($export as $export_data) {
                    $data[] = mb_convert_encoding($export_data, 'UTF-16LE', 'UTF-8');
                }
                $writer->insertOne($data);
            }
            return [
                'filename' => $filename,
                'page' => $request->page,
            ];
        }
    }

    public function cashBalance(Request $request)
    {

        $payout_id = request('payout_id');
        $swad_office_id = request('swad_office_id');
        $amount_granted = Beneficiary::where('payout_id', $payout_id)->where('swad_office_id', $swad_office_id)->sum('amount_granted');
        $allocated_amount = FundAllocation::where('payout_id', $payout_id)->where('swad_office_id', $swad_office_id)->sum('allocated_amount');
        return [
            'amount_granted' => $amount_granted,
            'allocated_amount' => $allocated_amount,
            'remaining' => ($allocated_amount - $amount_granted)
        ];
    }

    private function utf8ize($d)
    {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    private function safe_json_encode($value, $options = 0, $depth = 512)
    {
        $encoded = json_encode($value, $options, $depth);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_UTF8:
                $clean = $this->utf8ize($value);
                return $this->safe_json_encode($clean, $options, $depth);
            default:
                return 'Unknown error'; // or trigger_error() or throw new Exception()

        }
    }
}
