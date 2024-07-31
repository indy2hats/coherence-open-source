<div class="ibox-content">
    
            <div class="table-responsive">

                <table class="table table-striped overdueTaskTable">
        
                    <thead>

                        <tr>
                            <th>Task</th>

                            <th>Project</th>

                            <th>Assigned To</th>

                            <th class="estimated">Estimate Hours</th>

                            <th class="spend">Hours Spent</th>

                            <th>Deadline</th>

                        </tr>

                    </thead>
                   
                    <tbody>
                    @foreach($overduetasks as $task)
                        <tr>
                            <td><a href="/tasks/{{ $task->id }}">{{ $task->title }}</a></td>

                            <td><a href="/tasks/{{ $task->id }}">{{ $task->project->project_name}}</a></td>

                            <td>@foreach($task->users as $user)
                            <a href="">
                                        {{$user->full_name}}
                            </a>
                                        @if(!$loop->last)
                                        {{', '}}
                                        @endif
                                        @endforeach</td>
                        
                            <td>
                            @if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                {{ floor(($task->actual_estimated_time*60)/60).'h '.(($task->actual_estimated_time*60)%60).'m'}}
                            @else
                                {{ floor(($task->estimated_time*60)/60).'h '.(($task->estimated_time*60)%60).'m'}}
                            @endif    
                            </td>

                            <td>{{ floor(($task->time_spent*60)/60).'h '.(($task->time_spent*60)%60).'m'}}</td>

                            <td>{{ $task->end_date_format}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                   
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th>Total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>

            </div>

        </div>