<div id="add_item" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Item</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('checklists.store')}}" id="add_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row append-new-items">
                         <div class="row" style="padding-top:10px;">
                            <div class="col-sm-11">
                                <div class="input-group">
                                    <input type="text" class="form-control" type="text" name="title" id="title">
                                    <span class="input-group-btn"><button type="button" class="btn btn-primary ">&nbsp;&nbsp;</button></span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-success btn-circle btn-sm add-btn-items" type="button"><i class="ri-add-line"></i>

                            </button>
                            </div>

                        </div>
                        <div class="text-danger text-left field-error" id="label_title"></div>
                    </div>
                    <input type="hidden" id="item_id" value="" name="item_id"> 
                </form>
                
                    <div class="row submit-section mt20">
                        <button class="btn btn-primary create-item">Add</button>
                    </div>
            </div>
        </div>
    </div>
</div>