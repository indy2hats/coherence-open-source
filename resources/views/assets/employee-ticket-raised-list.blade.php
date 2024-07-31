@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-7 pull-left">
		<strong>
			<h2 class="page-title">Tickets</h3>
		</strong>
	</div>
</div>
<!-- /page title -->
<div class="content-div animated fadeInUp">
	@include('assets.employee-ticket-search')
	<div class="main panel ibox-content">
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Type</th>
						<th>Name</th>
						<th>Serial Number</th>
						<th>Type of Complaint</th>
						<th>Reason</th>
						<th>Created Date</th>
						<th>Status</th>
						<th>Resolving Status</th>
						@can('view-assets')<th>Action</th>@endcan
					</tr>
				</thead>
				<tbody>
					@if(count($assets) > 0)
					@foreach ($assets as $viewAsset)
					<tr>
						<td>
							{{ $viewAsset->asset->assetType->name ?? ''}}
						</td>
						<td>
							{{ $viewAsset->asset->name ?? ''}}
						</td>
						<td>{{ $viewAsset->asset->serial_number ?? ''}}</td>
						<td>{{ $viewAsset->type ?? ''}}</td>
						<td>{!! nl2br(trim($viewAsset->issue)) !!}</td>
						<td>{{ $viewAsset->created_at ? date_format(new DateTime($viewAsset->created_at), 'F d, Y') : '' }}</td>
						<td>@if($viewAsset->status == 'open')
							<label class="label label-danger">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif
							@if($viewAsset->status == 'closed')
							<label class="label label-success">{{ ucwords(str_replace('_', ' ', $viewAsset->status)) }}</label>
							@endif
							</td>
							<td>
								{{ $viewAsset->ticketStatus->title ?? ''}}
							</td>
						@can('ticket-assets')
						<td>
							<a class="dropdown-item ticket-raise-edit-asset {{ $viewAsset->status_id != NULL ? 'hide-link': ''}}" href="#" data-id="{{ $viewAsset->id }}"
								data-tooltip="tooltip" data-placement="top" title="Edit"> <i
									class="ri-pencil-line m-r-5"></i></a>
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
<div id="ticket_raise_edit_asset" class="modal custom-modal fade" role="dialog">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/assets/script-min.js') }}"></script>
@endsection