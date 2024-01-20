@extends('auth.layout')

@section('content')
    <div class="row justify-content-center h-100">
        <div class="col-xl-6">
            <div class="form-input-content">
                <div class="card login-form mb-3 mt-3">
                    <div class="card-body pt-5">
                        @if(\Illuminate\Support\Facades\Session::has('message'))
                            <div class="alert alert-warning">
                                {{ \Illuminate\Support\Facades\Session::get('message') }}
                                @php
                                    \Illuminate\Support\Facades\Session::forget('message');
                                @endphp
                            </div>
                        @endif

                        <h4>Madeness Mart</h4>

                        <form method="post" action="{{ route('register.payment') }}" class="mt-5 mb-5" id="registerForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-form-label" for="profilePic">Profile Image
                                </label>
                                <input type="file" class="form-control-file" id="profile_pic" onchange="" name="profile_pic">
                                <label id="profile_pic-error" class="error invalid-feedback animated fadeInDown" for="profile_pic"></label>
                                <img src="{{ url('public/images/default_avatar.jpg') }}" class="" id="profilepic_image_show" height="50px" width="50px" style="margin-top: 5px">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="first_name">First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control input-flat" id="first_name" name="first_name" placeholder="">
                                <label id="first_name-error" class="error invalid-feedback animated fadeInDown" for="first_name"></label>
                            </div>
                            <div class="form-group ">
                                <label class="col-form-label" for="last_name">Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control input-flat" id="last_name" name="last_name" placeholder="">
                                <label id="last_name-error" class="error invalid-feedback animated fadeInDown" for="last_name"></label>
                            </div>
                            <div class="form-group ">
                                <label class="col-form-label" for="mobile_no">Mobile No <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control input-flat" id="mobile_no" name="mobile_no" placeholder="">
                                <label id="mobile_no-error" class="error invalid-feedback animated fadeInDown" for="mobile_no"></label>
                            </div>
                            <div class="form-group ">
                                <label class="col-form-label" for="email">E-mail <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control input-flat" id="email" name="email" placeholder="">
                                <label id="email-error" class="error invalid-feedback animated fadeInDown" for="email"></label>
                            </div>
                            <div class="form-group ">
                                <label class="col-form-label" for="password">Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control input-flat" id="password" name="password" placeholder="">
                                <label id="password-error" class="error invalid-feedback animated fadeInDown" for="password"></label>
                            </div>
                            <div class="form-group ">
                                <label class="col-form-label" for="gender">Gender
                                </label>
                                <div>
                                    <label class="radio-inline mr-3"><input type="radio" name="gender" value="1" checked> Female</label>
                                    <label class="radio-inline mr-3"><input type="radio" name="gender" value="2"> Male</label>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="col-form-label" for="dob">Date of Birth <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control custom_date_picker" id="dob" name="dob" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" data-date-end-date="0d"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
                                    <label id="dob-error" class="error invalid-feedback animated fadeInDown" for="dob"></label>
                                </div>
                            </div>
                            <p class="text-info">Membersip Fee: <i class="fa fa-inr" aria-hidden="true"></i> {{ $Settings->premium_user_membership_fee }} </p>
                            <button class="btn login-form__btn submit w-100" type="submit" id="registerSubmit">Sign Up <i class="fa fa-spinner fa-spin loadericonfa" style="display:none;"></i></button>
                        </form>
                        <p class="mt-5 login-form__footer">Already have account? <a href="{{ route('login') }}" class="text-primary">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
