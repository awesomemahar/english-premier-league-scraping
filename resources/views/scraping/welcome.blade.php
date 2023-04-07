@extends('layouts.app')

@section('title', 'Home')
@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="text-center">Dashboard</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-04 m-2">
            <div class="card" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <h6 class="card-subtitle mb-2 text-muted">@if(isset($users))<a
                            href="{{route('users')}}">{{$users}}</a>@endif</h6>
                </div>
            </div>
        </div>
        <div class="col-04 m-2">
            <div class="card" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">Leagues</h5>
                    <h6 class="card-subtitle mb-2 text-muted">@if(isset($leagues))<a
                            href="{{route('leagues')}}">{{$leagues}}</a>@endif</h6>
                </div>
            </div>
        </div>
        <div class="col-04 m-2">
            <div class="card" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">Teams</h5>
                    <h6 class="card-subtitle mb-2 text-muted">@if(isset($teams))<a
                            href="{{route('teams')}}">{{$teams}}</a>@endif</h6>
                </div>
            </div>
        </div>
        <div class="col-04 m-2">
            <div class="card" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">Players</h5>
                    <h6 class="card-subtitle mb-2 text-muted">@if(isset($players))<a
                            href="{{route('players')}}">{{$players}}</a>@endif</h6>
                </div>
            </div>
        </div>
        <div class="col-04 m-2">
            <div class="card" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">Fixtures</h5>
                    <h6 class="card-subtitle mb-2 text-muted">@if(isset($fixtures))<a
                            href="{{route('fixtures')}}">{{$fixtures}}</a>@endif</h6>
                </div>
            </div>
        </div>
        <div class="col-04 m-2">
            <div class="card" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">All Predictions</h5>
                    <h6 class="card-subtitle mb-2 text-muted">@if(isset($predictions))<a
                            href="{{route('all-predictions')}}">{{$predictions}}</a>@endif</h6>
                </div>
            </div>
        </div>

    </div>
@endsection
