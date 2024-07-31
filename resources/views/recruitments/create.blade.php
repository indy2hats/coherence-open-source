<div id="create_candidate" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Candidate</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('recruitments.store')}}" id="add_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name" id="name">
                                <div class="text-danger text-left field-error" id="label_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="email" id="email">
                                <div class="text-danger text-left field-error" id="label_email"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Phone <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="phone" id="phone">
                                <div class="text-danger text-left field-error" id="label_phone"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Category <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="category" name="category">
                                    <option value="">Select Category</option>
                                    <option value="Front End Developer">Front End Developer</option>
                                    <option value="PHP Developer">PHP Developer</option>
                                    <option value="React Native Developer">React Native Developer</option>
                                    <option value="QA">QA</option>
                                    <option value="Digital Marketing Executive">Digital Marketing Executive</option>
                                    <option value="Business Development Executive">Business Development Executive</option>
                                    <option value="Wordpress Developer">Wordpress Developer</option>
                                    <option value="IT Project Coordinator">IT Project Coordinator</option>
                                    <option value="Technical Writer">Technical Writer</option>
                                    <option value="Accounts Executive">Accounts Executive</option>
                                    <option value="HR Associate">HR Associate</option>
                                    <option value="HR Manager">HR Manager</option>
                                    <option value="Fresher">Fresher</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_category"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Source <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="source" name="source">
                                    <option value="">Select Source</option>
                                    <option value="Naukri">Naukri</option>
                                    <option value="Linkedin">Linkedin</option>
                                    <option value="Agency">Agency</option>
                                    <option value="Email">Email</option>
                                    <option value="Reference">Reference</option>
                                    <option value="Others">Others</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_source"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Career Start Date </label>
                                <input class="form-control datetimepicker" type="text" name="career_start_date" id="career_start_date">
                                <div class="text-danger text-left field-error" id="label_career_start_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Applied Date </label>
                                <input class="form-control datetimepicker" type="text" name="applied_date" id="applied_date">
                                <div class="text-danger text-left field-error" id="label_applied_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Status <span class="required-label">*</span></label>
                                <select class="chosen-select" id="status" name="status">
                                    <option value="Pending" selected>Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Selected">Selected</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Test Not Attended">Test Not Attended</option>
                                    <option value="Interview Not Attended">Interview Not Attended</option>
                                    <option value="Can be Considered">Can be Considered</option>
                                    <option value="Declined Offer">Declined Offer</option>
                                </select>
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
                                <textarea class="form-control summernote" name="description" id="description"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create-recruitment">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
