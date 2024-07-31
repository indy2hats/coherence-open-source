<!-- Add Employee Modal -->
<div id="add_client" class="modal custom-modal animated fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Client</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('clients.store')}}" id="add_client_form" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">User </label>
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
                                <label class="col-form-label">Account Manager </label>
                                <select class="chosen-select" id="account_manager_id" name="account_manager_id">
                                    <option value="">Select User</option>
                                    @foreach ($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->full_name}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_account_manager_id"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Company Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="company_name">
                                <div class="text-danger text-left field-error" id="label_company_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class="required-label">*</span></label>
                                <input class="form-control" type="email" name="email">
                                <div class="text-danger text-left field-error" id="label_email"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Country <span class="required-label">*</span></label>
                                <input class="typeahead_3 form-control" type="text" name="country">
                                <div class="text-danger text-left field-error" id="label_country"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Currency <span class="required-label">*</span></label>
                                <select class="chosen-select" id="currency" name="currency">
                                    @foreach($currencyList as $key=>$label)
                                        <option value="{{$key}}">{{$key}} - {{$label}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_currency"></div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">State</label>
                                <input class="form-control" type="text" name="state">
                                <div class="text-danger text-left field-error" id="label_state"></div>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">City</label>
                                <input class="form-control" type="text" name="city">
                                <div class="text-danger text-left field-error" id="label_city"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Address</label>
                                <input class="form-control" type="text" name="address">
                                <div class="text-danger text-left field-error" id="label_address"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Zip Code</label>
                                <input class="form-control" type="text" name="post_code">
                                <div class="text-danger text-left field-error" id="label_post_code"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Phone </label>
                                <input class="form-control" type="text" name="phone">

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Upload Image</label>
                                <input class="form-control" type="file" name="image" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-form-label hidden-label">Tax details</label>
                        <div class="form-group tax-details-field-group">
                            <div class="col-md-4 field-width">
                                <input class="form-control" type="text" name="vat_gst_tax_label" value="" placeholder="label">
                                <div class="text-danger text-left field-error" id="label_vat_gst_tax_label"></div>
                            </div>
                            <div class="col-md-4 field-width">
                                <input class="form-control" type="text" name="vat_gst_tax_id" placeholder="ID">
                                <div class="text-danger text-left field-error" id="label_vat_gst_tax_id"></div>
                            </div>
                            <div class="col-md-4 input-group">
                                <input class="form-control" type="text" name="vat_gst_tax_percentage" placeholder="Value in %">
                                <div class="text-danger text-left field-error" id="lable_vat_gst_tax_percentage"></div>
                            </div> 
                        </div>
                    </div>
                </form>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn create-client">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Employee Modal -->