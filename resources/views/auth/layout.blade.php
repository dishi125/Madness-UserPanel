
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Madeness Mart</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('public/images/madness_fevicon.png') }}">
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"> -->
    <link href="{{url('public/plugins/toastr/css/toastr.min.css')}}" rel="stylesheet">
    <link href="{{url('public/css/style.css')}}" rel="stylesheet">
    <link href="{{ url('public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ url('public/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/plugins/jquery-asColorPicker-master/css/asColorPicker.css') }}" rel="stylesheet">
    <link href="{{ url('public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/plugins/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ url('public/css/jquery.filer.css') }}" rel="stylesheet">
    <link href="{{ url('public/css/jquery.filer-dragdropbox-theme.css') }}" rel="stylesheet">
</head>

<body class="h-100">

<!--*******************
    Preloader start
********************-->
<div id="preloader">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
        </svg>
    </div>
</div>
<!--*******************
    Preloader end
********************-->





<div class="login-form-bg h-100">
    <div class="container h-100">
        @yield('content')
    </div>
</div>




<!--**********************************
    Scripts
***********************************-->
<script src="{{ url('public/js/common.min.js') }}"></script>
<script src="{{ url('public/js/custom.min.js') }}"></script>
<script src="{{ url('public/js/settings.js') }}"></script>
<script src="{{ url('public/js/gleek.js') }}"></script>
<script src="{{ url('public/js/styleSwitcher.js') }}"></script>
<script src="{{ url('public/plugins/toastr/js/toastr.min.js') }}"></script>
<script src="{{ url('public/plugins/toastr/js/toastr.init.js') }}"></script>

