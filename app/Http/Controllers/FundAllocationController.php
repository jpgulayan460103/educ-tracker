<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundAllocationRequest;
use App\Models\FundAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FundAllocation::with('user', 'payout', 'swad_office', 'school_level')
        ->orderBy('payout_id')
        ->orderBy('swad_office_id')
        ->orderBy('school_level_id')
        ->get();
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
    public function store(FundAllocationRequest $request)
    {
        $user = Auth::user();
        $fund_allocation = FundAllocation::create($request->all());
        $fund_allocation->user_id = $user->id;
        $fund_allocation->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FundAllocation  $fundAllocation
     * @return \Illuminate\Http\Response
     */
    public function show(FundAllocation $fundAllocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FundAllocation  $fundAllocation
     * @return \Illuminate\Http\Response
     */
    public function edit(FundAllocation $fundAllocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FundAllocation  $fundAllocation
     * @return \Illuminate\Http\Response
     */
    public function update(FundAllocationRequest $request, $id)
    {
        $fund_allocation = FundAllocation::findOrFail($id);
        $fund_allocation->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FundAllocation  $fundAllocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(FundAllocation $fundAllocation)
    {
        //
    }
}
