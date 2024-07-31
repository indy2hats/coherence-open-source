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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Account Manager </label>
                            <select class="chosen-select" id="account_manager_id" name="account_manager_id">
                                <option value="">Select User</option>
                                @foreach ($employees as $employee)
                                <option value="{{$employee->id}}" {{$client->account_manager_id == $employee->id ? 'selected' : ''}}>{{$employee->full_name}}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="edit_account_manager_id"></div>
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
                            <select class="chosen-select" id="currency" name="currency">
                                @foreach($currencyList as $key=>$label)
                                    <option value="{{$key}}" {{$key == $client->currency?'selected':''}}>{{$key}} - {{$label}}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_edit_currency"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">State</span></label>
                            <input class="form-control" type="text" name="state" value="{{$client->state}}">
                            <div class="text-danger text-left field-error" id="label_edit_state"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">City</label>
                            <input class="form-control" type="text" name="city" value="{{$client->city}}">
                            <div class="text-danger text-left field-error" id="label_edit_city"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Address</label>
                            <input class="form-control" type="text" name="address" value="{{$client->address}}">
                            <div class="text-danger text-left field-error" id="label_edit_address"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Zip Code</label>
                            <input class="form-control" type="text" name="post_code" value="{{$client->post_code}}">
                            <div class="text-danger text-left field-error" id="label_edit_post_code"></div>
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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Upload Image</label>
                            <div class="fileinput fileinput-new input-group file-upload-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <div class="upload-file-width">
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <!-- <span class="fileinput-new">Select file Change</span> -->
                                        <!-- <span class="fileinput-exists">Change</span> -->
                                        <input type="file" name="image" />
                                    </span>
                                </div>
                                <div>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>

                                </div>
                                
                            </div>
                            <label class="col-form-label" style="color: #9b9b9b">Select file Change</label>
                        </div>
                        <!--<div class="form-group">
                            <label class="col-form-label">VAT ID / TAX ID</label>
                            <input class="form-control" type="text" name="vat_id" value="{{$client->vat_id}}">
                        </div>-->
                    </div>
                </div>
                <div class="row">
                    <label class="col-form-label hidden-label">Tax details</label>
                    <div class="form-group tax-details-field-group">
                        <div class="col-sm-4 field-width">
                            <input class="form-control" type="text" name="vat_gst_tax_label" value="{{$client->vat_gst_tax_label}}" placeholder="Label">
                            <div class="text-danger text-left field-error" id="label_edit_vat_gst_tax_label"></div>
                        </div>
                        <div class="col-sm-4 field-width">
                            <input class="form-control" type="text" name="vat_gst_tax_id" value="{{$client->vat_gst_tax_id}}" placeholder="ID">
                            <div class="text-danger text-left field-error" id="label_edit_vat_gst_tax_id"></div>
                        </div>
                        <div class="col-sm-4 input-group">
                            <input class="form-control" type="text" name="vat_gst_tax_percentage" value="{{$client->vat_gst_tax_percentage}}" placeholder="Value in %">
                            <div class="text-danger text-left field-error" id="label_edit_vat_gst_tax_percentage"></div>
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