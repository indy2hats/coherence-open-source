<div id="edit_asset_vendor_modal" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Asset Vendor</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('asset-vendors.update', $assetVendor->id)}}" id="edit_asset_vendor_form" method="post" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name <span class="required-label">*</span></label>
                            <input class="form-control" value="{{$assetVendor->name}}" type="text" name="name" id="name">
                            <div class="text-danger text-left field-error" id="label_name"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Phone <span class="required-label"></span></label>
                            <input class="form-control" value="{{$assetVendor->phone}}" type="text" name="phone" id="phone">
                            <div class="text-danger text-left field-error" id="label_phone"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email <span class="required-label"></span></label>
                            <input class="form-control" value="{{$assetVendor->email}}" type="text" name="email" id="email">
                            <div class="text-danger text-left field-error" id="label_email"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-focus select-focus focused">
                            <label>Status </label>
                            <select class="chosen-select" id="status" name="status">
                                <option value="active" {{$assetVendor->status == 'active' ? 'selected' : ''}}>Active</option>
                                <option value="inactive" {{$assetVendor->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label>Notes <span class="required-label"></span></label>
                        <textarea class="form-control" rows="4" name="description">{{ $assetVendor->description}}</textarea>
                        <div class="text-danger text-left field-error" id="label_description"></div>
                    </div>
                </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-asset-vendor">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>