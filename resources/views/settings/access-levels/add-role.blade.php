<!-- Add role Modal -->
<div id="add_role" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Role</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('access-level-add-role')}}" id="add_role_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Role Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="role_name" id="role_name">
                                <div class="text-danger text-left field-error" id="label_role_name"></div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="submit-section text-right">
                    <button class="btn btn-primary create-role">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add role Modal -->