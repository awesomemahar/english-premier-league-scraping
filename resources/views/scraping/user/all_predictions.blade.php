@extends('layouts.app')

@section('title', 'User Prediction')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Predictions</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <h3>All Predictions</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5>Open Predictions</h5>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
        <tr class="text-center">
            <th>Date</th>
            <th>Home Team</th>
            <th>Away Team</th>
            <th>Time</th>
            <th>User Prediction</th>
            <th>User Name</th>
        </tr>
        </tr>
        </thead>
        <tbody>
        @if(isset($user_predictions) && $user_predictions != null)
            @foreach($user_predictions as $key => $value)
                @if($value->fixture != null)
                    <tr class="text-center">
                        <td>{{\Carbon\Carbon::parse($value->fixture->date)->format('D d M')}}</td>
                        <td>{{$value->fixture->home_team->name}}</td>
                        <td>{{$value->fixture->away_team->name}}</td>
                        <td>{{$value->fixture->time_result}}</td>
                        <td>{{$value->home_score}} - {{$value->away_score}}</td>
                        <td>
                            <a href="{{route('user-predictions',$value->user->username)}}">{{$value->user->name}}</a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        </tbody>
    </table>
    <br>
    <div class="row">
        <div class="col-12">
            <h5>Predictions History</h5>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
        <tr class="text-center">
            <th>Date</th>
            <th>Home Team</th>
            <th>Away Team</th>
            <th>Actual Result</th>
            <th>User Prediction</th>
            <th>User Name</th>
            <th>Score</th>
            <th>Result</th>
            <th>Under/Over</th>
            <th>Exception Points</th>
            <th>Total</th>
            <th>Strike</th>
        </tr>
        </tr>
        </thead>
        <tbody>
        @if(isset($user_predictions_history) && $user_predictions_history != null)
            @foreach($user_predictions_history as $key => $value)
                @if($value->fixture != null)
                    <tr class="text-center">
                        <td>{{\Carbon\Carbon::parse($value->fixture->date)->format('D d M')}}</td>
                        <td>{{$value->fixture->home_team->name}}</td>
                        <td>{{$value->fixture->away_team->name}}</td>
                        <td>{{$value->fixture->time_result}}</td>
                        <td>{{$value->home_score}} - {{$value->away_score}}</td>
                        <td>
                            <a href="{{route('user-predictions',$value->user->username)}}">{{$value->user->name}}</a>
                        </td>
                        @if($value->prediction_result != null)
                            <td>{{$value->prediction_result->correct_score}}</td>
                            <td>{{$value->prediction_result->correct_result}}</td>
                            <td>{{$value->prediction_result->correct_under_over}}</td>
                            <td>{{$value->prediction_result->exception_points}}</td>
                            <td>
                                {{$value->prediction_result->correct_score + $value->prediction_result->correct_result + $value->prediction_result->correct_under_over + $value->prediction_result->exception_points}}</td>
                            <td>
                                @if($value->prediction_result->strike == 1)
                                    Yes
                                @else
                                    No
                                @endif
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
