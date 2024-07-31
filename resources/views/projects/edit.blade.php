<!-- Edit Project Modal -->
<div id="edit_project_modal" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Project</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('projects.update', $project->id)}}" id="edit_project_form" method="post" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Project Name <span class="required-label">*</span></label>
                            <input class="form-control" type="text" value="{{$project->project_name}}" name="edit_project_name" id="edit_project_name">
                            <div class="text-danger text-left field-error" id="label_edit_project_name"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group form-focus select-focus focused">
                            <label>Client <span class="required-label">*</span></label>
                            <select class="chosen-select" id="edit_client" name="edit_client">
                                <option value="">Select Client</option>
                                @foreach ($clientsList as $client)
                                <option value="{{$client->id}}" {{$project->client_id == $client->id ? 'selected' : ''}}>{{$client->company_name}}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_edit_client"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Project Id <span class="required-label">*</span></label>
                            <input class="form-control" type="text" name="edit_project_id" id="edit_project_id" value="{{$project->project_id}}" readonly>
                            <div class="text-danger text-left field-error" id="label_edit_project_id"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                    <div class="form-group form-focus select-focus focused">
                            <label>Project Category</label>
                            <select class="chosen-select" id="edit_category" name="edit_category">
                                <option {{$project->category == 'External' ? 'selected' : ''}}>External</option>
                                <option {{$project->category == 'Internal' ? 'selected' : ''}}>Internal</option>
                                <option {{$project->category == 'Upskilling' ? 'selected' : ''}}>Upskilling</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Start Date <span class="required-label">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" type="text" name="edit_start_date" id="edit_start_date" value="{{$project->start_date_show}}">
                                <div class="text-danger text-left field-error" id="label_edit_start_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>End Date</label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" type="text" name="edit_end_date" id="edit_start_date" value="{{$project->end_date_show}}">
                                <div class="text-danger text-left field-error" id="label_edit_end_date"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group form-focus select-focus focused">
                            <label>Cost Type <span class="required-label">*</span></label>
                            <select class="chosen-select" id="edit_cost_type" name="edit_cost_type">
                                <option {{$project->cost_type == 'Hourly' ? 'selected' : ''}}>Hourly</option>
                                <option {{$project->cost_type == 'Fixed' ? 'selected' : ''}}>Fixed</option>
                            </select>
                            <div class="text-danger text-left field-error" id="label_edit_cost_type"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Rate <span class="required-label">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control" type="text" name="edit_rate" id="edit_rate" value="{{$project->rate}}">
                                <div class="text-danger text-left field-error" id="label_edit_rate"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Estimated Hours</label>
                            <div class="cal-icon">
                                <input class="form-control " type="text" name="edit_estimated_hours" id="edit_estimated_hours" value="{{$project->estimated_hours}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group form-focus select-focus focused">
                            <label for="">Priority <span class="required-label">*</span></label>
                            <select class="chosen-select" id="edit_priority" name="edit_priority">
                                <option {{$project->priority == 'High' ? 'selected' : ''}}>High</option>
                                <option {{$project->priority == 'Medium' ? 'selected' : ''}}>Medium</option>
                                <option {{$project->priority == 'Low' ? 'selected' : ''}}>Low</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="cal-icon">
                                <select class="chosen-select" id="edit_status" name="edit_status">
                                    <option {{$project->status == 'Active' ? 'selected' : ''}}>Active</option>
                                    <option {{$project->status == 'Closed' ? 'selected' : ''}}>Closed</option>
                                    <option {{$project->status == 'Cancelled' ? 'selected' : ''}}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                    <div class="form-group form-focus select-focus focused">
                            <label>Project Type</label>
                            <input class="form-control typeahead_type" type="text" name="edit_project_type" value="{{$project->project_type}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-focus select-focus focused">
                            <label>Assign To</label>
                            <select class="chosen-select" id="project_assigned_users" name="project_assigned_users[]" multiple>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}" {{ (in_array($user->id, $projectUsers)) ? 'selected': '' }}>{{$user->full_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="col-sm-6 col-md-6">
                    <div class="form-group">
                            <label>URL</label>
                            <input class="form-control " type="text" name="edit_site_url" id="edit_url">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Technology</label>
                                <div class="cal-icon">
                                    <select class="chosen-select" id="edit_technology" name="edit_technology">
                                        <option value="" disabled selected>Select Technology</option>
                                        @foreach ($technologies as $technology)
                                            <option value="{{ $technology->id }}" {{ $project->technology_id == $technology->id ? 'selected' : '' }}>
                                                {{ ucfirst(strtolower($technology->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <span>Add to Archive List</span> <input @if($project->is_archived == 1) checked @endif type="checkbox" class="i-checks" name="is_archived"> 
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea rows="6" class="form-control summernote" placeholder="Enter your message here" name="edit_description" id="edit_description">{{$project->description}}</textarea>
                </div>

                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-project">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /Edit Project Modal -->