<div class="row">
        <div class="col-md-4">
        </div>
        <form id="task-search-form">
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" id="category" name="category">
                        <option value="">Select Category</option>
                        <option value="External" selected="selected">External</option>
                        <option value="Internal" {{ request()->category == "Internal" ? 'selected' : '' }} >Internal</option>
                        <option value="Upskilling" {{ request()->category == "Upskilling" ? 'selected' : '' }} >Upskilling</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" name="client_id" id="clientId">
                        <option value="">Select Client / Account</option>
                        @foreach ($clients as $client)
                        <option value="{{$client->id}}" {{ request()->clientId == $client->id ? 'selected': '' }}>{{$client->company_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" name="project_id" id="projectId">
                        <option value="">Select Project</option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}" {{ request()->project == $project->id ? 'selected': '' }}>{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        
            <div class="col-md-1">
                <div class="form-group">
                    <button class="btn btn-primary btn search" >Search</button>
                </div>
            </div>
        </form>
    </div>