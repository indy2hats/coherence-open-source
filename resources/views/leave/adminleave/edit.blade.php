<!-- Add Holiday Modal -->

    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Leave</h4>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" action="{{route('apply-leave.update', $leave->id)}}" id="edit_leave_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <input type ="hidden" value="{{$leave->user_id}}" name="user_id">
                    <div class="row">
                        <div class="form-group">
                        <span class="label label-warning">Remaining {{ $balance['casual'] }} Casual</span>
                        <span class="label label-primary">Remaining {{ $balance['medical'] }} Medical</span>
                        <span class="label label-danger">{{ $balance['lop'] }} LOP Taken</span> 
                        @if(array_key_exists('paternity',$balance))
                        <span class="label label-primary">Remaining {{ $balance['paternity'] }} Paternity</span>
                        @endif
                        <span class="label label-info">{{ $balance['compensatory'] }} Compensatory Taken</span>
                        <span class="label label-plain"> Total {{ $balance['total_taken_leaves'] }} Leaves Taken</span>    
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">From <span class="required-label">*</span></label>
                                <input value="{{$leave->from_date_format}}" class="form-control datetimepicker" id="fromdate" type="text" name="from_date">
                                <div class="text-danger text-left field-error" id="label_from_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">To <span class="required-label">*</span></label>
                                <input value="{{$leave->to_date_format}}" class="form-control datetimepicker" id="todate" type="text" name="to_date">
                                <div class="text-danger text-left field-error" id="label_to_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Leave Type<span class="required-label">*</span></label>
                                <select class="chosen-select" id="type" name="type">
                                    <option value="">Select Type</option>
                                    <option @if($leave->type == "Casual") selected @endif value="Casual">Casual</option>
                                    <option @if($leave->type == "Medical") selected @endif value="Medical">Medical</option>
                                    <option @if($leave->type == "Compensatory") selected @endif value="Compensatory">Compensatory off</option>
                                    <option @if($leave->type == "LOP") selected @endif value="LOP">LOP</option>
                                    <option @if($leave->type == "Paternity") selected @endif value="Paternity">Paternity</option>
                                    <option @if($leave->type == "Maternity") selected @endif value="Maternity">Maternity</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_type"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Leave Status<span class="required-label">*</span></label>
                                <select class="chosen-select" id="status" name="status">
                                    <option value="">Select Status</option>
                                    <option @if($leave->status == "Approved") selected @endif value="Approved">Approved</option>
                                    <option @if($leave->status == "Cancelled") selected @endif value="Cancelled">Cancelled</option>
                                    <option @if($leave->status == "Rejected") selected @endif value="Rejected">Rejected</option>
                                    <option @if($leave->status == "Waiting") selected @endif value="Waiting">Waiting</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_type"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input @if($leave->lop == "Yes") checked @endif type="checkbox" class="form-check-input" id="lop" name="lop" value="LOP"><label class="col-form-labelform-check-label" for="lop">LOP</label>
                            </div>
                        </div> --}}
                        <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input @if($leave->session == "First Half") checked @endif type="radio" class="form-check-input" id="first_half" name="session" value="First Half"><label class="col-form-labelform-check-label" for="first_half">First Half</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input @if($leave->session == "Second Half") checked @endif type="radio" class="form-check-input" id="second_half" name="session" value="Second Half"><label class="col-form-labelform-check-label" for="second_half">Second Half</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-check">
                                <input @if($leave->session == "Full Day") checked @endif type="radio" class="form-check-input" id="full_day" name="session" value="Full Day"><label class="col-form-labelform-check-label" for="full_day">Full Day</label>
                            </div>
                        </div>
                        <div class="text-danger text-left field-error" id="label_session"></div>
                    </div>
                    <div class="form-group">
                        <label>Reason<span class="required-label">*</span></label>
                        <textarea rows="5" class="form-control" placeholder="Enter your reason here" name="reason">{{ strip_tags($leave->reason) }}</textarea>
                        <div class="text-danger text-left field-error" id="label_reason"></div>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea rows="5" class="form-control" placeholder="Enter your remarks here" name="reason_for_rejection">{!! $leave->reason_for_rejection !!}</textarea>
                        <div class="text-danger text-left field-error" id="label_reason_for_rejection"></div>
                    </div>
                    <div class="submit-section text-right">
                        <button class="btn btn-primary update-leave">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- /Add Holiday Modal -->