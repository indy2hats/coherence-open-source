<div id="assign_leader" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Assign Project Users</h4>
        </div>
            <div class="modal-body">
                <div class="lead-assign m-b">
                    <input type="hidden" value="{{$project->id}}" id="current_project_id" name="project-id">
                    <select class="chosen-select" multiple id="select_project_managers" name="select_project_managers[]">
                        @foreach ($projectManagers as $pm)
                        <option value="{{$pm->id}}" {{ (in_array($pm->id, $selectedProjectManagers)) ? 'selected': '' }}>{{$pm->first_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn add-project-manager">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>