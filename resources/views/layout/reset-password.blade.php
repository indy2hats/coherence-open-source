<!DOCTYPE html>
<html>
    @include('partials.header')
    <body class="gray-bg w-100">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <div class="logo-name">    <img src="{{asset(Helper::getCompanyLogo())}}" alt="" width="70%">        </div>
                <div class="text-sm text-gray-600">
                    {{ __('Reset your password') }}
                </div>
       
                @if ($errors->any())          
                    <ul class="mt-5 p-h-xs list-unstyled list-inside text-danger ">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>              
                @endif
               
                <form class="p-h-xs" method="POST" action="{{ route('password.update') }}">
                    @csrf
 
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email', request('email')??'')}}" required placeholder="{{ __('Email')}}" autofocus />
                    </div>

                    <div class="form-group">
                        <input id="password" class="form-control" type="password" name="password" placeholder="{{__('Password')}}" required />
                    </div>
        
                    <div class="form-group">        
                        <input id="password_confirmation" class="form-control"
                                            type="password"
                                            name="password_confirmation" placeholder="{{__('Confirm Password')}}" required />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn btn-success block full-width m-b">{{ __('Reset Password') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>   