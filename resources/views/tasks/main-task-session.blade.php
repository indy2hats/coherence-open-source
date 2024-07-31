<div class="task-session">
    <div class="m-b-lg">
        <div class="session-title">Sessions</div>
        <div class="d-inline-flex pull-right text-right">
                

                    @unlessrole('client')
                    @if(
                    (
                    (
                    auth()->user()->cannot('manage-tasks') && $task->users->contains('id', auth()->user()->id) &&
                    (
                    (
                    auth()->user()->designation->name == 'Quality Analyst' && ($task->status == 'Development Completed'
                    || $task->status == 'Under QA')
                    ) ||
                    (
                    auth()->user()->designation->name != 'Quality Analyst' && ($task->status == 'In Progress' ||
                    $task->status == 'Backlog')
                    )
                    )
                    ) ||
                    (
                    auth()->user()->can('manage-tasks') && $task->users->contains('id', auth()->user()->id)
                    )
                    )
                    && $task->status != 'Done'
                    )

                    <a href="#" class="btn btn-success ml-10" data-toggle="modal"
                        data-target="#add-session"><i class="ri-add-line"></i> Add
                        Session</a>
                    @endif
                    @endunlessrole
                </div>
    </div>
    <div class="border-top p-h-m">

            <div class="d-flex flex-center-center flex-md-vertical">
                <div class="d-inline-flex flex-center-start flex-fill flex-md-wrap">

                    <div class="select-box">
                        @can('manage-tasks')
                        <select class="chosen-select form-control" id="userSession" name="userSession">
                            <option value="" {{request()->userSession == '' ? 'selected' : ''}}>Select User</option>
                            @foreach($userList as $user)
                            <option value="{{$user->id}}" {{request()->userId == $user->id ? 'selected' :
                                ''}}>{{$user->full_name}}</option>
                            @endforeach
                        </select>
                        @endcan
                    </div>
                    <div class="select-box">
                        @can('manage-tasks')
                        <select class="chosen-select form-control" id="userSessionType" name="userSessionType">
                            <option value="" {{request()->userSessionType == '' ? 'selected' : ''}}>Select Session Type
                            </option>
                            @foreach(\App\Models\SessionType::all() as $sessionType)
                            <option value="{{$sessionType->slug}}" {{request()->type == $sessionType->slug ? 'selected'
                                : ''}}>{{$sessionType->title}}</option>
                            @endforeach
                        </select>
                        @endcan
                    </div>
                    <div class="select-box">
                        <input class="form-control active" type="text" id="daterange" name="daterange"
                            placeholder="Select Date Range" value="{{ request()->daterange }}"
                            autocomplete="off">
                    </div>
                    
                    @can('manage-tasks')
                    <button href="#" class="btn btn-info" id="export-session-csv"><i
                            class="ri-download-line"></i> Export Data </button>
                    @endcan
                
                </div>
             
            </div>
    </div>

<div class="table-wrapper">

    <div class="table-responsive">


        <table class="table table-hover sessionTable">

            <thead>

                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Hours</th>
                    <th>Type</th>
                    <th>Comments</th>
                    @if(auth()->user()->cannot('manage-tasks') || $task->users->contains('id', auth()->user()->id))
                    <th>Actions</th>
                    @endif
                </tr>

            </thead>

            <tbody>
                @forelse($taskSession as $list)
                <tr>
                    <td>{{$list->user->full_name ?? 'Deleted User' }}</td>
                    <td>{{\Carbon\Carbon::parse($list->created_at)->format('M d, Y')}}</td>
                    <td data-total="{{$list->total}}" data-start="{{$list->start_time}}"
                        @if(Carbon\Carbon::parse($list->start_time)->format('d/m/Y') == date('d/m/Y') &&
                        $list->current_status == "started") class="time" @endif><span class="timer">{{floor($list->total
                            / 60 ).'h '.($list->total % 60).'m'}}</span></td>
                    <td>{{ucwords(str_replace('-', ' ', $list->session_type))}}</td>
                    <td>{!! nl2br(trim($list->comments)) !!}</td>
                    @if((auth()->user()->cannot('manage-tasks') || $task->users->contains('id', auth()->user()->id))
                    && $list->user_id == auth()->user()->id)
                    <td>
                        @php
                        $date = \Carbon\Carbon::parse($list->created_at)->addSeconds(1);
                        $secondLastDay = \Carbon\Carbon::parse(App\Services\DayService::getNthLastWorkingday(2));
                        $canManage = $date->gte($secondLastDay);
                        @endphp
                        @if($canManage)
                        <a data-tooltip="tooltip" data-placement="top" title="Edit"><i data-id="{{$list->id}}"
                                class="ri-pencil-line edit-task-session" aria-hidden="true"></i></a> | <a
                            data-tooltip="tooltip" data-placement="top" title="Delete"><i data-toggle="modal"
                                data-target="#delete_task_session" class="ri-delete-bin-line" aria-hidden="true"
                                data-id="{{$list->id}}" id="delete-session"></i></a>
                        @endif
                    </td>
                    @else
                    <td></td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" align="center">
                        No Sessions Added
                    </td>
                </tr>
                @endforelse

                @if($taskSession)
                <tr>
                    <td></td>
                    <td><strong>Total</strong></td>
                    <td><strong id="total_time">{{floor($total / 60 ).'h '.($total % 60).'m'}}</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            </tbody>

        </table>
        {{ $taskSession->appends(request()->all())->links() }}
    </div>



</div>
</div>
