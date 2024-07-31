<div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Notes</th>
                    <th>Status</th>
                    @can('manage-assets')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody>
                @if(count($assetVendors) > 0)
                @foreach ($assetVendors as $assetVendor)
                <tr>
                    <td>
                        {{ $assetVendor->name }}
                    </td>
                    <td>
                        {{ $assetVendor->phone }}
                    </td>
                    <td>
                        {{ $assetVendor->email }}
                    </td>
                    <td>
                        {{ $assetVendor->description }}
                    </td>
                    <td>@if($assetVendor->status == 'active')
                        <label class="label label-success">{{ ucwords(str_replace('_', ' ', $assetVendor->status)) }}</label>
                        @endif
                        @if($assetVendor->status == 'inactive')
                        <label class="label label-info">{{ ucwords(str_replace('_', ' ', $assetVendor->status)) }}</label>
                        @endif
                    </td>
                    @can('manage-assets')
                    <td>
                        <a class="dropdown-item edit-asset-vendor" href="#" data-id="{{ $assetVendor->id }}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        <a class="dropdown-item delete-asset-vendor" href="#" data-toggle="modal"
                            data-target="#delete_asset_vendor" data-id="{{ $assetVendor->id }}" data-tooltip="tooltip"
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
            {{$assetVendors->links()}}
        </div>
</div>