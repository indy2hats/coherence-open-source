<div class="ibox-content ">
	<div class="table-responsive" id="issue-records-table">
		<table class="table table-striped listData">
			<thead>
				<tr>
					<th>Title</th>
					<th>Category</th>
					<th>Project</th>
					<th>Added By</th>
					<th>Added On</th>
					<th>Issue</th>
					<th>Solution</th>
					<th>Actions</th>
				</tr>
				
			</thead>
			<tbody>
				@forelse($issues as $issue)
					<tr>
						<td>{{$issue->title}}</td>
						<td>{{ucwords(str_replace('-', ' ', $issue->category))}}</td>
						<td><a href="{{url('/projects/'.$issue->project->id)}}">{{$issue->project->project_name}}</a></td>
						<td>{{$issue->addedBy->full_name ?? ''}}</td>
						<td>{{$issue->created_at}}</td>
						<td>{!! nl2br($issue->description) !!}</td>
						<td>{!! nl2br($issue->solution) !!}</td>
						<td>
							<a href="{{url('/issue-records/'.$issue->id)}}">
                                <span class="edit-i">
                                    <i class="ri-eye-line" aria-hidden="true"></i>
                                </span>
                            </a>
                            <span class="edit-i">
                                <a data-tooltip="tooltip" data-placement="top" title="Edit"><i data-id="{{$issue->id}}" class="ri-pencil-line edit-issue" aria-hidden="true" style="padding-right: 10px;padding-left:10px"></i>
                                </a>
                            </span>
                            <span class="dlt-i"><a href="#" class="delete_issue_onclick" data-id="{{ $issue->id }}" data-tooltip="tooltip" data-placement="top" title="Delete">
                                            <i data-toggle="modal" data-target="#delete_issue" class="ri-delete-bin-line" aria-hidden="true"></i></a></span>
                        </td>
					</tr>
				@empty
					<tr>
						<td colspan="8" align="center">No Data Found</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
<div class="modal custom-modal fade" id="issue-records-img" role="document">
    <div class="modal-dialog modal-dialog-centered modal-lg" >		
        <div class="modal-content">
            <div class="modal-body p-xxs">
              <img src="" width="100%">
            </div>
        </div>
    </div>
</div>