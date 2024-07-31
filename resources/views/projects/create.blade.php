<!-- Create Project Modal -->
<div id="create_project" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Project</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('projects.store')}}" id="add_project_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Project Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="project_name" id="project_name_id">
                                <div class="text-danger text-left field-error" id="label_project_name"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Client <span class="required-label">*</span></label>
                                <select class="chosen-select" name="client">
                                    <option value="">Select Client</option>
                                    @foreach ($clientsList as $client)
                                    <option value="{{$client->id}}">{{$client->company_name}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_client"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Priority <span class="required-label">*</span></label>
                                <select class="chosen-select" id="priority" name="priority">
                                    <option selected>High</option>
                                    <option>Medium</option>
                                    <option> Low</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_priority"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Project Category</label>
                                <select class="chosen-select" id="category" name="category">
                                    <option selected>External</option>
                                    <option>Internal</option>
                                    <option>Upskilling</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Start Date <span class="required-label">*</span></label>
                                <input class="form-control datetimepicker" type="text" name="start_date">
                                <div class="text-danger text-left field-error" id="label_start_date"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>End Date </label>
                                <input class="form-control datetimepicker" type="text" name="end_date">
                                <div class="text-danger text-left field-error" id="label_end_date"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Cost Type  <span class="required-label">*</span></label>
                                <select class="chosen-select" id="cost_type" name="cost_type">
                                    <option>Hourly</option>
                                    <option>Fixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Rate  <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="rate" id="rate_id">
                                <div class="text-danger text-left field-error" id="label_rate"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Estimated Hours</label>
                                <input class="form-control " type="text" name="estimated_hours" id="estimated_hours_id"> 
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                        <div class="form-group form-focus select-focus focused">
                                <label>Project Type</label>
                                <input class="form-control typeahead_type" type="text" name="project_type">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-focus select-focus focused">
                                <label>Assign To</label>
                                <select class="chosen-select" id="project_assigned_users" name="project_assigned_users[]" multiple>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                                <label>URL</label>
                                <input class="form-control" type="text" name="site_url" id="url_id">
                                <div class="text-danger text-left field-error" id="label_url"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Technology</label>
                                <div class="cal-icon">
                                    <select class="chosen-select" id="technology" name="technology">
                                        <option value="" disabled selected>Select Technology</option>
                                        @foreach ($technologies as $technology)
                                            <option value="{{ $technology->id }}">
                                                {{ ucfirst(strtolower($technology->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    </div>
                     </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea rows="5" class="form-control summernote" placeholder="Enter your message here" name="description"></textarea>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create-project">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Create Project Modal -->