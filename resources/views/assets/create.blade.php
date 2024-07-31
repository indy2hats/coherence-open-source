<div id="create_asset" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Asset</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('assets.store')}}" id="add_asset_form" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Type <span class="required-label">*</span></label>
                                <select class="chosen-select asset-type" id="asset_type_id" name="asset_type_id">
                                    <option value="">Select Type</option>
                                    @foreach ($assetTypes as $assetType)
                                    <option value="{{$assetType->id}}">{{$assetType->name}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_asset_type_id"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="asset_name" id="asset_name_id">
                                <div class="text-danger text-left field-error" id="label_asset_name"></div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Purchased Date <span class="required-label"></span></label>
                                <input class="form-control datetimepicker" type="text" id="purchased_date" name="purchased_date">
                                <div class="text-danger text-left field-error" id="label_purchased_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Warranty Until<span class="required-label"></span></label>
                                <input class="form-control datetimepicker" type="text" name="warranty" id="warranty_id">
                                <div class="text-danger text-left field-error" id="label_warranty"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Serial Number</label>
                                <input class="form-control" type="text" name="serial_number" id="serial_number_id">
                                <div class="text-danger text-left field-error" id="label_serial_number"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Vendor <span class="required-label"></span></label>
                                <select class="chosen-select" id="vendor" name="vendor">
                                    <option value="">Select Vendor</option>
                                    @foreach ($assetVendors as $assetVendor)
                                    <option value="{{$assetVendor->id}}">{{$assetVendor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                    <label>Asset Value </label>
                                    <input class="form-control" type="text" name="asset_value" id="asset_value">
                                    <div class="text-danger text-left field-error" id="label_asset_value"></div>  
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Attach Files <span class="required-label"></span></label>
                                <input class="form-control" type="file" name="files[]" multiple />
                                <div class="text-danger text-left field-error" id="label_files"></div>
                            </div>
                        </div>
                    </div>

                    <div class="asset-type-attributes"></div>
            
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Other details/Notes: <span class="required-label"></span></label>
                                <textarea class="form-control" rows="4" name="configuration"></textarea>
                                <div class="text-danger text-left field-error" id="label_configuration"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                    <label>Status </label>
                                    <select class="chosen-select asset-status" id="status_id" name="status">
                                        <option value="allocated">Allocated</option>
                                        <option value="non_allocated" selected>Non Allocated</option>
                                        <option value="inactive">In Active</option>
                                    </select>
                            </div>
                        </div>
                    </div>

                    <div class="row assign-user" style="display:none">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>User <span class="required-label">*</span></label>
                                <select class="chosen-select" id="user_id" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Assigned Date <span class="required-label"></span></label>
                                <input class="form-control datetimepicker" type="text" name="assigned_date"  value="">
                            </div>
                        </div>
                    </div>

                    <div class="row"><div class="col-sm-6"><div class="text-danger text-left field-error" id="label_status"></div></div></div>


                    <div class="row">
                        <div class="submit-section">
                            <button type="button" class="btn btn-primary submit-btn create-asset">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>