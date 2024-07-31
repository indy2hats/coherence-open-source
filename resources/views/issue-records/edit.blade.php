
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Issue</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('issue-records.update', $issue->id)}}" id="edit_issue_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Project Name <span class="required-label">*</span></label>
                                <select class="chosen-select" id="select_project_name" name="project_id">
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $project)
                                    <option @if($project->id == $issue->project_id) selected @endif value="{{$project->id}}">{{$project->project_name}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_project_id"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input value="{{$issue->title}}" class="form-control" type="text" name="title" id="title">
                                <div class="text-danger text-left field-error" id="label_title"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-focus select-focus focused">
                                <label for="">Category <span class="required-label">*</span></label>
                                <select class="chosen-select" id="category" name="category">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option  @if($category->slug == $issue->category) selected @endif value="{{$category->slug}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_category"></div>
                            </div>
                        </div> 
                        <div class="col-sm-6 col-md-6" style="padding: 20px 15px;"> 
                            <label>Category Not Listed?</label>
                            <a href="#" class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#add_category"><i class="ri-add-line"></i> Add Category</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Issue <span class="required-label">*</span></label>
                        <textarea rows="4" class="form-control summernote" placeholder="Enter your issue here" name="description">{{$issue->description}}</textarea>
                        <div class="text-danger text-left field-error" id="label_description"></div>
                    </div>

                    <div class="form-group">
                        <label>Solution <span class="required-label">*</span></label>
                        <textarea rows="4" class="form-control summernote" placeholder="Enter your solution here" name="solution">{{$issue->solution}}</textarea>
                        <div class="text-danger text-left field-error" id="label_solution"></div>
                    </div>
                                   
                    <div class="submit-section mt20">
                        <button class="btn btn-primary submit-btn update-issue">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Create task Modal -->