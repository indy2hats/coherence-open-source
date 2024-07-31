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
            <p>Check Your Email </p>
            <p>We have sent you an email with a code.</p>
            <form class="form-horizontal" method="post" action="/verify">
                {{csrf_field()}}
                @if(!empty($error))
                     <div class="text text-danger m-b">
                        <strong>{{$error}}</strong>
                    </div>
                @endisset             
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="email_token" class="form-control" placeholder="Enter Code" required="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success block full-width m-b">
                            Submit
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Login another user?') }}
                    </a>
                </div>
            </form>
        </div>
    </body>
</html>