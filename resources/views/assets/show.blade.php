<div class="row">
    <div class="col-md-12 col-lg-8">
        <div class="heading-inline">
            <h3 class="no-margins" style="text-align:left;float:left;">Asset Name :
                <strong>{{$asset->name}}</strong>
            </h3>
            <hr style="clear:both;" />
        </div>
        <h2 data-id="{{$asset->id}}" class="asset-title" id="asset-id">
            <strong>{{$asset->serial_number}}</strong>
        </h2>
        <div id="ticket_status_edit" class="modal custom-modal fade" role="dialog" tabindex="-1">
        </div>
        <div id="ticket_raise_edit_asset" class="modal custom-modal fade" role="dialog" tabindex="-1">
        </div>
        <div class="m-b-md pt-15 tabs-container">
    
            <ul class="nav nav-tabs pt-10">
                <li class=""><a data-toggle="tab" href="#tickets">Tickets</a></li>
                <li class="active"><a data-toggle="tab" href="#history">History</a></li>
                <li class=""><a data-toggle="tab" href="#documents">Documents</a></li>
            </ul>
    
            <div class="tab-content">
                <div id="tickets" class="tab-pane">
                    <div class="panel-body">
                        @include('assets.all-tickets-rasied')
                    </div>
                </div>
                <div id="history" class="tab-pane active">
                    <div class="panel-body">
                        @include('assets.asset_users')
                    </div>
                </div>
                <div id="documents" class="tab-pane">
                    <div class="panel-body documents-assets-div">
                        @include('assets.documents')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="ri-information-line"></i> <strong>Asset Details</strong>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-border">
                    <tbody>
                        <tr>
                            <td><strong>Type </strong></td>
                            <td class="text-left">
                                <strong>{{$asset->assetType->name ?? ''}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Name </strong></td>
                            <td class="text-left">
                                <strong>{{$asset->name}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Purchased Date </strong></td>
                            <td class="text-left"><strong>{{ $asset->purchased_date ? date_format(new DateTime($asset->purchased_date), 'F d, Y') : '' }}</strong></a></td>
                        </tr>
                        <tr>
                            <td><strong>Asset Value (INR) </strong></td>
                            <td class="text-left">
                                <strong>{{ $asset->value ? number_format($asset->value,2) : ''}} </strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Warranty Until</strong></td>
                            <td class="text-left">
                                <strong>{{ $asset->warranty ? date_format(new DateTime($asset->warranty), 'F d, Y') : '' }}</strong>
                            </td>
                        </tr>
                        @if($asset->assetAttributeValues)
                            @foreach($asset->assetAttributeValues as $item)
                            <tr>
                                <td><strong>{{ $item->attribute_value->attribute->name ?? ''}}</strong></td>
                                <td class="text-left">
                                    <strong>{{ $item->attribute_value->value ?? ''}}</strong>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td><strong>Other details/Notes</strong></td>
                            <td class="text-left">
                                <strong>{!! nl2br(trim($asset->configuration)) !!}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Serial Number </strong></td>
                            <td class="text-left">
                                <strong>{{ $asset->serial_number }} </strong>
                            </td>
                        </tr>
                        
                        <tr>
                            <td><strong>Vendor </strong></td>
                            <td class="text-left"><strong>{{ $asset->assetVendor->name ?? '' }}</strong></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Status</strong></td>
                            <td class="text-left">
                                <strong>
                                    @if($asset->status == 'allocated')
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
                                    @endif
                                </strong>
                            </td>
                        </tr>
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>