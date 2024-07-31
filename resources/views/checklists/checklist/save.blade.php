<div id="save_list" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Mark as Complete</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('saveChecklist')}}" id="save_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Select Date <span class="required-label">*</span></label>
                                <input class="form-control datepicker" type="text" id="daterangechecklist" name="datepicker" value="{{ date('d/m/Y')}}">
                                <div class="text-danger text-left field-error" id="label_datepicker"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Note </label>
                                <input class="form-control" type="text" name="note" id="note">
                                <div class="text-danger text-left field-error" id="label_note"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="save_id" value="" name="save_id"> 
                </form>
                
                    <div class="submit-section mt20">
                        <button class="btn btn-primary add-to-report">Done</button>
                    </div>
            </div>
        </div>
    </div>
</div>