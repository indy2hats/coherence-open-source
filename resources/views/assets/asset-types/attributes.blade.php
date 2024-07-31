@if($assetTypeAttributes)
<div class="row">
@foreach($assetTypeAttributes as $assetTypeAttribute)
    <div class="col-sm-6">
        <div class="form-group">
            <label>{{ $assetTypeAttribute->attribute->name }}</label>
            <select class="chosen-select" name="attributeValues[]" id="attributeValues">
                <option value="">Select Value</option>
                @foreach ($assetTypeAttribute->attribute->attribute_values as $key => $attributeValue) 
                
                        <option value="{{ $attributeValue->id }}" >{{ $attributeValue->value }}</option>
        
                @endforeach
            </select>
            <div class="text-danger text-left field-error" id="label_attributes"></div>
        </div>
    </div>
@endforeach
</div>
@endif                  