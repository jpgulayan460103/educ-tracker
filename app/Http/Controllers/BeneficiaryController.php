<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
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
        // DB::enableQueryLog();
        $school_levels = SchoolLevel::all();
        $swad_office = SwadOffice::all();
        $claimed_status = "Claimed";
        foreach ($school_levels as $school_level) {
            $beneficiaries_query = $school_level
                ->beneficiaries()
                ->with('swad_office')
                ->select(
                    DB::raw('count(*) as beneficiary_served'),
                    DB::raw('(count(*) * '.$school_level->amount.') as sum_amount_granted'),
                    'swad_office_id',
                )
                ->groupBy('swad_office_id')
                ->where('beneficiaries.status', $claimed_status);
            $total_served_query = $school_level
                ->beneficiaries()
                ->where('beneficiaries.status', $claimed_status);
            if($request->payout_id){
                $payout_id = $request->payout_id;
                $beneficiaries_query->where('beneficiaries.payout_id', $payout_id);
                $total_served_query->where('beneficiaries.payout_id', $payout_id);
            }
            if($request->swad_office_id){
                $swad_office_id = $request->swad_office_id;
                $beneficiaries_query->where('beneficiaries.swad_office_id', $swad_office_id);
                $total_served_query->where('beneficiaries.swad_office_id', $swad_office_id);
            }
            $school_level->swad_offices = $beneficiaries_query->get();
            $school_level->total_beneficiaries_served = $total_served_query->count();
            $school_level->total_amount_granted = $school_level->total_beneficiaries_served * $school_level->amount;
        }
        // return DB::getQueryLog();
        return $school_levels;
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
                $export[] = $beneficiary['composition']['client']['full_name'];
                $export[] = $beneficiary['full_name'];
                $export[] = $beneficiary['composition']['client']['psgc']['full_address'];
                $export[] = $beneficiary['school_level']['name'];
                $export[] = $beneficiary['amount_granted'];
                $export[] = $beneficiary['status'];
                $export[] = $beneficiary['payout']['payout_date'];
                $export[] = $beneficiary['remarks'];
                $export[] = $beneficiary['composition']['father']['full_name'];
                $export[] = $beneficiary['composition']['mother']['full_name'];
                $export[] = $beneficiary['composition']['user']['full_name'];

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
