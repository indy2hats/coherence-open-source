<div id="create_asset_vendor" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Asset Vendor</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('asset-vendors.store')}}" id="add_asset_vendor_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name" id="name">
                                <div class="text-danger text-left field-error" id="label_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Phone <span class="required-label"></span></label>
                                <input class="form-control" type="text" name="phone" id="phone">
                                <div class="text-danger text-left field-error" id="label_phone"></div>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Notes <span class="required-label"></span></label>
                                    <textarea class="form-control" rows="4" name="description"></textarea>
                                    <div class="text-danger text-left field-error" id="label_description"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email <span class="required-label"></span></label>
                                    <input class="form-control" type="text" name="email" id="email">
                                    <div class="text-danger text-left field-error" id="label_email"></div>
                                </div>
                            </div>
                           
                            </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create-asset-vendor">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>