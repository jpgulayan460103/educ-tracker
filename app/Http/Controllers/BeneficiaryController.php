<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\SchoolLevel;
use App\Models\SwadOffice;
use App\Transformers\BeneficiaryTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $beneficiaries->orderBy('id', 'desc');
        $beneficiaries = $beneficiaries->paginate(10);

        return fractal($beneficiaries, new BeneficiaryTransformer)->parseIncludes('
            composition.father,
            composition.mother,
            composition.client.psgc,
            composition.user,
            school_level,
            sector,
            payout,
            swad_office,
        ');
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
                ->where('status', $claimed_status);
            $total_served_query = $school_level
                ->beneficiaries()
                ->where('status', $claimed_status);
            if($request->payout_id){
                $payout_id = $request->payout_id;
                $beneficiaries_query->where('payout_id', $payout_id);
                $total_served_query->where('payout_id', $payout_id);
            }
            if($request->swad_office_id){
                $swad_office_id = $request->swad_office_id;
                $beneficiaries_query->where('swad_office_id', $swad_office_id);
                $total_served_query->where('swad_office_id', $swad_office_id);
            }
            $school_level->swad_offices = $beneficiaries_query->get();
            $school_level->total_beneficiaries_served = $total_served_query->count();
            $school_level->total_amount_granted = $school_level->total_beneficiaries_served * $school_level->amount;
        }
        // return DB::getQueryLog();
        return $school_levels;
    }
}
