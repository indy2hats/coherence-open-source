<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Client</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('clients.update', $client->id)}}" id="edit_client_form" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                                <label class="col-form-label">User </label>
                                <select class="chosen-select" id="user_id" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}" {{$client->user_id == $user->id ? 'selected' : ''}}>{{$user->full_name}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="edit_user_id"></div>
                            </div>
                    </div>
                </div>
             <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Company Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="company_name" value="{{$client->company_name}}">
                                <div class="text-danger text-left field-error" id="label_edit_company_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class="required-label">*</span></label>
                                <input class="form-control" type="email" name="email" value="{{$client->email}}">
                                <div class="text-danger text-left field-error" id="label_edit_email"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Country <span class="required-label">*</span></label>
                                <input class="typeahead_3 form-control" type="text" name="country" value="{{$client->country}}">
                                <div class="text-danger text-left field-error" id="label_edit_country"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Currency <span class="required-label">*</span></label>
                                <input class="typeahead_4 form-control" type="text" name="currency" value="{{$client->currency}}">
                                <div class="text-danger text-left field-error" id="label_edit_currency"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">State</span></label>
                                <input class="form-control" type="text" name="state" value="{{$client->state}}">
                                 
                            </div>
                        </div>
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">City</label>
                                <input class="form-control" type="text" name="city" value="{{$client->city}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Address</label>
                                <input class="form-control" type="text" name="address" value="{{$client->address}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">PO Box</label>
                                <input class="form-control" type="text" name="post_code" value="{{$client->post_code}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Phone </label>
                                <input class="form-control" type="text" name="phone" value="{{$client->phone}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Upload Image</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Select file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="image"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div> 
                            </div>
                        </div>
                    </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-client">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>