<script src="{{ url('public/plugins/moment/moment.js') }}"></script>
<script src="{{ url('public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ url('public/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="{{ url('public/plugins/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ url('public/plugins/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ url('public/plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script src="{{ url('public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ url('public/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ url('public/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ url('public/js/plugins-init/form-pickers-init.js') }}"></script>
<script src="{{ url('public/js/jquery.filer.min.js') }}" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<!-- Auth JS start -->
<script type="text/javascript">
$('#LoginForm').on('submit', function (e) {
    $("#email-error").html("");
    $("#password-error").html("");
    var thi = $(this);
    $('#loginSubmit').find('.loadericonfa').show();
    $('#loginSubmit').prop('disabled',true);
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        type: 'POST',
        url: "{{ route('postlogin') }}",
        data: formData,
        success: function (res) {
            if(res.status == 'failed'){
                $('#loginSubmit').find('.loadericonfa').hide();
                $('#loginSubmit').prop('disabled',false);
                if (res.errors.email) {
                    $('#email-error').show().text(res.errors.email);
                } else {
                    $('#email-error').hide();
                }

                if (res.errors.password) {
                    $('#password-error').show().text(res.errors.password);
                } else {
                    $('#password-error').hide();
                }
            }

            if(res.status == 200){
                $('#loginSubmit').prop('disabled',false);
                toastr.success("You have Successfully loggedin",'Success',{timeOut: 5000});
                location.href ="{{ url('dashboard') }}";
            }

            if(res.status == 400){
                $('#loginSubmit').find('.loadericonfa').hide();
                $('#loginSubmit').prop('disabled',false);
                toastr.error("Oppes! You have entered invalid credentials",'Error',{timeOut: 5000});
            }
        },
        error: function (data) {
            $('#loginSubmit').find('.loadericonfa').hide();
            $('#loginSubmit').prop('disabled',false);
            toastr.error("Please try again",'Error',{timeOut: 5000});
        }
    });
});

$('#profile_pic').change(function(){
    $('#profile_pic-error').html("");
    var file = this.files[0];
    var fileType = file["type"];
    var validImageTypes = ["image/jpeg", "image/png", "image/jpg"];
    if ($.inArray(fileType, validImageTypes) < 0) {
        $('#profile_pic-error').html("Please provide a Valid Extension Image(e.g: .jpg .png)");
        var default_image = "{{ url('public/images/default_avatar.jpg') }}";
        $('#profilepic_image_show').attr('src', default_image);
    }
    else {
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#profilepic_image_show').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    }
});

$('body').on('click', '#registerSubmit', function () {
    $(this).prop('disabled',true);
    $(this).find('.loadericonfa').show();
    var btn = $(this);

    $('#profile_pic-error').html("");

    var validate_registerform = validateRegisterForm();

    if(validate_registerform == true){
        var formData = new FormData($("#registerForm")[0]);

        $("#registerForm").submit();
{{--        $.ajax({--}}
{{--            type: 'POST',--}}
{{--            url: "{{ route('register.payment') }}",--}}
{{--            data: formData,--}}
{{--            success: function (res) {--}}
{{--                if(res.status == 'failed'){--}}
{{--                    $('#registerSubmit').find('.loadericonfa').hide();--}}
{{--                    $('#registerSubmit').prop('disabled',false);--}}
{{--                }--}}

{{--                if(res.status == 200){--}}
{{--                    $('#registerSubmit').prop('disabled',false);--}}
{{--                    // toastr.success("You have Successfully loggedin",'Success',{timeOut: 5000});--}}
{{--                    location.href ="{{ url('dashboard') }}";--}}
{{--                }--}}
{{--            },--}}
{{--            error: function (data) {--}}
{{--                $('#registerSubmit').find('.loadericonfa').hide();--}}
{{--                $('#registerSubmit').prop('disabled',false);--}}
{{--                toastr.error("Please try again",'Error',{timeOut: 5000});--}}
{{--            }--}}
{{--        });--}}
    }else{
        $(btn).prop('disabled',false);
        $(btn).find('.loadericonfa').hide();
    }
});

function validateRegisterForm() {
    $("#registerForm").validate({
        rules: {
            profile_pic : {
                accept: "image/jpeg, image/png, image/jpg",
            },
            first_name : {
                required: true,
            },
            last_name: {
                required: true,
            },
            mobile_no: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
            dob: {
                required: true,
            },
        },

        messages : {
            profile_pic: {
                accept: "Please provide a Valid Extension Image(e.g: .jpg .png)"
            },
            first_name: {
                required: "Please provide a First Name",
            },
            last_name: {
                required: "Please provide a Last Name",
            },
            mobile_no: {
                required: "Please provide a Mobile No",
                number: "Please provide only numbers in  Mobile No",
                minlength: "Please provide a 10 digits Mobile No",
                maxlength: "Please provide a 10 digits Mobile No",
            },
            email: {
                required: "Please provide a E-mail address",
                email: "Please provide a valid E-mail address",
            },
            password: {
                required: "Please provide a Password",
            },
            dob: {
                required: "Please provide a Date of Birth",
            },
        }
    });

    var valid = true;
    if (!$("#registerForm").valid()) {
        valid = false;
    }

    return valid;
}

$('#forgetPasswordForm').on('submit', function (e) {
    $("#email-error").html("");
    var thi = $(this);
    $('#forgetPasswordSubmit').find('.loadericonfa').show();
    $('#forgetPasswordSubmit').prop('disabled',true);
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        type: 'POST',
        url: "{{ route('forget.password.post') }}",
        data: formData,
        success: function (res) {
            if(res.status == 'failed'){
                $('#forgetPasswordSubmit').find('.loadericonfa').hide();
                $('#forgetPasswordSubmit').prop('disabled',false);
                if (res.errors.email) {
                    $('#email-error').show().text(res.errors.email);
                } else {
                    $('#email-error').hide();
                }
            }

            if(res.status == 200){
                $('#forgetPasswordSubmit').find('.loadericonfa').hide();
                $('#forgetPasswordSubmit').prop('disabled',false);
                toastr.success("Reset Password Link Sent",'Success',{timeOut: 5000});
                {{--location.href ="{{ url('dashboard') }}";--}}
            }
        },
        error: function (data) {
            $('#forgetPasswordSubmit').find('.loadericonfa').hide();
            $('#forgetPasswordSubmit').prop('disabled',false);
            toastr.error("Please try again",'Error',{timeOut: 5000});
        }
    });
});

$('#resetPasswordForm').on('submit', function (e) {
    $("#email-error").html("");
    $("#password-error").html("");
    $("#password_confirm-error").html("");
    var thi = $(this);
    $('#resetPasswordSubmit').find('.loadericonfa').show();
    $('#resetPasswordSubmit').prop('disabled',true);
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        type: 'POST',
        url: "{{ route('reset.password.post') }}",
        data: formData,
        success: function (res) {
            if(res.status == 'failed'){
                $('#resetPasswordSubmit').find('.loadericonfa').hide();
                $('#resetPasswordSubmit').prop('disabled',false);
                if (res.errors.email) {
                    $('#email-error').show().text(res.errors.email);
                } else {
                    $('#email-error').hide();
                }

                if (res.errors.password) {
                    $('#password-error').show().text(res.errors.password);
                } else {
                    $('#password-error').hide();
                }

                if (res.errors.password_confirm) {
                    $('#password_confirm-error').show().text(res.errors.password_confirm);
                } else {
                    $('#password_confirm-error').hide();
                }
            }

            if(res.status == 200){
                $('#resetPasswordSubmit').find('.loadericonfa').hide();
                $('#resetPasswordSubmit').prop('disabled',false);
                toastr.success("Your password has been changed",'Success',{timeOut: 5000});
                location.href ="{{ route('login') }}";
            }

            if(res.status == 400){
                $('#resetPasswordSubmit').find('.loadericonfa').hide();
                $('#resetPasswordSubmit').prop('disabled',false);
                toastr.error("Invalid token",'Error',{timeOut: 5000});
            }
        },
        error: function (data) {
            $('#forgetPasswordSubmit').find('.loadericonfa').hide();
            $('#forgetPasswordSubmit').prop('disabled',false);
            toastr.error("Please try again",'Error',{timeOut: 5000});
        }
    });
});
</script>
<!-- Auth JS end -->

</body>
</html>





