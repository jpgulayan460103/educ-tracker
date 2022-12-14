<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompositionRequest;
use App\Models\Beneficiary;
use App\Models\BioParent;
use App\Models\Client;
use App\Models\Composition;
use App\Models\SwadOffice;
use App\Transformers\CompositionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            $user = Auth::user();
            $beneficiaries = [];
            foreach ($form_data['beneficiaries'] as $beneficiary_data) {
                $swad_office = SwadOffice::findOrFail($beneficiary_data['swad_office_id']);
                $format = $swad_office->code."-".str_pad($user->id, 2, "0", STR_PAD_LEFT)."-";
                $next_control_number = 1;
                $last_beneficiary = Beneficiary::where('control_number', 'like', "$format%")->orderBy('id','desc')->first();
                if($last_beneficiary){
                    $last_control_number_split = explode("-", $last_beneficiary->control_number);
                    $last_control_number = end($last_control_number_split);
                    $next_control_number = (int)$last_control_number + 1;
                }
                $beneficiary_data['control_number'] = $format.str_pad($next_control_number, 6, "0", STR_PAD_LEFT);
                $beneficiaries[] = new Beneficiary($beneficiary_data);
            }
            $father = new BioParent($form_data['father']);
            $father->relationship_beneficiary = "father";
            $mother = new BioParent($form_data['mother']);
            $mother->relationship_beneficiary = "mother";
            $composition = $client->composition()->create([
                'user_id' => $user->id
            ]);
            $composition->beneficiaries()->saveMany($beneficiaries);
            $composition->father()->save($father);
            $composition->mother()->save($mother);
            DB::commit();
            return fractal($composition, new CompositionTransformer)->parseIncludes('
                beneficiaries,
                father,
                mother,
                client,
            ');
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
            'beneficiaries.school_level',
            'father',
            'mother',
            'client.psgc',
            'client.sector',
            'client.sector_other',
            'client.client_sector',
        )->where('uuid', $uuid)->first();
        if($composition){
            return fractal($composition, new CompositionTransformer)->parseIncludes([
                'beneficiaries.payout',
                'beneficiaries.swad_office',
                'beneficiaries.school_level',
                'father',
                'mother',
                'client.psgc',
                'client.sector',
                'client.sector_other',
                'client.client_sector',
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
            $this->updateBeneficiaries($id);
            BioParent::find($form_data['father']['id'])->update($form_data['father']);
            BioParent::find($form_data['mother']['id'])->update($form_data['mother']);
            DB::commit();
            return $composition;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateBeneficiaries($id)
    {
        $composition = Composition::findOrFail($id);
        $item_ids_form = array();
        $item_ids = Beneficiary::where('composition_id',$id)->pluck('id')->toArray();
        $new_items = array();
        $user = Auth::user();
        foreach (request('beneficiaries') as $key => $item) {
            if(isset($item['id'])){
                Beneficiary::find($item['id'])->update($item);
                $item_ids_form[] = $item['id']; 
            }else{
                $item['composition_id'] = $composition->id;
                $swad_office = SwadOffice::findOrFail($item['swad_office_id']);
                $format = $swad_office->code."-".str_pad($user->id, 2, "0", STR_PAD_LEFT)."-";
                $next_control_number = 1;
                $last_beneficiary = Beneficiary::where('control_number', 'like', "$format%")->orderBy('id','desc')->first();
                if($last_beneficiary){
                    $last_control_number_split = explode("-", $last_beneficiary->control_number);
                    $last_control_number = end($last_control_number_split);
                    $next_control_number = (int)$last_control_number + 1;
                }
                $item['control_number'] = $format.str_pad($next_control_number, 6, "0", STR_PAD_LEFT);
                $new_items[$key] = new Beneficiary($item);
                $new_items[$key]->save();
            }
        }
        $this->removeItems($item_ids,$item_ids_form);
    }

    public function removeItems($item_ids,$item_ids_form)
    {
        $removed_item_ids = array_diff($item_ids,$item_ids_form);
        foreach ($removed_item_ids as $removed_item_id) {
            Beneficiary::where('id', $removed_item_id)->first()->delete();
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
