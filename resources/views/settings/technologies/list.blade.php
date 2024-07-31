<div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    @can('manage-technologies')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody>
                @if(count($technologies) > 0)
                @foreach ($technologies as $technology)
                <tr>
                    <td>
                        {{ $technology->name }}
                    </td>
                    <td>@if($technology->status == 'active')
                        <label class="label label-success">{{ ucwords(str_replace('_', ' ', $technology->status)) }}</label>
                        @endif
                        @if($technology->status == 'inactive')
                        <label class="label label-info">{{ ucwords(str_replace('_', ' ', $technology->status)) }}</label>
                        @endif
                    </td>
                    @can('manage-technologies')
                    <td>
                        <a class="dropdown-item edit-technology" href="#" data-id="{{ $technology->id }}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        <a class="dropdown-item delete-technology" href="#" data-toggle="modal"
                            data-target="#delete_technology" data-id="{{ $technology->id }}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>
                    </td>
                    @endcan
                </tr>
                @endforeach
                @else
                <tr>
                     <td colspan="7" align="center">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="pagination-div">
            {{$technologies->links()}}
        </div>
</div>