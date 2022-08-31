<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\FundAllocation;
use App\Models\SchoolLevel;
use App\Models\SwadOffice;
use App\Transformers\BeneficiaryTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $beneficiaries->whereHas("composition", function($q) use ($user_id){
                $q->where("user_id", $user_id);
            });
        }
        $beneficiaries->orderBy('id', 'desc');
        if(request()->has('type') && request('type') != "" && request()->has('keyword') && request('keyword') != ""){
            $keyword = $request->keyword;
            $type = $request->type;
            switch ($type) {
                case 'control_number':
                    $beneficiaries->where('control_number', 'like', "%$keyword%");
                    break;
                case 'status':
                    $beneficiaries->where('status', $keyword);
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
            $headers[] = 'Status Date';
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
}
