@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <allocation
        :swad-offices="{{ json_encode($swad_offices) }}"
        :payouts="{{ json_encode($payouts) }}"
        :school-levels="{{ json_encode($school_levels) }}"
    />
</div>
@endsection
