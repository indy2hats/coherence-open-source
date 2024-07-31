<div id="create_credential" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Credential</h4>
            </div>
            <div class="modal-body">
                <div class="lead-assign">
                    <form onsubmit="return false;" id="create_credential_id" action="{{route('credentials.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group form-focus select-focus focused">
                            <label>Project Name<span class="required-label">*</span></label>
                            <select class="chosen-select" id="add_project_name" name="project">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                <option value="{{$project->id}}">{{$project->project_name}}
                                </option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_project"></div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Type <span class="required-label">*</span></label>
                            <input class="form-control" type="text" name="type">
                            <div class="text-danger text-left field-error" id="label_type"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Username <span class="required-label">*</span></label>
                            <input class="form-control" type="text" name="username">
                            <div class="text-danger text-left field-error" id="label_username"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Password <span class="required-label">*</span></label>
                            <input class="form-control" type="password" name="password">
                            <div class="text-danger text-left field-error" id="label_password"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Content <span class="required-label">*</span></label>
                            <textarea rows="4" class="form-control summernote" placeholder="Enter your content here" name="content" id="content"></textarea>
                            <div class="text-danger text-left field-error" id="label_content"></div>
                        </div>
                        <div class="submit-section text-right">
                            <a type="button" class="btn btn-primary create-new">Add</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>