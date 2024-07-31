<div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Depreciation Rate</th>
                    <th>Status</th>
                    @can('manage-assets')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody>
                @if(count($assetTypes) > 0)
                @foreach ($assetTypes as $assetType)
                <tr>
                    <td>
                        {{ $assetType->name }}
                    </td>
                    <td>
                        {{ $assetType->depreciation_rate }}
                    </td>
                    <td>@if($assetType->status == 'active')
                        <label class="label label-success">{{ ucwords(str_replace('_', ' ', $assetType->status)) }}</label>
                        @endif
                        @if($assetType->status == 'inactive')
                        <label class="label label-info">{{ ucwords(str_replace('_', ' ', $assetType->status)) }}</label>
                        @endif
                    </td>
                    @can('manage-assets')
                    <td>
                        <a class="dropdown-item edit-asset-type" href="#" data-id="{{ $assetType->id }}"
                            data-tooltip="tooltip" data-placement="top" title="Edit"> <i
                                class="ri-pencil-line m-r-5"></i></a>
                        <a class="dropdown-item delete-asset-type" href="#" data-toggle="modal"
                            data-target="#delete_asset_type" data-id="{{ $assetType->id }}" data-tooltip="tooltip"
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
            {{$assetTypes->links()}}
        </div>
</div>