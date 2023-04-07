@extends('layouts.app')

@section('title', 'Player Stats')
@section('content')
    <div class="row">
        <div class="col-12">
            @if(isset($name) && $name !== null)
                <h3>{{$name}} - {{$player_team}}</h3>
            @endif
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
            @if(isset($table_header) && $table_header != null)
                @foreach($table_header as $item)
                    <th scope="col">{{$item}}</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($player_stats) && $player_stats != null)
            @foreach($player_stats as $player_stat)
                <tr class="text-center">
                    @foreach($player_stat as $item)
                        <td>{{$item}}</td>
                    @endforeach
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
@endsection
