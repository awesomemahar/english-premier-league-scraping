@extends('layouts.app')

@section('content')
    <section class="affilliate-section about-bitbetio">
        <div class="overlay pt-120 pb-120">
            <div class="container">
                <div class="row justify-content-center py-5 px-5 pt-80 pb-120">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header text-white" style="background-color: #571ce0">{{ __('Verify Your Email Address') }}</div>

                            <div class="card-body" style="color: white">
                                @if (session('resent'))
                                    <div class="alert alert-success" role="alert">
                                        {{ __('A fresh verification link has been sent to your email address.') }}
                                    </div>
                                @endif

                                {{ __('Before submitting your prediction, please confirm your email address. Check your email (including junk or spam folders) and click the verification button.') }}
                               <br><br>
                                {{ __('If you haven\'t received the email, please request again by clicking the button below.') }}<br>  <br>
                                <form class="form text-center" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="cmn-btn">{{ __('Click here to request another') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
