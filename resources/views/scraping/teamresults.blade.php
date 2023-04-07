@extends('layouts.app')

@section('title', 'Home Away Results')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('results')}}">Results</a></li>
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
                <h4>Home Results</h4>
            </div>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Date</th>
                        <th>Home</th>
                        <th>Result</th>
                        <th>Away</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($home_results) && $home_results != null)
                        @foreach($home_results as $key => $item)
                            <tr class="text-center">
                                <td>{{$key+1}}</td>
                                <td>{{\Carbon\Carbon::parse($item['date'])->format('D d M')}}</td>
                                <td>
                                    <a href="{{route('teams-results',$item['home'])}}">{{ucwords($item['home_team']->name)}}</a>
                                </td>
                                <td>{{$item['result']}}</td>
                                <td>
                                    <a href="{{route('teams-results',$item['away'])}}">{{ucwords($item['away_team']->name)}}</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="col-12">
                <h4>Away results</h4>
            </div>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Date</th>
                        <th>Home</th>
                        <th>Result</th>
                        <th>Away</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($away_results) && $away_results != null)
                        @foreach($away_results as $key => $item)
                            <tr class="text-center">
                                <td>{{$key+1}}</td>
                                <td>{{\Carbon\Carbon::parse($item['date'])->format('D d M')}}</td>
                                <td>
                                    <a href="{{route('teams-results',$item['home'])}}">{{ucwords($item['home_team']->name)}}</a>
                                </td>
                                <td>{{$item['result']}}</td>
                                <td>
                                    <a href="{{route('teams-results',$item['away'])}}">{{ucwords($item['away_team']->name)}}</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
