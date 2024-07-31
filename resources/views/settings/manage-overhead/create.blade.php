<!-- Add Overhead Modal -->
<div id="add_overhead_type" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Overhead</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('fixed-overhead.store')}}" id="add_overhead_type_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Type <span class="required-label">*</span></label>
                                <input type="text" placeholder="Enter the type" class="form-control" name="type" id="type_id"/>
                                <div class="text-danger text-left field-error" id="label_type"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Amount <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="amount" id="amount_id">
                                <div class="text-danger text-left field-error" id="label_amount"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Description <span class="required-label">*</span></label>
                                <textarea rows="4" class="form-control summernote" placeholder="Enter your message here" name="description" ></textarea>
                                <div class="text-danger text-left field-error" id="label_description"></div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="submit-section">
                    <button class="btn btn-primary create-overhead-type">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Overhead Modal -->