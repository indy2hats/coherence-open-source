<!-- Add Holiday Modal -->
<div id="apply_leave" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Assign Leave</h4>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" action="{{route('apply-leave.store')}}" id="apply_leave_form" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" value="" id="user_id">
                    <input type="hidden" name="status" value="Approved" id="status">
                    <div class="row">
                        <div class="form-group">
                        <span class="label label-warning">Remaining {{ $balance['casual'] }} Casual</span>
                        <span class="label label-primary">Remaining {{ $balance['medical'] }} Medical</span>
                        <span class="label label-info">{{ $balance['compensatory'] }} Compensatory Taken</span> 
                        <span class="label label-default">{{ $balance['compensatory_available']-$balance['compensatory'] }} Compensatory Available</span> 
                        <span class="label label-danger">{{ $balance['lop'] }} LOP Taken</span> 
                        @if(array_key_exists('paternity',$balance))
                        <span class="label label-primary">Remaining {{ $balance['paternity'] }} Paternity</span>
                        @endif
                        <span class="label label-plain "> Total {{ $balance['total_taken_leaves'] }} Leaves Taken</span>    
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">From <span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" id="fromdate" type="text" name="from_date">
                                <div class="text-danger text-left field-error" id="label_from_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">To <span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" id="todate" type="text" name="to_date">
                                <div class="text-danger text-left field-error" id="label_to_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Type Of Leave<span class="required-label">*</span></label>
                                <select class="chosen-select" id="type" name="type">
                                    <option value="">Select type</option>
                                    <option value="Casual">Casual</option>
                                    <option value="Medical">Medical</option>
                                    <option value="Compensatory">Compensatory off</option>
                                    <option value="LOP">LOP</option>
                                    <option value="Paternity">Paternity</option>
                                    <option value="Maternity">Maternity</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_type"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="lop" name="lop" value="LOP"><label class="col-form-labelform-check-label" for="lop">LOP</label>
                            </div>
                        </div> --}}
                        <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input type="radio" class="form-check-input" id="first_half" name="session" value="First Half"><label class="col-form-labelform-check-label" for="first_half">First Half</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input type="radio" class="form-check-input" id="second_half" name="session" value="Second Half"><label class="col-form-labelform-check-label" for="second_half">Second Half</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input type="radio" class="form-check-input" id="full_day" checked name="session" value="Full Day"><label class="col-form-labelform-check-label" for="full_day">Full Day</label>
                            </div>
                        </div>
                        <div class="text-danger text-left field-error" id="label_session"></div>
                    </div>
                    <div class="form-group">
                        <label>Reason<span class="required-label">*</span></label>
                        <textarea rows="5" class="form-control" placeholder="Enter your reason here" name="reason"></textarea>
                        <div class="text-danger text-left field-error" id="label_reason"></div>
                    </div>
                    <div class="submit-section text-right">
                        <button class="btn btn-primary apply-leave">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Holiday Modal -->