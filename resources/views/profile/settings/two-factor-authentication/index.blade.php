@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="ibox text-center">
                <div class="ibox-title">
                    <h4>Two Factor  Authentication</h4>
                </div>

                @if (\Session::has('status'))
                    <span class="text-default hidden" id="label_status">{{ \Session::get('status')}}</span>
                    <span class="text-default hidden" id="label_message">{{ \Session::get('message')}}</span>                        
                @endif

                <div class="ibox-content text-center">
                @if(!empty($google2faSecret))              
                    <form class="form-horizontal" method="POST" action="{{ route('enableTwoFactorAuthentication') }}" id="twoFactorForm">
                        @csrf
                        <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code {{ $google2faSecret }}</p>
                        <div>{!! $qrImage !!}</div>
                        <p>You must set up your Google Authenticator app before continuing.</p>
                        <p>Please enter the  <strong>OTP</strong> generated on your Authenticator App. <br> Ensure you submit the current one because it refreshes every 30 seconds.</p>

                        <div class="form-group">
                            <label for="otp" class="col-sm-4 control-label">One Time Password</label>
                            <div class="col-sm-4">
                                <input id="one_time_password" type="number" class="form-control" name="otp" placeholder="Please enter your OTP" required autofocus>
                                @error('otp')
                                    <div id="error_one_time_password" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-success m-b">Enable 2 Factor Authentication</button>
                        </div>
                    </form>
                @endif

                <div class="text-center disable-two-factor-outer {{ empty($google2faSecret) ? "show" :"hidden"  }}">
                    <p>You have enabled Two Step Verification for EPMS </p>
                    {{-- <div class="form-group">
                        <a href="#" class="btn btn-w-m btn-danger {{ empty($google2faSecret) ? "disable-two-factor" :""  }}" data-toggle="modal" data-target="#disable2FA">Disable Two Factor Authentication </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('profile.settings.two-factor-authentication.disable')  --}}
<script src="{{ asset('js/resources/profile/settings/script.min.js') }}"></script>
@endsection