@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <encoding-composition
        :psgcs="{{ json_encode($psgcs) }}"
        :school-levels="{{ json_encode($school_levels) }}"
        :sectors="{{ json_encode($sectors) }}"
    />
</div>
@endsection
