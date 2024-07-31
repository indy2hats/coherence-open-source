<!-- Edit Employee Modal -->

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit User</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('users.update', $user->id)}}" id="edit_employee_form" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">First Name <span class="required-label">*</span></label>
                            <input class="form-control" type="text" name="first_name" id="first_name" value="{{$user->first_name}}">
                            <div class="text-danger text-left field-error" id="edit_label_first_name"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Last Name</label>
                            <input class="form-control" value="{{$user->last_name}}" type="text" name="last_name" id="last_name">
                            <div class="text-danger text-left field-error" id="edit_label_last_name"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Email <span class="required-label">*</span></label>
                            <input class="form-control" type="email" name="email" id="email" value="{{$user->email}}">
                            <div class="text-danger text-left field-error" id="edit_label_email"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}">
                        <div class="form-group">
                            <label class="col-form-label">Employee Id <span class="required-label">*</span></label>
                            <input class="form-control typeahead_id" value="{{$user->employee_id}}" name="employee_id" id="edit_employee_id" readonly required>
                            <div class="text-danger text-left field-error" id="edit_label_employee_id"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Password</label>
                            <input class="form-control" type="password"  name="password" id="password">
                            <div class="text-danger text-left field-error" id="edit_label_password"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Confirm Password</label>
                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                            <div class="text-danger text-left field-error" id="edit_label_password_confirmation"></div>
                        </div>
                    </div>
                </div>
                <div class="row">  
                <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}">  
                        <div class="form-group">
                            <label class="col-form-label">Joining Date</label>
                            <div class="cal-icon"><input class="form-control datetimepicker" value="{{$user->joining_date_show}}" type="text" name="joining_date" id="joining_date"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}">
                        <div class="form-group">
                            <label class="col-form-label">Phone </label>
                            <input class="form-control" value="{{$user->phone}}" type="text" name="phone" id="phone">
                            <div class="text-danger text-left field-error" id="edit_label_phone"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">  
                        <div class="form-group">
                            <label class="col-form-label">Type <span class="required-label">*</span></label>
                            <select class="chosen-select" id="role_id" name="role_id">
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{$user->role_id == $role->id ? 'selected' : ''}}>{{$role->display_name}}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="edit_role_id"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}">  
                        <div class="form-group">
                            <label class="col-form-label">Monthly Salary</label>
                            <input type="text" class="form-control" name="monthly_salary" id="monthly_salary" value="{{$user->monthly_salary}}">
                            <div class="text-danger text-left field-error" id="edit_label_monthly_salary"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}"> 
                        <div class="form-group form-focus select-focus focused">
                            <label class="col-form-label">Department <span class="required-label">*</span></label>
                            <input type="text" class="form-control typeahead_department" name="department" value="{{ $user->department->name ?? '' }}"> 
                            <div class="text-danger text-left field-error" id="edit_label_department"></div>
                        </div>
                    </div>   
                    <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}"> 
                        <div class="form-group form-focus select-focus focused">
                        <label class="col-form-label">Designation <span class="required-label">*</span></label>
                        <input type="text" class="form-control typeahead_designation" name="designation" value="{{ $user->designation->name ?? '' }}">
                        <div class="text-danger text-left field-error" id="edit_label_designation"></div>
                        </div>
                    </div>                   
                </div>
                <div class="row">
                    <div class="col-sm-6"> 
                        <div class="form-group form-focus select-focus focused">
                            <label class="col-form-label">Nick Name</label>
                            <input class="form-control" type="text" name="nick_name" value="{{$user->nick_name}}">
                        </div>
                    </div>
                    <div class="col-sm-6"> 
                        <div class="form-group form-focus select-focus focused">
                            <label class="col-form-label">Image Upload</label>
                            <input class="form-control" type="file" name="image"/>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}">  
                        <div class="form-group">
                            <label class="col-form-label">Address</label>
                            <textarea rows="5" class="form-control" placeholder="Enter Address" name="address">{{ $user->address }}</textarea>
                            <div class="text-danger text-left field-error" id="edit_label_address"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">  
                        <div class="form-group">
                            <label class="col-form-label">Gender <span class="required-label">*</span></label>
                            <select class="chosen-select" id="gender" name="gender">
                                <option value="Male" {{$user->gender == 'Male' ? 'selected' : ''}}>Male</option>
                                <option value="Female" {{$user->gender == 'Female' ? 'selected' : ''}}>Female</option>
                                <option value="Others" {{$user->gender == 'Others' ? 'selected' : ''}}>Others</option>
                            </select>
                            <div class="text-danger text-left field-error" id="edit_label_gender"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}">  
                        <div class="form-group">
                            <label class="col-form-label">Leaving Date</label>
                            <div class="cal-icon"><input class="form-control datetimepicker" value="{{$user->leaving_date_show ?? null}}" type="text" name="leaving_date" id="leaving_date"></div>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-sm-3"> 
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="status" <?php echo $user->status ? 'checked' : '' ?> id="edit_status" >
                            <label class="col-form-labelform-check-label" for="edit_status">Active</label>
                
                        </div>
                    </div>  
                    <div class="col-sm-3 non-client-field" style="display: {{$user->hasRole('client') ? 'none': 'block'}}"> 
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="edit_contract"  id="edit_contract" <?php echo $user->contract ? 'checked' : '' ?> >
                            <label class="col-form-labelform-check-label" for="edit_contract">On Contract</label>
                        </div>
                    </div>                 
                </div>
                <div class="row accordion non-client-field" id="bank-details" style="display: {{$user->hasRole('client') ? 'none': 'block'}}">                       
                    <div class="row" id="heading-one">
                            <h2 class="mb-0 col-sm-12 m-0">
                                <button type="button" class="btn btn-link col-sm-12 text-primary" data-toggle="collapse" data-target="#collapse-edit-bank"><span class="text-green">Bank Details</span> <i class="fa fa-angle-down float-right"></i></button>                                  
                            </h2>
                    </div>
                    <div id="collapse-edit-bank" class="collapse col-sm-12" aria-labelledby="heading-one" data-parent="#bank-details">
                        <div class="row">
                            <div class="col-sm-6 non-client-field"> 
                                <div class="form-group form-focus select-focus focused">
                                    <label class="col-form-label">Bank Name</label>
                                    <input class="form-control typeahead_bank_name" type="text" name="bank_name" value="{{$user->bank_name}}" required>
                                    <div class="text-danger text-left field-error" id="edit_label_bank_name"></div>
                                </div>
                            </div> 
                            <div class="col-sm-6 non-client-field"> 
                                <div class="form-group form-focus select-focus focused">
                                    <label class="col-form-label">Account Number</label>
                                    <input class="form-control typeahead_account_number" type="text" name="account_number" value="{{$user->account_no }}" required>
                                    <div class="text-danger text-left field-error" id="edit_label_account_number"></div>
                                </div>
                            </div> 
                        </div> 
                        <div class="row">
                            <div class="col-sm-6 non-client-field"> 
                                <div class="form-group form-focus select-focus focused">
                                    <label class="col-form-label">Branch</label>
                                    <input class="form-control typeahead_branch" type="text" name="branch" value="{{$user->branch}}" required>
                                    <div class="text-danger text-left field-error" id="edit_label_branch"></div>
                                </div>
                            </div> 
                            <div class="col-sm-6 non-client-field"> 
                                <div class="form-group form-focus select-focus focused">
                                    <label class="col-form-label">IFSC Code</label>
                                    <input class="form-control typeahead_ifsc" type="text" name="ifsc" value="{{$user->ifsc}}" required>
                                    <div class="text-danger text-left field-error" id="edit_label_ifsc"></div>
                                </div>
                            </div> 
                        </div> 
                        <div class="row">
                            <div class="col-sm-6"> 
                                <div class="form-group form-focus select-focus focused">
                                    <label class="col-form-label">Pan Card Number</label>
                                    <input class="form-control typeahead_pan_number" type="text" name="pan_number" value="{{$user->pan}}" required>
                                    <div class="text-danger text-left field-error" id="edit_label_pan_number"></div>
                                </div>
                            </div> 
                            <div class="col-sm-6 non-client-field"> 
                                <div class="form-group form-focus select-focus focused">
                                    <label class="col-form-label">UAN Number</label>
                                    <input class="form-control typeahead_uan_number" type="text" name="uan_number" value="{{$user->uan}}" required>
                                    <div class="text-danger text-left field-error" id="edit_label_uan_number"></div>
                                </div>
                            </div> 
                        </div>
                    </div> 
                </div> 
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-user">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Employee Modal -->
