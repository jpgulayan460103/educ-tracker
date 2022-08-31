@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <encoded-composition
        :payouts="{{ json_encode($payouts) }}"
    />
</div>
@endsection
