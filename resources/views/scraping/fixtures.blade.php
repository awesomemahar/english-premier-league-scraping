@extends('layouts.app')

@section('title', 'Fixtures')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Fixtures</li>
                </ol>
            </nav>
        </div>
        <div class="col-6">
            <h3>Premier League Fixtures</h3>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('48-fixtures') }}">Matchday Fixtures</a>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr class="text-center">
                @if (isset($headers) && $headers != null)
                    @foreach ($headers as $head)
                        <th scope="col">{{ $head }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @if (isset($fixtures) && $fixtures != null)
                @foreach ($fixtures as $fixture)
                    @if (strpos($fixture['time_result'], ':') !== false)
                        <tr class="text-center">
                            <td>{{ \Carbon\Carbon::parse($fixture['date'])->format('D d M') }}</td>
                            <td>
                                <a
                                    href="{{ route('teams-fixtures', $fixture['home']) }}">{{ ucwords($fixture['home_team']->name) }}</a>
                            </td>
                            <td>{{ $fixture['time_result'] }}</td>
                            <td>
                                <a
                                    href="{{ route('teams-fixtures', $fixture['away']) }}">{{ ucwords($fixture['away_team']->name) }}</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif


        </tbody>
    </table>
@endsection
