@extends('layouts.app')

@section('title', 'Team')
@section('content')
    <div class="row">
        <div class="col-12">
            @if(isset($team))
                <h3>List of {{ucwords($team)}} Players</h3>
            @endif
        </div>
        <div class="col-6">
            <table class="table">
                <thead class="thead-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Player Name</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($team_data) && $team_data != null)
                    @foreach($team_data->players as $key => $value)
                        <tr class="text-center">
                            <td>{{$key+1}}</td>
                            <td>
                                <a href="{{route('player-stat-by-name',$value['name'])}}">{{ucwords($value['name'])}}</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
