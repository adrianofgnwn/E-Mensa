@extends('layouts.layout')

@section('content')
    <h1>Dish List</h1>

    @if(empty($dishes))
        <p>There are no dishes available.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Internal Price (€)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dishes as $dish)
                <tr>
                    <td>{{ $dish['name'] }}</td>
                    <td>{{ number_format($dish['preisintern'], 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection

@section('cssextra')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
@endsection

{{-- http://localhost:9000/m4_7c_gerichte --}}