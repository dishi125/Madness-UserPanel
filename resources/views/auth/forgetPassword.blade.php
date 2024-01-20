@extends('auth.layout')

@section('content')
    <div class="row justify-content-center h-100">
        <div class="col-xl-6">
            <div class="form-input-content">
                <div class="card login-form mb-0">
                    <div class="card-body pt-5">
                        @if(\Illuminate\Support\Facades\Session::has('message'))
                            <div class="alert alert-success">
                                {{ \Illuminate\Support\Facades\Session::get('message') }}
                                @php
                                    \Illuminate\Support\Facades\Session::forget('message');
                                @endphp
                            </div>
                        @endif

                        <h4>Madeness Mart</h4>

                        <form method="post" class="mt-5 mb-5 login-input" id="forgetPasswordForm">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                                <div id="email-error" class="invalid-feedback animated fadeInDown" style="display: none;"></div>
                            </div>
                            <button class="btn login-form__btn submit w-100" type="submit" id="forgetPasswordSubmit">Send Password Reset Link <i class="fa fa-spinner fa-spin loadericonfa" style="display:none;"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
