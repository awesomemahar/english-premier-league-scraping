@extends('layouts.app')

@section('title', 'Leaders Dashboard')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Leaders Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
            <th>#</th>
            <th>Name</th>
            <th>Total Predictions</th>
            <th>Total Points</th>
            <th>Number of Strikes</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $key =>$user)
            <tr class="text-center">
                <td>{{$key+1}}</td>
                <td><a href="{{route('user-predictions',$user['username'])}}">{{$user->name}}</a></td>
                <td>{{$user->total_predictions}}</td>
                <td>{{$user->total_points}}</td>
                <td>{{$user->strikes}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
