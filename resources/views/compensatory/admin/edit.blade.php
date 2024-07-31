
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('compensations.update', $item->id)}}" id="edit_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" id="date" type="text" name="date" value="{{$item->date_format}}">
                                <div class="text-danger text-left field-error" id="label_edit_date"></div>
                            </div>
                        </div>
                         <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Application Status<span class="required-label">*</span></label>
                                <select class="chosen-select" id="status" name="status">
                                    <option value="">Select Status</option>
                                    <option @if($item->status == "Approved") selected @endif value="Approved">Approved</option>
                                    <option @if($item->status == "Rejected") selected @endif value="Rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-6">
                                <div class="form-group form-check">
                                    <input type="radio" class="form-check-input" id="half_day" name="session" value="Half Day" {{$item->session == 'Half Day'?'checked':''}}><label class="col-form-labelform-check-label" for="half_day">Half Day</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-check">
                                    <input type="radio" class="form-check-input" id="full_day" name="session" value="Full Day" {{$item->session == 'Full Day'?'checked':''}}><label class="col-form-labelform-check-label" for="full_day">Full Day</label>
                                </div>
                            </div>
                            <div class="text-danger text-left field-error" id="label_edit_session"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Reason<span class="required-label">*</span></label>
                        <textarea rows="5" class="form-control" placeholder="Enter your reason here" name="reason">{!! strip_tags($item->reason) !!}</textarea>
                        <div class="text-danger text-left field-error" id="label_edit_reason"></div>
                    </div>
                    <div class="form-group">
                        <label>Remarks </label>
                        <textarea rows="5" class="form-control" placeholder="Enter your remarks here" name="remarks">{!! strip_tags($item->reason_for_rejection) !!}</textarea>
                    </div>
                    <div class="submit-section text-right">
                        <button class="btn btn-primary update-details">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

