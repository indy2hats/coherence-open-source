<div class="ibox-content">
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>
					<th>Date</th>				
					<th>Checklist Title</th>
					<th>Note</th>
					<th>Checklists</th>
				</tr>
			</thead>
			<tbody>
				@forelse($list as $item)
					<tr>
						<td>{{$item->added_on}}</td>
						<td>{{$item->title}}</td>
						<td>{{$item->note?$item->note:'-'}}</td>
						<td>
							@foreach(unserialize($item->checklists) as $key => $value)
								<p>{{$key}} 
									@if($value)
										<span style="color:#7cb342;"><i class=" fa fa-check" aria-hidden="true"></i></span>
									@else
										<span style="color:#e53935;"><i class=" fa fa-times" aria-hidden="true"></i></span>
									@endif
								</p>
							@endforeach
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" align="center">No Data Found</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>