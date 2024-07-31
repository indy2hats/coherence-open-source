<table class="table table-stripped asset-list">

    <thead>

        <tr>
            <th>Name</th>

            <th>Type</th>

            <th>Employee</th>

            <th>Serial Number</th>

            <th>Date Of Purchase</th>

            <th>Purchase Value (INR)</th>

            <th>Depreciated Value (INR)</th>

            <th>Status</th>

            @foreach($attributes as $attribute)
            <th>{{$attribute->name}}</th>
            @endforeach

        </tr>

    </thead>

    <tbody>
        <?php $filteredAssetValue = $filteredAssetDepreciatedValue = 0; ?>
        @forelse($assets as $asset)
    
        <tr>

            <td>{{$asset->name}}</td>
            
            <td>{{$asset->assetType->name}}</td>

            <td>@if($asset->status == 'allocated')
            {{ count($asset->assetUser)> 0 ? $asset->assetUser[0]->user->full_name : ''}}
            @endif</td>

            <td>{{$asset->serial_number}}</td>

            <td>{{ $asset->purchased_date ? date_format(new DateTime($asset->purchased_date), 'F d, Y') : '' }}</td>

            <td style="text-align: left;">{{ $asset->value ? number_format($asset->value,2) : ''}}</td>

            <td style="text-align: left;">{{ $asset->depreciation_value ? number_format($asset->depreciation_value,2) : ''}}</td>

            <td>@if($asset->status == 'allocated')
            <label class="label label-success">{{ ucwords(str_replace('_', ' ', $asset->status)) }}</label>
            @endif
            @if($asset->status == 'inactive')
            <label class="label label-info">{{ ucwords(str_replace('_', ' ', $asset->status)) }}</label>
            @endif
            @if($asset->status == 'non_allocated')
            <label class="label label-danger">{{ ucwords(str_replace('_', ' ', $asset->status)) }}</label>
            @endif
            @if($asset->status == 'ticket_raised')
            <label class="label label-warning">{{ ucwords(str_replace('_', ' ', $asset->status)) }}</label>
            @endif</td>

            @foreach($attributes as $attribute)
                <td>{{ array_key_exists($attribute->id, $asset->attributes) ? $asset->attributes[$attribute->id] : '-'  }}</td>
            @endforeach
            <?php $filteredAssetValue += $asset->value == '' ? 0 : $asset->value; ?> 
            <?php $filteredAssetDepreciatedValue += $asset->depreciation_value == '' ? 0 : $asset->depreciation_value; ?> 
        </tr>

        @empty
        <tr>
            <td colspan="7" align="center">No Data Found</td>
        </tr>

    @endforelse
    @if($assets)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Total (INR)</strong></td>
                <td style="text-align: left;"><strong>{{number_format($filteredAssetValue, 2)}}</strong></td>
                <td style="text-align: left;"><strong>{{number_format($filteredAssetDepreciatedValue, 2)}}</strong></td>
                <td></td>
            </tr>
            <tr></tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Total Asset Value (INR)</strong></td>
                <td style="text-align: left;"><strong>{{number_format($assetValue, 2)}}</strong></td>
                <td></td>
                <td></td>
            </tr>
        @endif
</tbody>
</table>