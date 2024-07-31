<!DOCTYPE html>
<html>
    @include('partials.header')
    <body class="gray-bg w-100">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <div class="logo-name">    <img src="{{asset(Helper::getCompanyLogo())}}" alt="" width="70%">        </div>
                <div class="text-danger text-left">
                    {{ $errors->first('auth_token') }}
                </div>
                <form class="m-t" role="form" action="{{ route('login') }}" method="POST">
                     @include('partials.error-message')
                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required="true">
                        <div class="text-danger text-left">
                            {{ $errors->first('email') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" required="true">
                        <div class="text-danger text-left">
                            {{ $errors->first('password') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <input id="box1" type="checkbox" name="remember_me"/>
                        <label for="box1">Remember Me</label>
                    </div>
                    {{ csrf_field() }}                   
                    <button type="submit" class="btn btn-success block full-width m-b">Login</button>

                    @if (Route::has('password.request'))
                        <div class="form-group">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        </div>
                    @endif                 
                </form>
            </div>
        </div>
        <script src="{{ asset('js/all.js') }}"></script>
        @include('partials.scripts-include')
        <script>
            @if(session('status'))
            toastr.success("{{ session('status') }}");
            @endif
        </script>
    </body>
</html>

