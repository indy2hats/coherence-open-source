    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Credential</h4>
            </div>
            <div class="modal-body">
                <div class="lead-assign">
                    <form onsubmit="return false;" id="edit_credential_id" action="{{route('credentials.update', $item->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="{{$item->id}}">
                        <div class="form-group form-focus select-focus focused">
                            <label>Project Name<span class="required-label">*</span></label>
                            <select class="chosen-select" id="edit_project_name" name="project">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                <option @if($project->id == $item->project_id) selected @endif value="{{$project->id}}">{{$project->project_name}}
                                </option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_edit_project"></div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Type <span class="required-label">*</span></label>
                            <input class="form-control" type="text" name="type" value="{{$item->type}}">
                            <div class="text-danger text-left field-error" id="label_edit_type"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Username <span class="required-label">*</span></label>
                            <input class="form-control" type="text" name="username" value="{{$item->username}}">
                            <div class="text-danger text-left field-error" id="label_edit_username"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Password <span class="required-label">*</span></label>
                            <input class="form-control" type="password" name="password" value="{{$password}}">
                            <div class="text-danger text-left field-error" id="label_edit_password"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Content <span class="required-label">*</span></label>
                            <textarea rows="4" class="form-control summernote" placeholder="Enter your content here" name="content" id="content">{{$item->value}}</textarea>
                            <div class="text-danger text-left field-error" id="label_edit_content"></div>
                        </div>
                        <div class="submit-section text-right">
                            <a type="button" class="btn btn-primary update-data">Update</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>