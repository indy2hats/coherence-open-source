<!DOCTYPE html>
<html>
    @include('partials.header')
    <body class="gray-bg w-100">
<div class="middle-box text-center loginscreen animated fadeInDown" style="margin-top: 0px; width: 350px;">
    <div>
        
        <div class="logo-name" style="font-size: 0px;"> <img src="{{asset(Helper::getCompanyLogo())}}" alt="" width="100px;"> </div>
        <h2>Change your password to continue</h2>
        <form onsubmit="return false" class="m-t" role="form" action="{{ route('changePasswordAction') }}" method="POST" id="change-password-form">
            <div class="form-group">
                <label class="col-form-label" style="text-align: left;width: 100%;">Current Password <span class="required-label">*</span></label>
                <input class="form-control" type="password" name="current_password" id="old_password_id">
                <div class="text-danger text-left field-error" id="label_current_password"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label" style="text-align: left;width: 100%;">New Password <span class="required-label">*</span></label>
                <input class="form-control" type="password" name="password" id="password_id">
                <p id="passwordHelpBlock" class="help-text text-muted">
                        Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                </p>
                <div class="text-danger text-left field-error" id="label_password"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label" style="text-align: left;width: 100%;">Confirm Password <span class="required-label">*</span></label>
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation_id">
                <div class="text-danger text-left field-error" id="label_password_confirmation"></div>

            </div>
            {{ csrf_field() }}
            <button class="btn btn-success block full-width m-b change-password">Change Password</button>
        </form>
            </div>
        </div>

<script src="{{ asset('js/all.js') }}"></script>
@include('partials.scripts-include')

<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.4.2/sweet-alert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.4.2/sweet-alert.css">

<script type="text/javascript">
    $(document).on('click', '.change-password', function() {
        $('.field-error').html('');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
            type: 'POST',
            url: $('#change-password-form').attr('action'),
            data: $('#change-password-form').serialize(),
            success: function(response) {
                if(response.flag){
                    /*swal({ 
                      title: "Sucess",
                       text: response.message,
                        type: "success" 
                      },
                      function () {
                          
                    });*/

                    window.location.href = '/dashboard';
                }
                else if(response.flag == false){
                    $('.overlay').remove();
                    swal({ 
                      title: "Error",
                       text: response.message,
                        type: "error" 
                      },
                      function () {
                        //window.location.href = '/logout';
                    });
                }
            },
            error: function(error) {
                $('.overlay').remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });
</script>
    </body>
</html>