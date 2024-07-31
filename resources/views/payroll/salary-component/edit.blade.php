
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Status Type</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('salary-component.update', $component->id)}}" id="edit_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input value="{{$component->title}}" class="form-control" type="text" name="edit_title" id="edit_title">
                                <div class="text-danger text-left field-error" id="label_edit_title"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-md-8">
                            <div class="form-group">
                                <label>Type <span class="required-label">*</span></label>
                                <select class="form-control chosen-select" id="edit_type" name="edit_type">
                                    @foreach ($type as $option)
                                    <option value="{{ $option }}" {{ $option==$component->type ? 'selected':''}}>{{ ucwords($option)}}</option>                                        
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_edit_type"></div>
                            </div>
                        </div>
                        <div class="col-sm-4"> 
                            <label>Status <span class="required-label">*</span></label>
                            <div class="form-group ">         
                                <input type="checkbox" class="form-check-input" name="edit_status" id="edit_status"  {{ $component->status==1 ? 'checked' : ''  }}>
                                <label class="col-form-labelform-check-label" for="edit_status">Active</label>                    
                            </div>
                        </div>
                    </div>                           
                    <div class="submit-section mt20">
                        <button class="btn btn-primary update-type" type="submit">Update</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>