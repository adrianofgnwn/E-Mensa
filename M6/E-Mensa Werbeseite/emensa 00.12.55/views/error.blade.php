{{-- Error Page --}}
@extends('layout')

@section('content')
    <h1>Fehler</h1>
    <p>{{ session('error') }}</p>
@endsection
