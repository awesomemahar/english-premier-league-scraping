@extends('layouts.app')

@section('title', 'Results')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Results</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <h3>Premier League Results</h3>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
            @if(isset($headers) && $headers != null)
                @foreach($headers as $head)
                    <th scope="col">{{$head}}</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($results) && $results != null)
            @foreach($results as $result)
                <tr class="text-center">
                    <td>{{\Carbon\Carbon::parse($result['date'])->format('D d M')}}</td>
                    <td>
                        <a href="{{route('teams-results',$result['home'])}}">{{ucwords($result['home_team']->name)}}</a>
                    </td>
                    <td>{{$result['time_result']}}</td>
                    <td>
                        <a href="{{route('teams-results',$result['away'])}}">{{ucwords($result['away_team']->name)}}</a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
