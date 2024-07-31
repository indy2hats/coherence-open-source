<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Share Credentials</h4>
        </div>
        <div class="modal-body">
            <form id="share_credential_id" action="{{route('shareCredentials')}}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{$item->id}}">
                <div class="form-group form-focus select-focus focused">
                    <label>Project Name<span class="required-label">*</span></label>
                    <select class="chosen-select" id="select_project_name" name="project_id" disabled>
                        <option value="">Select Project</option>
                        @foreach ($projects as $project)
                        <option @if($project->id == $item->project_id) selected @endif value="{{$project->id}}">{{$project->project_name}}
                        </option>
                        @endforeach
                    </select>
                    <div class="text-danger text-left field-error" id="label_project_id"></div>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Type <span class="required-label">*</span></label>
                    <input class="form-control" type="text" name="type" value="{{$item->type}}" readonly>
                    <div class="text-danger text-left field-error" id="label_title"></div>
                </div>

                <div class="form-group form-focus select-focus focused">
                    <label>Assign To</label>
                    <select class="chosen-select" id="credential_assigned_users" name="credential_assigned_users[]" multiple>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}" {{ (in_array($user->id, $credentialUsers)) ? 'selected': '' }}>{{$user->full_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="submit-section text-right">
                    <a type="button" class="btn btn-primary share-Data">Share</a>
                </div>
            </form>
        </div>
    </div>
</div>