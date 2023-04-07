@extends('layouts.frontend.app')

@section('content')
    <section class="affilliate-section about-bitbetio">
        <div class="overlay pt-120 pb-120">
            <div class="container pb-120">
                <div class="row justify-content-center py-5 px-5 pt-80 pb-120">
                    <div class="col-lg-8">
                        <div class="section-header text-center">
                            <h4 class="title">Reset Password</h4>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="single-input">
                                    <label for="logemail">Email</label>
                                    <input type="email" class="@error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus
                                           placeholder="Email Address">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="cmn-btn">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
