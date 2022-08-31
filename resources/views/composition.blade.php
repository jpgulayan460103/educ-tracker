@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <encoding-composition
        :psgcs="{{ json_encode($psgcs) }}"
        :school-levels="{{ json_encode($school_levels) }}"
        :sectors="{{ json_encode($sectors) }}"
        :payouts="{{ json_encode($payouts) }}"
        :sector-others="{{ json_encode($sector_others) }}"
        :user="{{ json_encode($user) }}"
        uuid="{{ $uuid }}"
        :client-sectors="{{ json_encode($client_sectors) }}"
        :swad-offices="{{ json_encode($swad_offices) }}"
    />
</div>
@endsection
