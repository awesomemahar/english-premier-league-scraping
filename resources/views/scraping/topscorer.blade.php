@extends('layouts.app')

@section('title', 'Top Scorers')
@section('content')
    <div class="row">
        <div class="col-12">
            <h3>Premier League Top Scorers</h3>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
            {{--@if(isset($table_header) && $table_header != null)
                @foreach($table_header as $item)
                    <th scope="col">{{$item}}</th>
                @endforeach
            @endif--}}
            <th>#</th>
            <th>Player</th>
            <th>Team</th>
            <th>Goals</th>
            <th>Home</th>
            <th>Away</th>
            <th>1-15min</th>
            <th>16-30min</th>
            <th>31-45min</th>
            <th>46-60min</th>
            <th>61-75min</th>
            <th>76-90min</th>
            <th>Last Goal Date</th>
            <th>% team</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($top_scorers) && $top_scorers != null)
            @foreach($top_scorers as $key=> $top_scorer)
                <tr class="text-center">
                    <td>{{$key+1}}</td>
                    <td>
                        <a href="{{route('player-stat-by-name',$top_scorer->player->name)}}">{{ucwords($top_scorer->player->name)}}</a>
                    </td>
                    <td>{{$top_scorer->player->team->name}}</td>
                    <td>{{$top_scorer->goals}}</td>
                    <td>{{$top_scorer->home}}</td>
                    <td>{{$top_scorer->away}}</td>
                    <td>{{$top_scorer->min_01_15}}</td>
                    <td>{{$top_scorer->min_16_30}}</td>
                    <td>{{$top_scorer->min_31_45}}</td>
                    <td>{{$top_scorer->min_46_60}}</td>
                    <td>{{$top_scorer->min_61_75}}</td>
                    <td>{{$top_scorer->min_76_90}}</td>
                    <td>{{\Carbon\Carbon::parse($top_scorer->last_goal_date)->format('D d M')}}</td>
                    <td>{{$top_scorer->percent_team}}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
@endsection
