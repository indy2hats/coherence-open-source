
<div id="create_new" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('compensations.store')}}" id="add_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" id="date" type="text" name="date" required>
                                <div class="text-danger text-left field-error" id="label_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-6">
                                <div class="form-group form-check">
                                    <input type="radio" class="form-check-input" id="half_day" name="session" value="Half Day"><label class="col-form-labelform-check-label" for="half_day">Half Day</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-check">
                                    <input type="radio" class="form-check-input" id="full_day" checked name="session" value="Full Day"><label class="col-form-labelform-check-label" for="full_day">Full Day</label>
                                </div>
                            </div>
                            <div class="text-danger text-left field-error" id="label_session"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Reason<span class="required-label">*</span></label>
                        <textarea rows="5" class="form-control" placeholder="Enter your reason here" name="reason"></textarea>
                        <div class="text-danger text-left field-error" id="label_reason"></div>
                    </div>
                    <div class="submit-section text-right">
                        <button class="btn btn-primary create-new" type="submit">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
