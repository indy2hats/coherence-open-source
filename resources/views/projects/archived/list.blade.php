<div>
        <table class="table table-striped projectTable">
            <thead>
                <tr>
                    <th>Archived</th>
                    <th>Project</th>
                    <th>Project ID</th>
                    <th>Category</th>
                    <th>Client/Account</th>
                    <th>Status</th>
                    <th class="text-center">Priority</th>
                    @can('manage-projects')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                <tr>
                    <td><div class="form-group form-check">
                                <input type="checkbox" class="form-check-input change-archive-project" data-id="{{$project->id}}" {{$project->is_archived == 1?'checked':''}} id="{{$project->id}}" >
                                <label class="col-form-label form-check-label" for="{{$project->id}}"></label>
                    
                            </div></td>
                    <td>
                        <a href="{{ Helper::getProjectView().$project->id }}">{{ $project->project_name }}</a>
                    </td>
                    <td>{{ $project->project_id }}</td>
                    <td>{{ $project->category }}</td>
                    <td>{{ $project->client->company_name?? '' }}</td>
                    <td>{{ $project->status }}</td>
                    <td class="text-center">
                        @if($project->priority=="High")
                        <span class="badge badge-danger">High</span>
                        @endif
                        @if($project->priority=="Medium")
                        <span class="badge badge-warning">Medium</span>
                        @endif
                        @if($project->priority=="Low")
                        <span class="badge badge-primary">Low</span>
                        @endif
                    </td>
                    @can('manage-projects')
                    <td>
                        <a class="dropdown-item delete-project" href="#" data-toggle="modal"
                            data-target="#delete_project" data-id="{{ $project->id }}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                    </td>
                    @endcan
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        No Data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination-div">
            {{$projects->links()}}
        </div>
</div>