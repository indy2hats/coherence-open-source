<div id="edit_asset_type_modal" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Asset Type</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('asset-types.update', $assetType->id)}}" id="edit_asset_type_form" method="post" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name <span class="required-label">*</span></label>
                            <input class="form-control" value="{{$assetType->name}}" type="text" name="name" id="name">
                            <div class="text-danger text-left field-error" id="label_name"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Rate of Depreciation <span class="required-label">*</span></label>
                            <div class="custom-field-wrap-addon">
                                <div class="input-group" style="width:calc(100% - 5px)">
                                <input class="form-control" value="{{$assetType->depreciation_rate}}" type="text" name="depreciation_rate" id="depreciation_rate">
                                <span class="input-group-addon">%</span>    
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-focus select-focus focused">
                            <label>Status </label>
                            <select class="chosen-select" id="status" name="status">
                                <option value="active" {{$assetType->status == 'active' ? 'selected' : ''}}>Active</option>
                                <option value="inactive" {{$assetType->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label title="Add the attributes for this asset type. These attribute values will be captured when assets are added.">Attributes</label>
                            <select class="chosen-select" name="attributes[]" id="attributes" multiple>
                                @foreach ($attributes as $key => $attribute) 
                                
                                        <option value="{{ $attribute->id }}" {{in_array($attribute->id,$assetTypeAttributes) ? 'selected' : ''}}>{{ $attribute->name}}</option>
                        
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_attributes"></div>
                        </div>
                    </div>
                 </div>

                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-asset-type">Save</button>
                </div>
            </form>
        </div>

      
    </div>
</div>