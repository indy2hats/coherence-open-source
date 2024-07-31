<!-- Add Holiday Modal -->
<div id="add_holiday" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Holiday</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('manage-holidays.store')}}" id="add_holiday_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" type="text" name="holiday_date">
                                <div class="text-danger text-left field-error" id="label_holiday_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Holiday Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="holiday_name" id="holiday_name_id">
                                <div class="text-danger text-left field-error" id="label_holiday_name"></div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="submit-section text-right">
                    <button class="btn btn-primary create-holiday">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Holiday Modal -->