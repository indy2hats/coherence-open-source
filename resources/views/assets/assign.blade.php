<div id="assign_asset" class="modal custom-modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Assign Asset</h4>
            </div>
            <div class="modal-body">
                <form  action="{{ route('assets.assign') }}" id="assign_asset_form" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" id="assign_asset_id" name="id" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>User <span class="required-label">*</span></label>
                                <select class="chosen-select" id="user_id" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->full_name}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_user_id"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Assigned Date <span class="required-label"></span></label>
                                <input class="form-control datetimepicker" type="text" name="assigned_date">
                                <div class="text-danger text-left field-error" id="label_assigned_date"></div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn assign_asset_btn" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>