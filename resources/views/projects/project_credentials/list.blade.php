<div class="col-md-12">
    <table class="table table-striped files">
        <thead>
            <tr>
                <th>Type</th>
                <th>Value</th>
                <th>File</th>
                <th>Created Date</th>
                @can('manage-project-credentials')
                    <th>Action</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{$item->type}}</td>
                <td>{!! nl2br($item->value) !!}</td>
                <td>@if($item->path != '') <a class="dropdown-item" href="{{ asset('storage/'.$item->path)}}" download><i class="ri-download-line"></i> Download</a> @endif</td>
                <td>{{$item->created_at_format}}</td>
                @can('manage-project-credentials')
                    <td>
                        <a class="dropdown-item edit-data" data-id="{{ $item->id }}" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="ri-pencil-line"></i></a> |
                        <a class="dropdown-item delete-credential-data" href="#" data-toggle="modal" data-target="#delete_credential" data-id="{{ $item->id }}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                    </td>
                @endcan
            </tr>
            @endforeach
        </tbody>
    </table>
</div>