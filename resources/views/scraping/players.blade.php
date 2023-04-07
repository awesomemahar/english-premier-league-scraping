@extends('layouts.app')

@section('title', 'Player List')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Players</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <h3>Premier League All Players</h3>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
            <th>#</th>
            <th>Player Name</th>
            <th>Team</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($players) && $players != null)
            @foreach($players as $key => $player_stat)
                <tr class="text-center">
                    <td>{{$player_stat['player_id']}}</td>
                    <td>
                        <a href="{{route('player-stat-by-name',$player_stat['name'])}}">{{ucwords($player_stat['name'])}}</a>
                    </td>
                    <td>
                        <a href="{{route('teams-players',$player_stat->team['name'])}}">{{ucwords($player_stat->team['name'])}}</a>
                    </td>

                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {!! $players->links('pagination::bootstrap-4') !!}
    </div>
@endsection
