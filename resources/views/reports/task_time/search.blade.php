<div class="ibox-content panel mb10">
	<form action="{{route('taskTimeSearch')}}" method="post" autocomplete="off" id="task-search-form">
	@csrf
		
	<div class="row filter-row" style="padding-top: 10px;padding-bottom: 10px">
		<div class="col-sm-6 col-md-3" style="margin-bottom: 10px;">
			<select class="chosen-select filter" id="search_task" name="taskId">
				<option value="">Select Task</option>
                @foreach ($tasks as $task)
                <option value="{{$task->id}}" {{ request()->taskId == $task->id ? 'selected': '' }}>{{$task->title}}</option>
                @endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-2" style="margin-bottom: 10px;">
            <select class="chosen-select task-filter" id="search_assigned_user" name="userId">
                <option value="">Select User</option>  
                @foreach ($users as $user)
                    <option value="{{$user->id}}" {{ request()->userId == $user->id ? 'selected': '' }}>{{$user->full_name}}</option>
                @endforeach  
            </select>
		</div>
		<div class="col-sm-6 col-md-3" style="margin-bottom: 10px;">
			<div class="form-group">
                <input class="form-control active p-xs task-filter" type="text" id="daterange" name="daterange"  value="{{request()->daterange}}">
			</div>
    	</div>
        <div class="col-sm-2 col-md-2" style="margin-bottom: 10px;">
            <div class="input-group">
                <span class="input-group-addon">>=</span>
                <input type="text" class="form-control discount_percent task-filter" name="greaterThan" id="greater_than" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            </div>
        </div>
        <div class="col-sm-2 col-md-2" style="margin-bottom: 10px;">
            <div class="input-group">
                <span class="input-group-addon"><=</span>
                <input type="text" class="form-control discount_percent task-filter" name="lessThan" id="less_than" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            </div>
        </div>
	</div>
    <div class="row">
        <div class="col-sm-12 col-md-12 text-right">
            <button class="btn  btn-w-m btn-info filter-reset ">Clear Filters</button>
    	</div>
	</div>
    
	</form>
</div>
