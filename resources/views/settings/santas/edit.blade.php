
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Santa Member</h4>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" action="{{route('santa-members.update', $santa->id)}}" id="edit_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>User <span class="required-label">*</span></label>
                                <select class="chosen-select" id="user_id" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option @if($user->id == $santa->user_id) selected @endif value="{{ $user->id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_user_id"></div>
                            </div>
                            <div class="form-group">
                                <label>Phone <span class="required-label">*</span></label>
                                <input value="{{ $santa->phone }}" class="form-control" type="text" name="phone" id="phone">
                                <div class="text-danger text-left field-error" id="label_phone"></div>
                            </div>
                            <div class="form-group">
                                <label>Address<span class="required-label">*</span></label>
                                <textarea rows="5" class="form-control" placeholder="Enter Address" name="address">{{ $santa->address }}</textarea>
                                <div class="text-danger text-left field-error" id="label_address"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Select Image</label>
                                <input class="form-control" type="file" name="image"/>
                                <div class="text-danger text-left field-error" id="label_image"></div>
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
