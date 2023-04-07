@extends('layouts.app')

@section('title', 'Player Stats')
@section('content')
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('players')}}">Players</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if(isset($name) && isset($player_data->team->name))
                        <a>{{$name}}</a>
                    @endif
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12">
            @if(isset($name) && isset($player_data->team->name))
                <h3>{{$name}} Goals - {{ucwords($player_data->team->name)}}</h3>
            @endif
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
            <th>#</th>
            <th>Date</th>
            <th>Home</th>
            <th>Result</th>
            <th>Away</th>
            <th>Goals</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($player_data) && $player_data != null)
            @foreach($player_data->stats as $key => $stat)
                <tr class="text-center">
                    <td>{{$key+1}}</td>
                    <td>{{$stat->date}}</td>
                    <td>{{$stat->home_team->name}}</td>
                    <td>{{$stat->result}}</td>
                    <td>{{$stat->away_team->name}}</td>
                    <td>{{$stat->goals}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
