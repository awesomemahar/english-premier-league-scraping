@extends('layouts.app')

@section('title','Home')

@section('content')
    <!-- error-section start -->
    <section class="error-section py-3 py-md-5">
        <div class="overlay py-3 py-md-5">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6">
                        <div class="img-area">
                            <img src="{{asset('siteassets/images/error-illus.png')}}" alt="image">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="section-text">
                            <h2 class="title">Page not found</h2>
                            <p>Oops.. Looks like you got lost :(</p>
                        </div>
                        <div class="btn-border">
                            {{-- <a href="{{route('welcome')}}" class="cmn-btn">Go Back Home</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- error-section end -->
@endsection
