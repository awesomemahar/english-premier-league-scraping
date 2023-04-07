@extends('layouts.app')

@section('title', 'Teams')
@section('content')
    <div class="row">
        <div class="col-12">
            <h3>Premier League Teams</h3>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
        <tr class="text-center">
            <th>#</th>
            <th>Team</th>
            <th>No. of Players</th>
        </tr>
        </tr>
        </thead>
        <tbody>
        @if(isset($teams) && $teams != null)
            @foreach($teams as $key => $result)
                <tr class="text-center">
                    <td>{{$key+1}}</td>
                    {{-- <td><a href="{{route('teams-fixtures',$result['team_id'])}}">{{ucwords($result['name'])}}</a> --}}
                    <td><a href="">{{ucwords($result['name'])}}</a>
                    </td>
                    <td><a href="{{route('teams-players',$result['name'])}}">{{count($result->players)}}</a></td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
