@extends('layouts.admin')

@section('content')
    <div id="app">
        <unit-edit></unit-edit>
    </div>
@endsection

@section('custom-js')
<script src="{{ asset('js/app.js') }}"></script>
@endsection