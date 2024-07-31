<div id="add_item_category" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Checklist</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('checklists.store')}}" id="add_form_list" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="title" id="title">
                                <div class="text-danger text-left field-error" id="label_list_title"></div>
                            </div>
                        </div>
                    </div>
                    <div class="append-new">
                        <div class="row" style="padding-top:10px;">
                            <div class="col-sm-11">
                                <div class="input-group">
                                    <input type="text" class="form-control" type="text" name="items[]" id="item">
                                    <span class="input-group-btn"><button type="button" class="btn btn-primary ">&nbsp;&nbsp;</button></span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-success btn-circle btn-sm add-btn" type="button"><i class="ri-add-line"></i>

                            </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="item_id" value="" name="item_id"> 
                </form>
                
                    <div class="submit-section mt20">
                        <button class="btn btn-primary create-item-list">Add</button>
                    </div>
            </div>
        </div>
    </div>
</div>