<div class="table-responsive">
		    <div class="col-md-12">
		        <div>
		            <table class="table pre-table">
		                <thead>
		                    <tr>
		                    	<th>Name</th>
		                    	<th>Date</th>
		                        <th>Session</th>
		                        <th>Reason</th>
		                        <th>Status</th>
		                        <th>Action Taken By</th>                        
		                        <th>Remarks</th>
		                        <th>Action</th>
		                    </tr>
		                </thead>
		                <tbody>
		                    @foreach ($previous as $item)
		                    
		                        <tr>
		                      	<td>{{$item->users->full_name}}</td>
		                        <td>{{$item->date}}</td>
		                        <td>{{$item->session}}</td>
		                        <td>{!! strip_tags($item->reason) !!}</td>
		                        <td>
		                            @if ($item->status=='Approved')
		                                <span class="badge badge-success">Approved</span>
		                            @elseif($item->status == 'Rejected')
		                                <span class="badge badge-danger">Rejected</span>
		                            @else
		                                <span class="badge badge-info">Pending</span>
		                            @endif
		                        </td>
		                        <td>{{$item->user_approved->full_name}}</td>
                        		<td>{!! strip_tags($item->reason_for_rejection) !!}</td>
		                       <td>
                                  <a class="edit-application" data-id="{{$item->id}}"><i class="ri-pencil--fill"></i></a>
                                <a style="margin-left:10px;" class="delete-application" data-toggle="modal" data-target="#delete_application" data-id="{{$item->id}}"><i class="ri-delete-bin-line"></i></a>

                                </td>
		                    </tr>
		                    @endforeach
		                </tbody>
		            </table>
		        </div>
		    </div>

		</div>