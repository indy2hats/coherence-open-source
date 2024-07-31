
<div class="col-sm-12">
    <div class="form-group">
        <label for="">Status</label>
        <select class="chosen-select" id="task_status" name="task_status">
        @if(!Auth::user()->hasRole('client'))
            <option value="Backlog" {{$task->status == 'Backlog' ? 'selected' : ''}}
                {{ ((auth()->user()->designation->name == 'Quality Analyst' && auth()->user()->cannot('manage-tasks')) || $task->status == 'Done' || $task->status == 'Under QA') ? 'disabled':'' }} >Backlog</option>
            <option value="In Progress" {{$task->status == 'In Progress' ? 'selected' : ''}}
                {{ ((auth()->user()->designation->name == 'Quality Analyst' && auth()->user()->cannot('manage-tasks')) || $task->status == 'Under QA' )? 'disabled' : ''}}>In 
                Progress</option>
            <option value="Development Completed"
                {{$task->status == 'Development Completed' ? 'selected' : ''}}
                {{ ((auth()->user()->designation->name == 'Quality Analyst' && auth()->user()->cannot('manage-tasks')) || $task->status == 'Done' || $task->status == 'Under QA') ? 'disabled': '' }}>Development
                Completed</option>
            <option value="Under QA" {{$task->status == 'Under QA' ? 'selected' : ''}}
            {{ (((auth()->user()->designation->name != 'Quality Analyst' || $task->status != 'Development Completed') || $task->status == 'Done')) ? 'disabled': '' }}
                >Under QA
            </option>
            <option value="Done" {{$task->status == 'Done' ? 'selected' : ''}} 
               {{ auth()->user()->cannot('manage-tasks') ? 'disabled' : '' }}>Done</option>
            <!-- @can('manage-tasks')
            <option value="On Hold" {{$task->status == 'On Hold' ? 'selected' : ''}}>On Hold
            </option>
            <option value="Awaiting Client"
                {{$task->status == 'Awaiting Client' ? 'selected' : ''}}>
                Awaiting Client
            </option>
            <option value="Client Review" {{$task->status == 'Client Review' ? 'selected' : ''}}>
                Client
                Review</option>
            <option value="Done" {{$task->status == 'Done' ? 'selected' : ''}}>Done</option>
            @endcan -->
        @else
        <option >{{$task->status}}</option>
        @endif 

        </select>
    </div>
</div>