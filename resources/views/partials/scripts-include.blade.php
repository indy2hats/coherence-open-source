

<script src="https://unpkg.com/flexmasonry/dist/flexmasonry.js"></script>
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
@endif