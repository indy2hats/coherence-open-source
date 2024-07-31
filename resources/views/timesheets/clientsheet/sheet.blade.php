<div class="ibox-content">
	<div class="row">
		<table class="table table-striped listData">
			<thead>
				<tr>
					<th>Project</th>
					@for ($i = 1; $i < 8; $i++)
						<th @if(in_array($i,$holidays)) class="alert alert-danger alert-dismissable" @endif  @if($days['current_week']==$days['day'.$i]) class="alert alert-success" @endif>{{$days['day'.$i]}}</th>
					@endfor
					<th>Total_Hours</th>
				</tr>
			</thead>
			<tbody>
				@foreach($dataset as $task)
				<tr>
					<td width="200">
						<a style="cursor:pointer;" href="{{ url('projects/'.$task['project_id'])}}"><input class="form-control inputBox project-sheet-data-link" type="text" value="{{$task['project_name']}}" readonly style="cursor:pointer;"></a>
					</td>
					@for ($i = 1; $i < 8; $i++) <td @if(in_array($i,$holidays)) class="alert alert-danger alert-dismissable" @endif>
						<div>
							<input class="form-control inputBox data-field" type="text" value="{{($task['day'.$i]>0)?floor($task['day'.$i]/60).'h '.($task['day'.$i]%60).'m':''}}">
						</div>
						</td>
						@endfor
						<td class="total-field">{{floor(($task['day1']+$task['day2']+$task['day3']+$task['day4']+$task['day5']+$task['day6']+$task['day7'])/60).'h '.(($task['day1']+$task['day2']+$task['day3']+$task['day4']+$task['day5']+$task['day6']+$task['day7'])%60).'m'}}
						</td>
				</tr>
				@endforeach
				@if($dataset)
				<tr class="text-center">
					<td></td>
					<td><strong>{{($total['Mon']>0)?floor($total['Mon']/60).'h '.($total['Mon']%60).'m':'0h 0m'}}</strong></td>
					<td><strong>{{($total['Tue']>0)?floor($total['Tue']/60).'h '.($total['Tue']%60).'m':'0h 0m'}}</strong></td>
					<td><strong>{{($total['Wed']>0)?floor($total['Wed']/60).'h '.($total['Wed']%60).'m':'0h 0m'}}</strong></td>
					<td><strong>{{($total['Thu']>0)?floor($total['Thu']/60).'h '.($total['Thu']%60).'m':'0h 0m'}}</strong></td>
					<td><strong>{{($total['Fri']>0)?floor($total['Fri']/60).'h '.($total['Fri']%60).'m':'0h 0m'}}</strong></td>
					<td><strong>{{($total['Sat']>0)?floor($total['Sat']/60).'h '.($total['Sat']%60).'m':'0h 0m'}}</strong></td>
					<td><strong>{{($total['Sun']>0)?floor($total['Sun']/60).'h '.($total['Sun']%60).'m':'0h 0m'}}</strong></td>
					<td><strong>{{($total['total']>0)?floor($total['total']/60).'h '.($total['total']%60).'m':'0h 0m'}}</strong></td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>