@extends('layouts.layout')

@section('content')
    <h1>Query Parameter Display</h1>
    <p>The value of <strong>name</strong> is: <strong>{{ $name }}</strong></p>
@endsection


{{-- Test URL: http://localhost:9000/m4_7a_queryparameter?name=John --}}