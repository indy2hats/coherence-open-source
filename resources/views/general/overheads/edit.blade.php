<!-- Edit Overhead Modal -->
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Overhead</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('overheads.update', $overhead->id)}}" id="edit_overhead_form" method="POST" autocomplete="off">
                    @csrf
                    <input name="_method" type="hidden" value="PUT">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <div class="form-group" id="data_5">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control" name="date" id="date" value="{{$overhead->date}}" readonly>
                                    </div>
                                </div>
                                <div class="text-danger text-left field-error" id="edit_label_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Type <span class="required-label">*</span></label>
                                <input type="text" placeholder="Enter the type" class="typeahead_2 form-control" name="type" value="{{$overhead->type}}"/>
                                <div class="text-danger text-left field-error" id="edit_label_type"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Amount <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="amount" value="{{$overhead->amount}}">
                                <div class="text-danger text-left field-error" id="edit_label_amount"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Description <span class="required-label">*</span></label>
                                <textarea rows="4" class="form-control summernote" placeholder="Enter your message here" name="description" >{{$overhead->description}}</textarea>
                                <div class="text-danger text-left field-error" id="edit_label_description"></div>                                
                            </div>
                        </div>
                    </div>
                </form>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-overhead">Update</button>
                </div>
        </div>
    </div>
</div>

<!-- /Edit Overhead Modal -->