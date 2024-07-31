<!DOCTYPE html>
<html>
    @include('partials.header')
    <body class="gray-bg w-100">
<div class="text-center loginscreen animated fadeInDown">
    <div class="logo-name" style="font-size: 0px;"> <a href="{{url('/')}}"><img src="{{asset(Helper::getCompanyLogo())}}" alt="" width="100px;"></a> </div>
        
     <input type="hidden" id="user_id" value="{{$userId}}">   
     <input type="hidden" id="leave_id" value="{{$leave->id}}">   
</div>

<div class="row animated fadeInUp">
    <div class="col-md-6 col-md-offset-3">
        <h2>Leave Details</h2>
        <div class="media-body " style="display:block; background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">Applied By:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->users->full_name}}
            </p>   
        </div>
        <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">From date:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->from_date_format}}
            </p>   
        </div>
        <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">To date:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->to_date_format}}
            </p>   
        </div>
        <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">No: of days:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->total_leave_days}}
            </p>   
        </div>
        <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">Leave Type:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->type}}
            </p>   
        </div>
        <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">Leave Session:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->session}}
            </p>   
        </div>
        <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">Reason:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {!! $leave->reason !!}
            </p>   
        </div>
        @if($leave->status == "Waiting")
            <div class="media-body btn-div" style="display:block;background: #fff;
            padding: 10px 20px 10px 20px;    width: 100%;">
            <p class="m-b-xs" style="display: inline-block;
        padding-left:5px;">   
            <button type="button" id="" class="btn btn-outline btn-primary accept-leave"  data-toggle="modal" data-target="#accept_leave">Accept Leave</button>
            <button type="button" class="btn btn-outline btn-danger reject-leave"  data-toggle="modal" data-target="#reject_reason">Reject Leave</button>
            </p>   
            </div>
        @else
            <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">Status:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->status}}
            </p>   
        </div>
        <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">Action Taken By:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->user_approved->full_name}}
            </p>   
        </div>
        @endif
        @if($leave->status == "Rejected")
            <div class="media-body " style="display:block;background: #fff;
        padding: 10px 20px 10px 20px;    width: 100%;">                
            <strong style="min-width:100px;display:inline-block;">Remarks:</strong>
            <p class="m-b-xs" style="display: inline-block;
    padding-left:5px;">
                {{$leave->reason_for_rejection}}
            </p>   
        </div>
        @endif
    </div>
</div>
<script src="{{ asset('js/all.js') }}"></script>
@include('partials.scripts-include')
@include('leave.adminleave.accept')
@include('leave.adminleave.reject')
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.4.2/sweet-alert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.4.2/sweet-alert.css">

<script type="text/javascript">

    $(document).on('click', '#accept_leave .continue-btn', function() {
            var acceptLeaveId = $('#leave_id').val();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#accept_leave').modal('hide');
            $.ajax({
                method: 'post',
                url: '{{route('acceptLeave')}}',
                data: {'leaveId':acceptLeaveId},
                success: function(res) {
                    $(".overlay").remove();
                    $(".btn-div").remove();
                    toastr.success(res.message,'Approved');
                }
            });
        });

    $(document).on('click', '.reject-leave', function() {
            var  rejectLeaveId = $('#leave_id').val();
            $('#reject_reason #reject_leave_id').val( rejectLeaveId);
        });

    $(document).on('click', '#reject_reason .continue-btn', function() {
            var rejectLeaveId = $('#reject_reason #reject_leave_id').val();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                method: 'post',
                url: '{{route('rejectLeave')}}',
                data: $('#leave_rejection_form').serialize(),
                success: function(res) {
                    $(".overlay").remove();
                    $('#reject_reason').modal('hide');
                    $('#reject_reason').trigger('reset');
                    toastr.success(res.message,'Approved');
                    $(".btn-div").remove();
                },
                error: function(error) {
                    $(".overlay").remove();
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