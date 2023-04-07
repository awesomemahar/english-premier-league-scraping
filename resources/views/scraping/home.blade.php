@extends('layouts.app')

@section('title', 'Home Away')
@section('content')
    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <div class="col-12">
                <h3>Home Table</h3>
            </div>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        @if(isset($table_header) && $table_header != null)
                            @foreach($table_header as $header)
                                <th scope="col">{{$header}}</th>
                            @endforeach
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($home) && $home != null)
                        @foreach($home as $items)
                            <tr class="text-center">
                                @foreach($items as $key => $item)
                                    <td>{{$item}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="col-12">
                <h3>Away Table</h3>

            </div>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        @if(isset($table_header) && $table_header != null)
                            @foreach($table_header as $header)
                                <th scope="col">{{$header}}</th>
                            @endforeach
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($away) && $away != null)
                        @foreach($away as $items)
                            <tr class="text-center">
                                @foreach($items as $key => $item)
                                    <td>{{$item}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 mt-5">
            <div class="col-12">
                <h3>Relative Home/Away Table</h3>

            </div>
            <div class="col-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Team</th>
                        <th>GPh</th>
                        <th>GPa</th>
                        <th>Pts</th>
                        <th>PPG Home</th>
                        <th>PPG Away</th>
                        <th></th>
                        <th>Home Advantage</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($relative) && $relative != null)
                        @foreach($relative as $items)
                            <tr class="text-center">
                                @foreach($items as $key => $item)
                                    <td>{{$item}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
