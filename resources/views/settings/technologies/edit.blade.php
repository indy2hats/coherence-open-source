<div id="edit_technology_modal" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Technology</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('project-technologies.update', $technology->id)}}" id="edit_technology_form" method="post" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name <span class="required-label">*</span></label>
                            <input class="form-control" value="{{$technology->name}}" type="text" name="name" id="name">
                            <div class="text-danger text-left field-error" id="label_name"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-focus select-focus focused">
                            <label>Status </label>
                            <select class="chosen-select" id="status" name="status">
                                <option value="active" {{$technology->status == 'active' ? 'selected' : ''}}>Active</option>
                                <option value="inactive" {{$technology->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-technology">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>