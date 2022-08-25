<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompositionRequest;
use App\Models\Beneficiary;
use App\Models\BioParent;
use App\Models\Client;
use App\Models\Composition;
use App\Transformers\CompositionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $composition = Composition::with(
            'beneficiaries',
            'father',
            'mother',
            'client',
        )->get();

        return fractal($composition, new CompositionTransformer)->parseIncludes('
            beneficiaries,
            father,
            mother,
            client,
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
    public function store(CompositionRequest $request)
    {
        try {
            DB::beginTransaction();
            $form_data = $request->all();
            $client = Client::create($form_data['client']);

            $beneficiaries = [];
            foreach ($form_data['beneficiaries'] as $beneficiary_data) {
                $beneficiaries[] = new Beneficiary($beneficiary_data);
            }

            $father = new BioParent($form_data['father']);
            $father->relationship_beneficiary = "father";
            $mother = new BioParent($form_data['mother']);
            $mother->relationship_beneficiary = "mother";
            $composition = $client->composition()->create();
            $composition->beneficiaries()->saveMany($beneficiaries);
            $composition->father()->save($father);
            $composition->mother()->save($mother);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function show(Composition $composition, $id)
    {
        return Composition::with(
            'beneficiaries',
            'father',
            'mother',
            'client',
        )->findOrFail($id);
    }

    public function showUuid(Composition $composition, $uuid)
    {
        $composition =  Composition::with(
            'beneficiaries.payout',
            'beneficiaries.swad_office',
            'father',
            'mother',
            'client.psgc',
            'client.sector',
            'client.sector_other',
        )->where('uuid', $uuid)->first();
        if($composition){
            return fractal($composition, new CompositionTransformer)->parseIncludes([
                'beneficiaries.payout',
                'beneficiaries.swad_office',
                'father',
                'mother',
                'client.psgc',
                'client.sector',
                'client.sector_other',
            ]);
        }else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function edit(Composition $composition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function update(CompositionRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $form_data = $request->all();
            $composition = Composition::findOrFail($id);
            Client::findOrFail($composition->client_id)->update($form_data['client']);
            $beneficiaries = [];
            foreach ($form_data['beneficiaries'] as $beneficiary_data) {
                Beneficiary::findOrFail($beneficiary_data['id'])->update($beneficiary_data);
            }
            BioParent::find($form_data['father']['id'])->update($form_data['father']);
            BioParent::find($form_data['mother']['id'])->update($form_data['mother']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Composition $composition)
    {
        //
    }
}
