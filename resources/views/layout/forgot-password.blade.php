<!DOCTYPE html>
<html>
    @include('partials.header')
    <body class="gray-bg w-100">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <div class="logo-name">    <img src="{{asset(Helper::getCompanyLogo())}}" alt="" width="70%">        </div>
                <div class="text-gray-600">
                    {{ __('Forgot your password?') }}
                </div>
                <div class="text-gray-600 m-b-xs">
                    {{ __('Let us know your email address and we will email you a password reset link.') }}
                </div>
        
                @if ($errors->any())                               
                    <ul class="list-unstyled list-inside text-danger ">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>       
                @endif
        
                <form class="p-h-xs" method="POST" action="{{ route('password.email') }}" >
                    @csrf
                    <div class="form-group">
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email')}}" placeholder="{{ __('Email')}}" required autofocus />
                    </div>
        
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn btn-success block full-width m-b">Email Password Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>





       

        
   
