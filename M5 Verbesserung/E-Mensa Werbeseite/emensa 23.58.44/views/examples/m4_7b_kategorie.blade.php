@extends('layouts.layout')

@section('content')
    <h1>Dish Categories</h1>

    @if(empty($categories))
        <p>No categories found.</p>
    @else
        <ul>
            @foreach($categories as $index => $category)
                <li class="{{ $index % 2 == 1 ? 'bold' : '' }}">
                    {{ $category['name'] }}
                </li>
            @endforeach
        </ul>
    @endif
@endsection

@section('cssextra')
    <style>
        .bold {
            font-weight: bold;
        }
    </style>
@endsection


{{-- localhost:9000/m4_7b_kategorie--}}