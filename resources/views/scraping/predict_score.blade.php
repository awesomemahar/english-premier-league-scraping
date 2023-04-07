@extends('layouts.app')

@section('title', 'Leagues')
@section('content')
    <div class="row">
        <div class="col-12">
            <h3>Match</h3>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
        <tr class="text-center">
            <th>#</th>
            <th>League</th>
            <th>Country</th>
        </tr>
        </tr>
        </thead>
        <tbody>
        @if(isset($leagues) && $leagues != null)
            @foreach($leagues as $key => $result)
                <tr class="text-center">
                    <td>{{$key+1}}</td>
                    <td>{{ucwords($result['name'])}}</td>
                    <td>{{$result['country']}}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
@endsection
