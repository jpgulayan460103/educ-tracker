<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Client;
use App\Models\Composition;
use App\Models\FundAllocation;
use App\Models\Payout;
use App\Models\Psgc;
use App\Models\SchoolLevel;
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
        if($user->user_role != "Admin"){
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
                case 'control_number':
                    $beneficiaries->where('control_number', 'like', "%$keyword%");
                    break;
                case 'encoded_date':
                    $beneficiaries->whereBetween('created_at', [
                        Carbon::parse($keyword),
                        Carbon::parse($keyword)->clone()->addDay()->subSecond()
                    ]);
                    break;
                case 'client':
                    $beneficiaries->whereHas("composition.client", function($q) use ($keyword){
                        $q->where("full_name", 'like', "%$keyword%");
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
                    $beneficiaries->where("full_name", 'like', "%$keyword%");
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

        $report_query = Beneficiary::where('status', 'Claimed');
        $report_query->select(
            DB::raw("count(*) as beneficiary_served"),
            DB::raw("sum(amount_granted) as sum_amount_granted"),
            'swad_office_id',
            'school_level_id',
        );
        $report_query->groupBy('swad_office_id');
        $report_query->groupBy('school_level_id');

        if($request->payout_id){
            $payout_id = $request->payout_id;
            $report_query->where('beneficiaries.payout_id', $payout_id);
        }
        if($request->swad_office_id){
            $swad_office_id = $request->swad_office_id;
            $report_query->where('beneficiaries.swad_office_id', $swad_office_id);
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

    public function resport(Request $request)
    {
        // DB::enableQueryLog();
        $swad_offices = SwadOffice::all();
        $school_level = SchoolLevel::all();
        $claimed_status = "Claimed";
        foreach ($swad_offices as $swad_office) {
            $beneficiaries_query = $swad_office
                ->beneficiaries()
                ->with('school_level')
                ->select(
                    DB::raw('count(*) as beneficiary_served'),
                    DB::raw('(count(*) * '.$swad_office->amount.') as sum_amount_granted'),
                    'school_level_id',
                )
                ->groupBy('school_level_id')
                ->where('beneficiaries.status', $claimed_status);
            $total_served_query = $swad_office
                ->beneficiaries()
                ->where('beneficiaries.status', $claimed_status);
            if($request->payout_id){
                $payout_id = $request->payout_id;
                $beneficiaries_query->where('beneficiaries.payout_id', $payout_id);
                $total_served_query->where('beneficiaries.payout_id', $payout_id);
            }
            if($request->school_level_id){
                $school_level_id = $request->school_level_id;
                $beneficiaries_query->where('beneficiaries.school_level_id', $school_level_id);
                $total_served_query->where('beneficiaries.school_level_id', $school_level_id);
            }
            $swad_office->school_levels = $beneficiaries_query->get();
            $swad_office->total_beneficiaries_served = $total_served_query->count();
            $swad_office->total_amount_granted = $swad_office->total_beneficiaries_served * $swad_office->amount;
        }
        // return DB::getQueryLog();
        return $swad_offices;
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
        $beneficiaries = $request->beneficiaries;
        $processed_filename = $request->processed_filename;
        $columns = $request->columns;
        $writer = Writer::createFromPath("files/processed/$processed_filename.csv", 'a+');
        $columns[] = "Control Number";
        $columns[] = "UUID";
        // $writer->insertOne($columns);
        foreach ($beneficiaries as $key => $beneficiary_data) {
            $client_name = $beneficiary_data[5];
            $payout_date = $beneficiary_data[6];
            $swad_office_name = $beneficiary_data[1];
            $payout = Payout::where('payout_date', $payout_date)->first();
            $swad_office = SwadOffice::where('name', $swad_office_name)->first();
            $psgc = Psgc::where('swad_office_id', $swad_office->id)->first();
            $client = Client::where('last_name', $client_name)->where('swad_office_id', $swad_office->id)->first();
            if($client){
                $composition = Composition::where('client_id', $client->id)->first();
            }else{
                $client = Client::create([
                    'last_name' => $client_name,
                    'first_name' => '',
                    'middle_name' => '',
                    'ext_name' => '',
                    'full_name' => '',
                    'street_number' => '',
                    // 'psgc_id' => $psgc->id,
                    'swad_office_id' => $swad_office->id,
                    'mobile_number' => '',
                    'age' => 0,
                    'gender' => '',
                    'occupation' => '',
                    'monthly_salary' => 0,
                    'relationship_beneficiary' => '',
                    // 'sector_id' => 1,
                ]);

                $composition = Composition::create([
                    'client_id' => $client->id,
                    'user_id' => 1
                ]);
            }

            $swad_office_name = $beneficiary_data[1];
            $insert_data['last_name'] = $beneficiary_data[2];
            $school_level_name = $beneficiary_data[3];
            

            $swad_office = SwadOffice::where('name', $swad_office_name)->first();
            $school_level = SchoolLevel::where('name', $school_level_name)->first();
            $insert_data['swad_office_id'] = $swad_office->id;
            $insert_data['school_level_id'] = $school_level->id;
            $insert_data['payout_id'] = $payout->id;
            $insert_data['composition_id'] = $composition->id;
            $insert_data['status'] = $beneficiary_data[7];
            $insert_data['amount_granted'] = $beneficiary_data[7] == "Claimed" ? $school_level->amount : 0;
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
            $headers[] = 'Beneficiary';
            $headers[] = 'Address';
            $headers[] = 'School Level';
            $headers[] = 'Amount Granted';
            $headers[] = 'Status';
            $headers[] = 'Payout Date';
            $headers[] = 'Remarks';
            $headers[] = 'Father Name';
            $headers[] = 'Mother Name';
            $headers[] = 'Encoded By';

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
                $export[] = isset($beneficiary['composition']['client']) ? $beneficiary['composition']['client']['psgc']['full_address'] : "";
                $export[] = $beneficiary['school_level']['name'];
                $export[] = $beneficiary['amount_granted'];
                $export[] = $beneficiary['status'];
                $export[] = $beneficiary['payout']['payout_date'];
                $export[] = $beneficiary['remarks'];
                $export[] = isset($beneficiary['composition']['father']) ? $beneficiary['composition']['father']['full_name'] : "";
                $export[] = isset($beneficiary['composition']['mother']) ? $beneficiary['composition']['mother']['full_name'] : "";
                $export[] = isset($beneficiary['composition']['user']) ? $beneficiary['composition']['user']['full_name'] : "";

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
