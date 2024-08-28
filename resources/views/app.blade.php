<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link href="{{ asset(\App\Helpers\Helper::getCompanyLogo()) }}" rel="icon">
        <style>
        #wrapper{
            animation: wrap-animate 1s; 
        }
        @keyframes wrap-animate {
            0% {
                opacity: 0;
                transform: translateY(25px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/flexmasonry/dist/flexmasonry.css">
    
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
        <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    
        <link href="{{ asset('css/plugins/summernote/summernote.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
        <link href="{{ asset('css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
        <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style-custom.css') }}" rel="stylesheet">
    
        <link href="{{ asset('css/plugins.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        {{-- TODO::remove unwanted things --}}
        {{-- <script src="https://unpkg.com/flexmasonry/dist/flexmasonry.js"></script>
        <script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
        <script src="{{ asset('js/resources/partials/script.min.js') }}"></script>
        <script src="{{ asset('js/plugins/summer-note-removes-script-tag/note.js') }}"></script>


        @if (Auth::user())
        @if(Auth::user()->wish_notify == 1)
        <script type="text/javascript">
        
                if($('#wishmodal').length){

                    $(window).on('load',function(){
                        $('#wishmodal').modal('show');
                        setTimeout(function() {
                            if($('.modal-content-type').attr('data-id') == 'Wish'){
                                $('#wishmodal').fireworks();
                            }
                            if($('.modal-content-type').attr('data-file_type') == 'Video'){
                                $("#video_btn").trigger('click');
                            }
                        });
                    });

                    $('#wishmodal').on('hidden.bs.modal', function (e) {
                        e.preventDefault();
                        var editUrl = '/user-wish-notified';
                        $.ajax({
                            type: 'GET',
                            url: editUrl,
                            data: {},
                            success: function(data) {
                            }
                        });
                    });

                }
        </script>
        @endif
        @if(!empty(Helper::showDailyStatusReportPage()))
            @if(Auth::user()->dsr_notify == 1)
                <script type="text/javascript">
                    eodmodal();
                </script>
            @endif
        @endif
        @endif --}}
    </body>
</html>