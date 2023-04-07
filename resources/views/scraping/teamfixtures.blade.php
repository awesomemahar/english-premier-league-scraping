@extends('layouts.app')

@section('title', 'Home Away Fixtures')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('fixtures')}}">Fixtures</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        @if(isset($team_data) && $team_data != null)
                            <a>{{ucwords($team_data->name)}}</a>
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 text-center">
            @if(isset($team_data) && $team_data != null)
                <h3>{{ucwords($team_data->name)}}</h3>
            @endif
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="col-12">
                <h4>Home Fixtures</h4>
            </div>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Date</th>
                        <th>Home</th>
                        <th>Time</th>
                        <th>Away</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($home_fixtures) && $home_fixtures != null)
                        @foreach($home_fixtures as $key => $item)
                            @if(strpos($item['result'], ':') !== false)
                                <tr class="text-center">
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($item['date'])->format('D d M')}}</td>
                                    <td>
                                        <a href="{{route('teams-fixtures',$item['home'])}}">{{($item['home_team']->name)}}</a>
                                    </td>
                                    <td>{{$item['result']}}</td>
                                    <td>
                                        <a href="{{route('teams-fixtures',$item['away'])}}">{{($item['away_team']->name)}}</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="col-12">
                <h4>Away Fixtures</h4>
            </div>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Date</th>
                        <th>Home</th>
                        <th>Time</th>
                        <th>Away</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($away_fixtures) && $away_fixtures != null)
                        @foreach($away_fixtures as $key => $item)
                            @if(strpos($item['result'], ':') !== false)
                                <tr class="text-center">
                                    <td>{{$key+1}}</td>
                                    <td>{{\Carbon\Carbon::parse($item['date'])->format('D d M')}}</td>
                                    <td>
                                        <a href="{{route('teams-fixtures',$item['home'])}}">{{($item['home_team']->name)}}</a>
                                    </td>
                                    <td>{{$item['result']}}</td>
                                    <td>
                                        <a href="{{route('teams-fixtures',$item['away'])}}">{{($item['away_team']->name)}}</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
