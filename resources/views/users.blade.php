@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <users
        :swad-offices="{{ json_encode($swad_offices) }}"
        :offices="{{ json_encode($offices) }}"
    />
</div>
@endsection
