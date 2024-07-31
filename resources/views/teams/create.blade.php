<!-- Add Team Modal -->
<div id="add_team" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Manage Team</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('team.store')}}" id="add-team-form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="col-form-label">Reporting to <span class="required-label">*</span></label>
                            <select class="chosen-select" name="reviewer" id="reviewer">
                                <option value="{{ $authUser->id }}"  selected>{{ $authUser->full_name}}</option>
                            </select> 
                            <div class="text-danger text-left field-error" id="label_reviewer"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Reportees <span class="required-label">*</span></label>
                            <select class="chosen-select" name="reportees[]" id="reportees" multiple>
                                @foreach ($users as $key => $user) 
                               
                                        <option value="{{ $key }}" {{in_array($key,$reporteesId) ? 'selected' : ''}}>{{ $user}}</option>
                        
                                @endforeach
                            </select> 
                            <div class="text-danger text-left field-error" id="label_reportees"></div>
                        </div>    
                    </div>
                    <div class="submit-section text-center">
                        <button button="submit" class="btn btn-primary add-team">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Team Modal -->