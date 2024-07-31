@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-7 pull-left">
		<strong>
			<h2 class="page-title">Assets</h3>
		</strong>
	</div>
</div>
<!-- /page title -->
<div class="content-div animated fadeInUp">
	{{-- @include('assets.search') --}}
	<div class="main panel ibox-content">
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Type</th>		
						<!-- <th>Configuration</th> -->
						<th>Serial Number</th>
						<th>Assigned Date</th>
						<th>Status</th>
						@can('view-assets')<th>Action</th>@endcan
					</tr>
				</thead>
				<tbody>
					@if(count($assets) > 0)
					@foreach ($assets as $viewAsset)
					<tr>
						<td>
							{{ $viewAsset->asset->name }}
						</td>
						<td>
							{{ $viewAsset->asset->assetType->name }}
						</td>
						<!-- <td>{{ $viewAsset->asset->configuration }}</td> -->
						<td>{{ $viewAsset->asset->serial_number }}</td>
						<td>{{ $viewAsset->assigned_date ? date_format(new DateTime($viewAsset->assigned_date), 'F d, Y') : '' }}</td>
						<td>@if($viewAsset->status == 'allocated')
							<label class="label label-success">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif
							@if($viewAsset->status == 'inactive')
							<label class="label label-info">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif
							@if($viewAsset->status == 'non_allocated')
							<label class="label label-danger">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif
							@if($viewAsset->status == 'ticket_raised')
							<label class="label label-warning">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif</td>
						@can('view-assets')
						<td>
							<a class="dropdown-item return-asset {{ $viewAsset->status == 'ticket_raised' ? 'hide-link': ''}}" href="#" data-toggle="modal"
								data-target="#return_asset" data-id="{{ $viewAsset->id }}" data-tooltip="tooltip"
								data-placement="top" title="Return"><i class="ri-arrow-go-back-line"></i>
							</i></a>
							@can('ticket-assets')
							<a class="dropdown-item ticket-raise-asset {{ $viewAsset->status == 'ticket_raised' ? 'hide-link': ''}}" href="#" data-toggle="modal"
								data-target="#ticket_raise_asset" data-id="{{ $viewAsset->id }}" data-tooltip="tooltip"
								data-placement="top" title="Report Asset Issue"><i class="ri-ticket-line"></i>
							</a>
							@endcan
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
				{{$assets->links()}}
			</div>
	</div>
	</div>
</div>
@include('assets.return')
@include('assets.ticket-raise')
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/assets/script-min.js') }}"></script>
@endsection