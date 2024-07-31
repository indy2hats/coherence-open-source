<div class="col-md-12">
    <table class="table table-striped files">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Date Uploaded</th>
                <th>Path / Link</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($files as $file)
                <tr>
                    <td>{{$file->name}}</td>
                    <td>{{$file->type}}</td>
                    <td>{{$file->created_at_format}}</td>
                    <td>@if($file->type == 'link')<a href="{{$file->path}}" target="blank">{!!$file->path!!}</a>@else{{$file->path}}@endif</td>
                    <td>
                        @if($file->type == 'file')
                            <a class="dropdown-item" href="{{ asset('storage/'.$file->path)}}" download><i class="ri-download-line"></i> Download</a>
                            @can('manage-project-documents') | @endcan
                        @endif
                        @can('manage-project-documents')
                            <a class="dropdown-item delete-file-show" href="#" data-toggle="modal" data-target="#delete_file" data-id="{{ $file->id }}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>