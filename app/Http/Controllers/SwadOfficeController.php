<?php

namespace App\Http\Controllers;

use App\Models\SwadOffice;
use Illuminate\Http\Request;

class SwadOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $swad_offices = SwadOffice::query();
        if($request->id){
            $swad_offices->where('id', $request->id);
        }
        return $swad_offices->get();
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
     * @param  \App\Models\SwadOffice  $swadOffice
     * @return \Illuminate\Http\Response
     */
    public function show(SwadOffice $swadOffice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SwadOffice  $swadOffice
     * @return \Illuminate\Http\Response
     */
    public function edit(SwadOffice $swadOffice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SwadOffice  $swadOffice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SwadOffice $swadOffice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SwadOffice  $swadOffice
     * @return \Illuminate\Http\Response
     */
    public function destroy(SwadOffice $swadOffice)
    {
        //
    }
}
