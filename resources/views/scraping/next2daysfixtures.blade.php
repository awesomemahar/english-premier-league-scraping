@extends('layouts.app')

@section('title', 'Matchday Fixtures')
@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('fixtures')}}">Fixtures</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Matchday Fixtures</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <h4>Premier League fixtures for next 48 hours</h4>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr class="text-center">
            @if(isset($headers) && $headers != null)
                @foreach($headers as $head)
                    <th scope="col">{{$head}}</th>
                @endforeach
                <th></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($fixtures) && $fixtures != null)
            @foreach($fixtures as $key => $fixture)
                <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle bg-light"
                    aria-expanded="true">
                    <td colspan="5" class="font-weight-bold">
                        Match {{$key +1}}
                        <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                    </td>
                </tr>
                <tr id="demo1" class="accordian-body collapse show" style="">
                    <td>{{\Carbon\Carbon::parse($fixture['date'])->format('D d M')}}</td>
                    <td>
                        <a href="{{route('teams-fixtures',$fixture['home'])}}">{{ucwords($fixture['home_team']->name)}}</a>
                    </td>
                    <td>{{$fixture['time_result']}}</td>
                    <td>
                        <a href="{{route('teams-fixtures',$fixture['away'])}}">{{ucwords($fixture['away_team']->name)}}</a>
                    </td>
                </tr>
                <tr id="demo1" class="accordian-body collapse show" style="">
                    <form method="POST" action="{{route('save-prediction')}}">
                        @csrf
                        <input hidden name="fixture_id" value="{{$fixture->id}}">
                        <input hidden name="user_id" value="2">
                        <td class="text-right">Predict Score</td>
                        <td><select name="home_score" class="form-control">
                                @for($i = 0; $i<26; $i++)
                                    <option>{{$i}}</option>
                                @endfor
                            </select>
                        </td>
                        <td></td>
                        <td><select name="away_score" class="form-control">
                                @for($i = 0; $i<26; $i++)
                                    <option>{{$i}}</option>
                                @endfor
                            </select></td>
                        <td>
                            <button type="submit" class="btn btn-primary">Save Prediction</button>
                        </td>
                    </form>
                </tr>
                {{--<tr class="text-center">
                    <td>{{\Carbon\Carbon::parse($fixture['date'])->format('D d M')}}</td>
                    <td>
                        <a href="{{route('teams-fixtures',$fixture['home'])}}">{{ucwords($fixture['home_team']->name)}}</a>
                    </td>
                    <td>{{$fixture['time_result']}}</td>
                    <td>
                        <a href="{{route('teams-fixtures',$fixture['away'])}}">{{ucwords($fixture['away_team']->name)}}</a>
                    </td>
                    --}}{{--<td>
                        <a href="{{route('predict-score', $fixture->id)}}"> Predict Score</a>
                        --}}{{----}}{{-- <button type="button" class="btn btn-primary" data-toggle="modal"
                                 data-target-id="{{ $fixture->id }}" data-target="#myModal">
                             Predict Score
                         </button>--}}{{----}}{{--
                    </td>--}}{{--
                </tr>--}}
            @endforeach
        @endif

        </tbody>
    </table>
    <!-- Modal -->
    {{--<div id="myModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Match</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="pass_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>--}}
@endsection

{{--@section('js')
    <script>
        $(document).ready(function () {
            $("#myModal").on("show.bs.modal", function (e) {
                var id = $(e.relatedTarget).data('target-id');
                console.log(id);
                $('#pass_id').val(id);
                $.ajax({
                    type:'POST',
                    url:'/get-fixture',
                    data:'_token = <?php echo csrf_token() ?>',
                    success:function(data) {
                        $("#msg").html(data.msg);
                    }
                });
            });
        });
    </script>
@endsection--}}
