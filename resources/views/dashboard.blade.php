@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <dashboard
        :all-swad-offices="{{ json_encode($swad_offices) }}"
        :all-payouts="{{ json_encode($payouts) }}"
    />
</div>
@endsection
