@foreach($myChecklists as $list)
<div class="col-md-12 col-lg-6">
	<div class="ibox float-e-margins no-borders">
		<div class="ibox-title">
			<h5>{{$list->title}}</h5>
			<div class="ibox-tools">
				@can('manage-leave')<i class="ri-share-line share-with" style="padding-right: 10px;" data-toggle="modal" data-target="#share_with" data-id="{{$list->id}}"></i>@endcan
				<i class="ri-add-line add_item" style="padding-right: 10px;" data-toggle="modal" data-target="#add_item" data-item-id="{{$list->id}}"></i>

				<i class="ri-pencil-line edit-button" style="padding-right: 10px;" data-toggle="modal" data-target="#edit_item" data-id="{{$list->id}}" data-title="{{$list->title}}"></i>

				<i class="ri-delete-bin-line delete_item_onclick" data-toggle="modal" data-target="#delete_item" data-id="{{$list->id}}"></i>
				<a class="collapse-link-user"><i class="fa fa-chevron-down"></i></a>
			</div>
		</div>
		<div class="ibox-content no-padding" style="display: none;">
			 <table class="table table-responsive">
		 		<tbody>
		 		@foreach($list->children()->get() as $item)
			 		<tr>
	                    <td>{{$item->title}}</td>
	                    <td class="text-right">
	                    	<i class="ri-pencil-line edit-button" style="padding-right: 10px;" data-toggle="modal" data-target="#edit_item" data-id="{{$item->id}}" data-title="{{$item->title}}"></i>

	                    	<i class="ri-delete-bin-line delete_item_onclick" data-toggle="modal" data-target="#delete_item" data-id="{{$item->id}}"></i>
	                    </td>
	                </tr>
    			@endforeach
            	</tbody>
            </table>
        </div>
    </div>
</div>
@endforeach


