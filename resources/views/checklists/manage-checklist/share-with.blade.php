<div id="share_with" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Share This Checklist With</h4>
        </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" value="" id="list_id" name="list-id">
                    <select class="chosen-select" multiple id="data" name="users[]">
                        @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                        @endforeach
                    </select>
                    <div class="text-danger text-left field-error" id="label_share_users"></div>
                </div>
                <div class="submit-section text-right">
                    <button class="btn btn-primary submit-btn share">Share</button>
                </div>
            </div>
        </div>
    </div>
</div>