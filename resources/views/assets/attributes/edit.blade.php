<style>
    .custom-bordered-row {
        border: 1px solid #ccc; /* Adjust the color and thickness as needed */
        padding: 10px; /* Optional: Add padding for spacing */
    }

    .custom-swal{
        /* z-index: 3000!important; */
    }
    
</style>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Attribute</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="row custom-bordered-row">
                        <div class="col-sm-3 col-md-3"><label>Name <span class="required-label">*</span></label></div>
                        <div class="col-sm-9 col-md-9">
                            <div class="col-sm-6 col-md-6 editable" data-attribute-id="{{ $attribute->id }}" data-attribute-name="{{ $attribute->name }}">{{ $attribute->name}}</div>
                            <div class="col-sm-3 col-md-3"><a href="#" class="edit-name-attribute" data-target="#edit_name_attribute" data-id="{{ $attribute->id }}"
                                    data-tooltip="tooltip" title="Edit" data-toggle="modal"> <i
                                        class="ri-pencil-line m-r-5"></i></a> </div>
                        </div>
                    </div>
                    <input type="hidden" id="attribute-id" value="{{ $attribute->id }}">
                    <div class="text-danger text-left field-error" id="label_edit_name"></div>
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">
                    <div class="row custom-bordered-row">
                        <div class="col-sm-3 col-md-3"><label>Values <span class="required-label">*</span></label></div>
                        <div class="col-sm-9 col-md-9 attribute-value-list">
                            <?php $count = count($attribute->attribute_values); ?>
                            @foreach ($attribute->attribute_values as $index => $attribute_value) 
                                <div class="row" id="row-value-{{$attribute_value->id}}"> 
                                    <div class="col-sm-6 col-md-6 editable" data-attribute-id="{{ $attribute_value->id }}" data-attribute-value="{{ $attribute_value->value }}">{{ $attribute_value->value}}</div>
                                    <div class="col-sm-3 col-md-3">
                                        @if($count > 1)
                                        <a class="dropdown-item delete-attribute-value" href="#" data-toggle="modal"
                                            data-target="#delete_attribute_value" data-id="{{ $attribute_value->id }}" data-tooltip="tooltip"
                                            data-placement="top"><i class="ri-delete-bin-line m-r-5"></i></a>
                                        @endif
                                        @if($count == $index+1)
                                            <a href="#" class="add-attribute-value" data-target="#add_attribute_value" data-id="{{ $attribute->id }}"
                                                data-tooltip="tooltip" data-toggle="modal"> 
                                                <i class="ri-add-line m-r-5"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-danger text-left field-error" id="label_name"></div>
                </div>
            </div>
    

        </div>
    </div>

    <script src="{{ asset('js/resources/assets/attributes/script-min.js') }}"></script>
<!-- attributes Modal -->