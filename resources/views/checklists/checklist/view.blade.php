@foreach($updatedList as $list)
    <?php
    $set = unserialize($list->checklists);
    $item = App\Models\TaxonomyList::with('children')->where('user_id',Auth::user()->id)->where('id',$list->parent_id)->first();
    ?>
    @if (($item!==null))
        <div class="col-md-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{$item->title}}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link-user"><i class="epms-icon--1x ri-arrow-up-s-line"></i></a>
                    </div>
                </div>
                <div class="ibox-content no-padding" style="">
                    <form id="checklist_update" method="post" action="{{route('updateUserChecklist')}}">
                    <ul class="todo-list small-list ui-sortable">
                       
                    @foreach($item->children()->get() as $each)
                            <li style="background:#fff;">
                                <input  type="checkbox" @if(isset($set[$each->id]) && $set[$each->id] == 1) checked @endif value="{{$each->id}}" name="checklists[]" class="i-checks"/>
                                <span class="m-l-xs" style="font-size: 13px;">{{$each->title}}</span>
                            </li>
                        @endforeach
                    </ul>
                    <input type="hidden" name="cat_id" value="{{$list->parent_id}}">
                    <input type="hidden" name="update_id" value="{{$list->id}}">
                    <a href="#" class="btn btn-w-m btn-primary save" data-id="{{$list->id}}" data-toggle="modal" data-target="#save_list" style="margin: 10px;"> Mark as Complete</a>
                </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@foreach($myChecklists as $list)
@if(!in_array($list->id,$updatedList->pluck('parent_id')->toArray()))
<div class="col-md-6">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5>{{$list->title}}</h5>
			<div class="ibox-tools">
				<a class="collapse-link-user"><i class="fa fa-chevron-down"></i></a>
			</div>
		</div>
		<div class="ibox-content no-padding" style="display: none;">
			<form id="checklist_update" method="post" action="{{route('updateUserChecklist')}}">
			 <ul class="todo-list small-list ui-sortable">
                @foreach($list->children()->get() as $item)
                    <li style="background:#fff;">
                        <input  type="checkbox" value="{{$item->id}}" name="checklists[]" class="i-checks" />
                        <span class="m-l-xs" style="font-size: 13px;">{{$item->title}}</span>
                    </li>
                @endforeach
            </ul>
            <input type="hidden" name="cat_id" value="{{$list->id}}">
            <input type="hidden" name="update_id" value="">
        </form>
        </div>
    </div>
</div>
@endif
@endforeach


