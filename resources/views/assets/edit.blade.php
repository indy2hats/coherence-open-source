<div id="edit_asset_modal" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Asset</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('assets.update', $asset->id)}}" id="edit_asset_form" method="post" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Type <span class="required-label">*</span></label>
                            <select class="chosen-select asset-type" id="asset_type_id" name="asset_type_id">
                                <option value="">Select Type</option>
                                @foreach ($assetTypes as $assetType)
                                <option value="{{$assetType->id}}" {{$assetType->id == $asset->asset_type_id ? 'selected' : ''}}>{{$assetType->name}}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_asset_type_id"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Asset Name <span class="required-label">*</span></label>
                            <input class="form-control" value="{{$asset->name}}" type="text" name="asset_name" id="asset_name_id">
                            <div class="text-danger text-left field-error" id="label_asset_name"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Purchased Date <span class="required-label"></span></label>
                            <input class="form-control datetimepicker" value="{{ $asset->purchased_date ? \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y') : '' }}" type="text" name="purchased_date">
                            <div class="text-danger text-left field-error" id="label_purchased_date"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Warranty Until<span class="required-label"></span></label>
                            <input class="form-control datetimepicker" type="text" name="warranty" value="{{ $asset->warranty ? \Carbon\Carbon::parse($asset->warranty)->format('d/m/Y') : '' }}"  id="warranty_id">
                            <div class="text-danger text-left field-error" id="label_warranty"></div>
                        </div>
                    </div>

                    
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Serial Number</label>
                            <input class="form-control" type="text" name="serial_number" value="{{ $asset->serial_number }}"  id="serial_number_id">
                            <div class="text-danger text-left field-error" id="label_serial_number"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Vendor <span class="required-label"></span></label>
                            <select class="chosen-select" id="vendor" name="vendor">
                                <option value="">Select Vendor</option>
                                @foreach ($assetVendors as $assetVendor)
                                <option value="{{$assetVendor->id}}" {{$assetVendor->id == $asset->vendor_id ? 'selected' : ''}}>{{$assetVendor->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                    <label>Asset Value </label>
                                    <input class="form-control" type="text" name="asset_value" value="{{ $asset->value }}"  id="asset_value">
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
                    
                @if($assetTypeAttributes)
                <div class="row asset-type-attributes">
                @foreach($assetTypeAttributes as $assetTypeAttribute)
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ $assetTypeAttribute->attribute->name }}</label>
                            <select class="chosen-select" name="attributeValues[]" id="attributeValues">
                                <option value="">Select Value</option>
                                @foreach ($assetTypeAttribute->attribute->attribute_values as $key => $attributeValue) 
                                
                                        <option value="{{ $attributeValue->id }}" {{in_array($attributeValue->id,$assetAttributeValues) ? 'selected' : ''}}>{{ $attributeValue->value }}</option>
                        
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_attributes"></div>
                        </div>
                    </div>
                @endforeach
                </div>
                @endif        
               
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label>Other details/Notes: <span class="required-label"></span></label>
                        <textarea class="form-control" rows="4" name="configuration">{{ !empty($asset->configuration) ? $asset->configuration : '' }}</textarea>
                        <div class="text-danger text-left field-error" id="label_configuration"></div>
                    </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-focus select-focus focused">
                                <label>Status </label>
                                <select class="chosen-select asset-status" id="status_id" name="status">
                                    <option value="allocated" {{$asset->status == 'allocated' ? 'selected' : ''}}>Allocated</option>
                                    <option value="non_allocated" {{$asset->status == 'non_allocated' ? 'selected' : ''}}>Non Allocated</option>
                                    <option value="inactive" {{$asset->status == 'inactive' ? 'selected' : ''}}>In Active</option>
                                </select>
                        </div>
                    </div>
                <div>
                
                </div>

                </div>
                <div class="row assign-user" style="display:{{ $asset->status == 'allocated' ? 'block' : 'none'}}">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>User <span class="required-label">*</span></label>
                            <select class="chosen-select" id="user_id" name="user_id">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}" {{ ($asset->allocatedUser ?? null) && $asset->allocatedUser->user_id == $user->id ? 'selected' : '' }}>{{$user->full_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Assigned Date <span class="required-label"></span></label>
                            <input class="form-control datetimepicker" type="text" name="assigned_date"  value="{{ $asset->allocatedUser ? \Carbon\Carbon::parse($asset->allocatedUser->assigned_date)->format('d/m/Y') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-sm-6"><div class="text-danger text-left field-error" id="label_status"></div></div></div>


                @if($asset->documents->count() > 0 )
                <h4 style="margin-bottom:10px;">Asset Documents</h4>
                <div class="row">
                    @foreach($asset->documents as $document)
                    <div class="col-sm-3 col-md-3 doc-repeat{{$document->id}}">
                        <a target="_blank" href="{{ asset('storage/'.$document->path)}}">
                            <p
                                style="font-size:14px;text-align: center; border:1px solid #eee;padding:10px;    word-break: break-word;">
                                <i class="fa fa-file clear" aria-hidden="true"
                                    style="font-size:25px;"></i> {{explode('/', $document->path)[2]}}
                            </p>
                        </a>

                                <a class="delete-asset-doc text-danger" style="z-index:99999;position: absolute;top: 5px;right: 25px;" href="#" data-toggle="modal"
            data-target="#delete_asset_document" data-id="{{ $document->id }}" data-tooltip="tooltip"
            data-placement="top" title="Delete"><i
                                class="ri-delete-bin-line" aria-hidden="true"></i></a>
                    </div>
                    @endforeach
                </div>
                @endif
                        

                
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-asset">Save</button>
                </div>
                
            </form>
        </div>
    </div>
</div>