@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <users
        :swad-offices="{{ json_encode($swad_offices) }}"
    />
</div>
@endsection
