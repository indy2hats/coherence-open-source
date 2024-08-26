<div>
        <table class="table table-striped projectTable">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Project ID</th>
                    <th>Technology</th>
                    <th>Category</th>
                    <th>Client/Account</th>
                    <th>Status</th>
                    <th class="text-center">Priority</th>
                    @can('manage-projects')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody>
                @if(count($projects) > 0)
                @foreach ($projects as $project)
            
                <tr>
                    <td>
                        <a href="{{ Helper::getProjectView().$project->id }}">{{ $project->project_name }}</a>
                    </td>
                    <td>{{ $project->project_id }}</td>
                    <td>{{ $project->technology->name ?? ''}}</td>
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
                        <a class="dropdown-item edit-project" href="#" data-id="{{ $project->id }}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        |
                        <a class="dropdown-item archive-project" href="#" 
                            data-id="{{ $project->id }}" data-tooltip="tooltip"
                            data-placement="top" title="Archive"><i class="ri-archive-line"></i></a>
                    </td>
                    @endcan
                </tr>
                @endforeach
                @else
                <tr>
                     <td colspan="8" align="center">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-7 pagination-div">
                {{$projects->links() }}
            </div>
            <div class="col-md-* text-right text-md-right">
                <strong>Total Number Of Projects : {{ $projects->total() }}</strong>
            </div>
        </div>
</div>