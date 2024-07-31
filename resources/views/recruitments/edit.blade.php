
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Candidate</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('recruitments.update',$candidate->id)}}" id="edit_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name" id="name" value="{{$candidate->name}}">
                                <div class="text-danger text-left field-error" id="label_edit_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="email" id="email" value="{{$candidate->email}}">
                                <div class="text-danger text-left field-error" id="label_edit_email"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Phone <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="phone" id="phone" value="{{$candidate->phone}}">
                                <div class="text-danger text-left field-error" id="label_edit_phone"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Category <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="category" name="category">
                                 <option value="Front End Developer" {{$candidate->category == 'Front End Developer'?'selected':''}}>Front End Developer</option>
                                    <option value="PHP Developer" {{$candidate->category == 'PHP Developer'?'selected':''}}>PHP Developer</option>
                                    <option value="React Native Developer" {{$candidate->category == 'React Native Developer'?'selected':''}}>React Native Developer</option>
                                    <option value="QA" {{$candidate->category == 'QA'?'selected':''}}>QA</option>
                                    <option value="Digital Marketing Executive"  {{$candidate->category == 'Digital Marketing Executive' ? 'selected' : ''}}>Digital Marketing Executive</option>
                                    <option value="Business Development Executive"  {{$candidate->category == 'Business Development Executive' ? 'selected' : ''}}>Business Development Executive</option>
                                    <option value="Wordpress Developer"  {{$candidate->category == 'Wordpress Developer' ? 'selected' : ''}}>Wordpress Developer</option>
                                    <option value="IT Project Coordinator"  {{$candidate->category == 'IT Project Coordinator' ? 'selected' : ''}}>IT Project Coordinator</option>
                                    <option value="Technical Writer"  {{$candidate->category == 'Technical Writer' ? 'selected' : ''}}>Technical Writer</option>
                                    <option value="Accounts Executive"  {{$candidate->category == 'Accounts Executive' ? 'selected' : ''}}>Accounts Executive</option>
                                    <option value="HR Associate"  {{$candidate->category == 'HR Associate' ? 'selected' : ''}}>HR Associate</option>
                                    <option value="HR Manager"  {{$candidate->category == 'HR Manager' ? 'selected' : ''}}> HR Manager</option>
                                    <option value="Fresher" {{$candidate->category == 'Fresher'?'selected':''}}>Fresher</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_edit_category"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Source <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="source" name="source">
                                    <option value="Naukri" {{$candidate->source == 'Naukri'?'selected':''}}>Naukri</option>
                                    <option value="Linkedin" {{$candidate->source == 'Linkedin'?'selected':''}}>Linkedin</option>
                                    <option value="Agency" {{$candidate->source == 'Agency'?'selected':''}}>Agency</option>
                                    <option value="Email" {{$candidate->source == 'Email'?'selected':''}}>Email</option>
                                    <option value="Reference" {{$candidate->source == 'Reference'?'selected':''}}>Reference</option>
                                    <option value="Others" {{$candidate->source == 'Others'?'selected':''}}>Others</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_source"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Career Start Date </label>
                                <input class="form-control datetimepicker" type="text" name="career_start_date" id="career_start_date" value="{{ $candidate->career_start_date_format }}">
                                <div class="text-danger text-left field-error" id="label_career_start_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Status <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="status" name="status">
                                    <option value="Pending" {{$candidate->status == 'Pending'?'selected':''}}>Pending</option>
                                    <option value="Processing" {{$candidate->status == 'Processing'?'selected':''}}>Processing</option>
                                    <option value="Selected" {{$candidate->status == 'Selected'?'selected':''}}>Selected</option>
                                    <option value="Rejected" {{$candidate->status == 'Rejected'?'selected':''}}>Rejected</option>
                                    <option value="On Hold" {{$candidate->status == 'On Hold'?'selected':''}}>On Hold</option>
                                    <option value="Test Not Attended" {{$candidate->status == 'Test Not Attended'?'selected':''}}>Test Not Attended</option>
                                    <option value="Interview Not Attended" {{$candidate->status == 'Interview Not Attended'?'selected':''}}>Interview Not Attended</option>
                                    <option value="Can be Considered" {{$candidate->status == 'Can be Considered'?'selected':''}}>Can be Considered</option>
                                    <option value="Declined Offer" {{$candidate->status == 'Declined Offer'?'selected':''}}>Declined Offer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Applied Date </label>
                                <input class="form-control datetimepicker" type="text" name="applied_date" id="applied_date"  value="{{ $candidate->applied_date }}">
                                <div class="text-danger text-left field-error" id="label_applied_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Upload Resume <span class="required-label">*</span></label>
                                <input class="form-control" type="file" name="resume"/>
                                <div class="text-danger text-left field-error" id="label_resume"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control summernote" name="description" id="description">{{$candidate->description}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn update-candidate">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
