<div class="flexmasonry-layout">
@foreach($data as $item)
                <div class="board-item">

                    <div class="new-col">


                    <div class="ibox">

                        <div class="ibox-content">

                            <h3 class="text-center">{{$item['type']}}</h3>


                            <ul class="sortable-list connectList agile-list ui-sortable" data-status="{{$item['type']}}" id="{{preg_replace('/\s+/', '_',strtolower($item['type']))}}">
                                @foreach($item['tasks'] as $task)
                                @if(in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles')))
                                    {{ $estimatedTime = $task['actual_estimated_time'] }}
                                @else
                                    {{ $estimatedTime = $task['estimated_time'] }}
                                @endif  
                                <li class="@if($task['priority'] == 'High') danger-element @elseif($task['priority']  == 'Medium') warning-element @elseif($task['priority']  == 'Critical')  critical-element @else info-element @endif single-click-event" id="{{$task['task_id']}}">

                                    <strong >{{ \Illuminate\Support\Str::limit($task['task_title'], 40, $end='...') }}</strong>

                                    <div class="agile-detail">

                                        <i class="ri-time-line"></i> {{$task['deadline']}}

                                        <span class="btn btn-xs btn-white">Estimated: {{$estimatedTime}}hr/Taken: {{ number_format($task['time_spent'],2)}}hr</span>


                                    </div>

                                </li>
                                @endforeach



                            </ul>

                        </div>

                    </div>
                </div>

                </div>
@endforeach
</div>