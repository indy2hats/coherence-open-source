<!-- Edit Holidays Modal -->
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Holiday</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('manage-holidays.update',$holiday->id)}}" id="edit_holiday_form" method="POST" autocomplete="off">
                    @csrf
                  <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date<span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" type="text" name="holiday_date" value="{{$holiday->edit_date}}">
                                <div class="text-danger text-left field-error" id="edit_label_holiday_date"></div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Holiday Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="holiday_name" id="edit_holiday_name" value="{{$holiday->holiday_name}}">
                                <div class="text-danger text-left field-error" id="edit_label_holiday_name"></div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-holiday">Update</button>
                </div>
        </div>
    </div>
</div>

<!-- /Edit Holidays Modal -->