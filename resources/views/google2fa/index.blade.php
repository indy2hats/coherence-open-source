<!DOCTYPE html>
<html>
    <head>
        @include('partials.header')
    </head>
    <body class="gray-bg w-100">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div class="logo-name">    
                <img src="{{asset(Helper::getCompanyLogo())}}" alt="" width="70%">        
            </div>

            <p>Two Factor Authentication</p>

            @if($errors->any())
                <p class="text-danger m-t-2">{{$errors->first()}}</p>    
            @endif

            <form class="form-horizontal" method="POST" action="{{ route('2fa') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <p>Please enter the  <strong>OTP</strong> generated on your Authenticator App. Ensure you submit the latest code.</p>

                    <div class="col-md-12">
                        <input id="one_time_password" type="number" class="form-control" name="one_time_password" placeholder="One Time Password" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success block full-width m-b">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    <script src="{{ asset('js/all.js') }}"></script>
    @include('partials.scripts-include')
    </body>
</html>