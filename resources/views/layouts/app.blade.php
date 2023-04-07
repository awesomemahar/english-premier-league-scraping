<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>@yield('title') | What2Bet</title>
</head>
<body>
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{route('welcome')}}">What2bet.online</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a @if(isset($active) && $active == "Fixtures") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('fixtures')}}">Fixtures</a>
                <a @if(isset($active) && $active == "Results") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('results')}}">Results</a>
                <a @if(isset($active) && $active == "Home Away") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('home-away')}}">Home Away</a>
                <a @if(isset($active) && $active == "Top Scorers") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('top-scorers')}}">Top Scorers</a>
                <a @if(isset($active) && $active == "Teams") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('teams')}}">Teams</a>
                <a @if(isset($active) && $active == "Players") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('players')}}">Players</a>
                <a @if(isset($active) && $active == "Leagues") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('leagues')}}">Leagues</a>
                <a @if(isset($active) && $active == "Users") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('users')}}">Users</a>
                <a @if(isset($active) && $active == "Predictions") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('all-predictions')}}">Predictions</a>
                <a @if(isset($active) && $active == "Leaders Dashboard") class="nav-link active" @else class="nav-link"
                   @endif href="{{route('leaders-dashboard')}}">Leaders Dashboard</a>
            </div>
        </div>
    </nav>

</div>
<div class="main">
    <div class="container-fluid mt-5">
        @yield('content')
    </div>
</div>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
@yield('js')
<!-- Option 2: jQuery, Popper.js, and Bootstrap JS --
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

</body>
</html>
