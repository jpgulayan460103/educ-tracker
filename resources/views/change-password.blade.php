@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <change-password
        :user="{{ json_encode($user) }}"
    />
</div>
@endsection
