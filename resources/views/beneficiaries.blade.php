@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <encoded-composition
        :payouts="{{ json_encode($payouts) }}"
        :swad-offices="{{ json_encode($swad_offices) }}"
    />
</div>
@endsection
