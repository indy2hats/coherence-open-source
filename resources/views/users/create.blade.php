<!-- Add Employee Modal -->
<div id="add_employee" class="modal custom-modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog modal-dialog-centered modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New User</h4>
            </div>
            <div class="modal-body">
                
                <form action="{{route('users.store')}}" id="add_employee_form" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">First Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="first_name" id="first-name_id" required>
                                <div class="text-danger text-left field-error" id="label_first_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Last Name</label>
                                <input class="form-control" type="text" name="last_name" id="last_name_id">
                                <div class="text-danger text-left field-error" id="label_last_name"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class="required-label">*</span></label>
                                <input class="form-control" type="email" name="email" id="email_id" required> 
                                <div class="text-danger text-left field-error" id="label_email"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 non-client-field">  
                            <div class="form-group">
                                <label class="col-form-label">Employee ID <span class="required-label">*</span></label>
                                <input type="text" class="form-control typeahead_id" name="employee_id" id="employee_id" readonly required>
                                <div class="text-danger text-left field-error" id="label_employee_id"></div>  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Password <span class="required-label">*</span></label>
                                <input class="form-control" type="password" name="password" id="password_id" required>
                                <p id="passwordHelpBlock" class="help-text text-muted">
                                    Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                                </p>
                                <div class="text-danger text-left field-error" id="label_password"></div>                                
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Confirm Password <span class="required-label">*</span></label>
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation_id" required>
                                <div class="text-danger text-left field-error" id="label_password_confirmation"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">  
                            <div class="form-group">
                                <label class="col-form-label">Type <span class="required-label">*</span></label>
                                <select class="chosen-select" id="role_id" name="role_id" required>
                                    <option value="" selected>Select Type</option>
                                    @foreach ($roles as $role)
                                        <option value="{{$role->id}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_role_id"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 non-client-field">  
                            <div class="form-group">
                                <label class="col-form-label">Monthly Salary </label>
                                <input type="text" class="form-control" name="monthly_salary" id="monthly_salary_id">
                                <div class="text-danger text-left field-error" id="label_monthly_salary"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 non-client-field">  
                            <div class="form-group">
                                <label class="col-form-label">Joining Date</label>
                                <div class="cal-icon"><input class="form-control datetimepicker" type="text" name="joining_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 non-client-field">
                            <div class="form-group">
                                <label class="col-form-label">Phone </label>
                                <input class="form-control" type="text" name="phone">
                                <div class="text-danger text-left field-error" id="phone_id"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 non-client-field"> 
                            <div class="form-group form-focus select-focus focused">
                                <label class="col-form-label">Department <span class="required-label">*</span></label>
                                <input class="form-control typeahead_department" type="text" name="department" required>
                                <div class="text-danger text-left field-error" id="label_department"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 non-client-field"> 
                            <div class="form-group form-focus select-focus focused">
                                <label class="col-form-label">Designation <span class="required-label">*</span></label>
                                <input class="form-control typeahead_designation" type="text" name="designation" required>
                                <div class="text-danger text-left field-error" id="label_designation"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"> 
                            <div class="form-group form-focus select-focus focused">
                                <label class="col-form-label">Nick Name</label>
                                <input class="form-control" type="text" name="nick_name">
                            </div>
                        </div>
                        <div class="col-sm-6">  
                            <div class="form-group">
                                <label class="col-form-label">Gender <span class="required-label">*</span></label>
                                <select class="chosen-select" id="gender" name="gender" required>
                                    <option value="" selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_gender"></div>
                            </div>
                        </div>                                        
                    </div>
                    <div class="row">
                        <div class="col-sm-6 non-client-field"> 
                            <div class="form-group ">
                                <label class="col-form-label">Address</label>
                                <textarea rows="3" class="form-control" placeholder="Enter Address" name="address"></textarea>
                                <div class="text-danger text-left field-error" id="label_address"></div>
                            </div>
                        </div>
                        <div class="col-sm-6"> 
                            <div class="row">
                                <div class="col-sm-12"> 
                                    <div class="form-group form-focus select-focus focused">
                                        <label class="col-form-label">Image Upload</label>
                                        <input class="form-control" type="file" name="image"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">                        
                                <div class="col-sm-6"> 
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" name="status" checked="" id="status" >
                                        <label class="col-form-labelform-check-label" for="status">Active</label>
                    
                                    </div>
                                </div>
                                <div class="col-sm-6"> 
                                    <div class="form-group form-check non-client-field">
                                        <input type="checkbox" class="form-check-input" name="contract"  id="contract" >
                                        <label class="col-form-labelform-check-label" for="contract">On Contract</label>
                                    </div>
                                </div>       
                            </div>
                        </div>               
                    </div>
                    <div class="row accordion non-client-field" id="bank-details">                       
                            <div class="row" id="heading-one">
                                    <h2 class="mb-0 col-sm-12 m-0">
                                        <button type="button" class="btn btn-link col-sm-12 text-primary" data-toggle="collapse" data-target="#collapse-one"><span class="text-green">Bank Details</span> <i class="fa fa-angle-down float-right"></i></button>                                  
                                    </h2>
                            </div>
                            <div id="collapse-one" class="collapse col-sm-12" aria-labelledby="heading-one" data-parent="#bank-details">
                                <div class="row">
                                <div class="col-sm-6 non-client-field"> 
                                    <div class="form-group form-focus select-focus focused">
                                        <label class="col-form-label">Bank Name</label>
                                        <input class="form-control typeahead_bank_name" type="text" name="bank_name" required>
                                        <div class="text-danger text-left field-error" id="label_bank_name"></div>
                                    </div>
                                </div> 
                                <div class="col-sm-6 non-client-field"> 
                                    <div class="form-group form-focus select-focus focused">
                                        <label class="col-form-label">Account Number</label>
                                        <input class="form-control typeahead_account_number" type="text" name="account_number" required>
                                        <div class="text-danger text-left field-error" id="label_account_number"></div>
                                    </div>
                                </div> 
                                </div>
                                <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="form-group form-focus select-focus focused">
                                        <label class="col-form-label">Branch</label>
                                        <input class="form-control typeahead_branch" type="text" name="branch" required>
                                        <div class="text-danger text-left field-error" id="label_branch"></div>
                                    </div>
                                </div> 
                                <div class="col-sm-6"> 
                                    <div class="form-group form-focus select-focus focused">
                                        <label class="col-form-label">IFSC Code</label>
                                        <input class="form-control typeahead_ifsc" type="text" name="ifsc" required>
                                        <div class="text-danger text-left field-error" id="label_ifsc"></div>
                                    </div>
                                </div> 
                                </div>
                                <div class="row">
                                <div class="col-sm-6 non-client-field"> 
                                    <div class="form-group form-focus select-focus focused">
                                        <label class="col-form-label">Pan Card Number</label>
                                        <input class="form-control typeahead_pan_number" type="text" name="pan_number" required>
                                        <div class="text-danger text-left field-error" id="label_pan_number"></div>
                                    </div>
                                </div> 
                                <div class="col-sm-6 non-client-field"> 
                                    <div class="form-group form-focus select-focus focused">
                                        <label class="col-form-label">UAN Number</label>
                                        <input class="form-control typeahead_uan_number" type="text" name="uan_number" required>
                                        <div class="text-danger text-left field-error" id="label_uan_number"></div>
                                    </div>
                                </div> 
                                </div>
                            </div> 
                    </div>                    
                    
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create-user">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Employee Modal -->
