<div class="ibox-content">
	<div class="row">
		<table class="table table-striped timesheet-wrap listData" data-id="[{{implode(',',$holidays)}}]">
			<thead>
				<tr>
					<th>Project</th>
					<th>Task</th>
					@for ($i = 1; $i < 8; $i++)
						<th @if(in_array($i,$holidays)) class="alert alert-danger alert-dismissable bnone" @endif @if($days['current_week']==$days['day'.$i]) class="alert alert-success bnone" @endif>{{$days['day'.$i]}}</th>
					@endfor
					<th>Total Hours</th>
				</tr>
				<tr class="text-center">
					<td></td>
					<td></td>
					<td><strong id="col_1">{{($total['Mon']>0)?floor($total['Mon']/60).'h '.($total['Mon']%60).'m':'0h 0m'}}</strong></td>
					<td><strong id="col_2">{{($total['Tue']>0)?floor($total['Tue']/60).'h '.($total['Tue']%60).'m':'0h 0m'}}</strong></td>
					<td><strong id="col_3">{{($total['Wed']>0)?floor($total['Wed']/60).'h '.($total['Wed']%60).'m':'0h 0m'}}</strong></td>
					<td><strong id="col_4">{{($total['Thu']>0)?floor($total['Thu']/60).'h '.($total['Thu']%60).'m':'0h 0m'}}</strong></td>
					<td><strong id="col_5">{{($total['Fri']>0)?floor($total['Fri']/60).'h '.($total['Fri']%60).'m':'0h 0m'}}</strong></td>
					<td><strong id="col_6">{{($total['Sat']>0)?floor($total['Sat']/60).'h '.($total['Sat']%60).'m':'0h 0m'}}</strong></td>
					<td><strong id="col_7">{{($total['Sun']>0)?floor($total['Sun']/60).'h '.($total['Sun']%60).'m':'0h 0m'}}</strong></td>
					<td><strong id="col_total">{{($total['total']>0)?floor($total['total']/60).'h '.($total['total']%60).'m':'0h 0m'}}</strong></td>
				</tr>
			</thead>
			<tbody>
				@foreach($dataset as $task)
				<tr id="tr_{{$task['task_id']}}">
					<td width="200">
						<select class="chosen-select" id="select_project" name="select_project" onselect="selectTask()">
							<option value="{{$task['project_id']}}">{{$task['project_name']}}</option>
						</select>
					</td>
					<td width="200">
						<select class="chosen-select" id="select_task" name="select_task">
							<option title="{{$task['task_title']}}" value="{{$task['task_id']}}" selected>{{ str_limit($task['task_title'], 30)}}</option>
						</select>
					</td>
					
						@for ($i = 1; $i < 8; $i++) <td @if(in_array($i,$holidays)) class="alert alert-danger alert-dismissable bnone"  @endif>
						<div>
							<input class="form-control inputBox" @if($task['editable_'.$i] == 0 ) disabled @else data-task_id="{{$task['task_id']}}" data-date="{{$days['day'.$i.'_date']}}" data-session_id="{{$task['day'.$i.'_id']}}" @endif type="text" value="{{($task['day'.$i]>0)?floor($task['day'.$i]/60).'h '.($task['day'.$i]%60).'m':''}}" data-value="{{($task['day'.$i]>0)?floor($task['day'.$i]/60).'h '.($task['day'.$i]%60).'m':''}}" data-start="{{$task['task_start_date']}}" style="text-align: center;height: 30px;border-radius: 5px;box-shadow: 0 0 5px rgba(81, 203, 238, 1);cursor:pointer; background: #fff;" data-toggle="tooltip" data-placement="bottom" title="Format: Eg- 1.5 = 1h 30m" readonly>
						</div>
						</td>
						@endfor
						<td id="td_{{$task['task_id']}}" style="text-align: center;padding-top: 15px;font-weight: bolder;font-size: 15px">{{floor(($task['day1']+$task['day2']+$task['day3']+$task['day4']+$task['day5']+$task['day6']+$task['day7'])/60).'h '.(($task['day1']+$task['day2']+$task['day3']+$task['day4']+$task['day5']+$task['day6']+$task['day7'])%60).'m'}}
						</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>