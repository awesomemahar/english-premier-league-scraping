@extends('layouts.app')

@section('title', 'Users')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <h3>All Users</h3>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
        <tr class="text-center">
            <th>#</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Email</th>
        </tr>
        </tr>
        </thead>
        <tbody>
        @if(isset($users) && $users != null)
            @foreach($users as $key => $result)
                <tr class="text-center">
                    <td>{{$key+1}}</td>
                    <td><a href="{{route('user-predictions',$result['username'])}}">{{$result['username']}}</a></td>
                    <td>{{$result['name']}}</td>
                    <td>{{$result['email']}}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
@endsection
