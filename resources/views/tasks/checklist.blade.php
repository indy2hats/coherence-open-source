@if($task->checklists->count())
                    @php $checklistCategories = $task->checklists->groupBy('category.title'); @endphp

                    @if(in_array(auth()->user()->id, $task->users->pluck('id')->toArray()) && auth()->user()->id != $task->reviewer_id)
                        <div class="row" style="margin-top: 5px">
                            <p style="padding-left: 15px;">
                                Checklists :</p>
                            <div class="payment-card" >
                                <ul class="todo-list small-list ui-sortable">
                                    @foreach($checklistCategories as $category => $checklists)
                                        <h4>{{$category}}</h4>
                                        @foreach($checklists as $checklist)
                                            <li style="background: #fff;">
                                                <a data-status="{{$checklist->pivot->developer_status}}" data-type="developer" data-id="{{$checklist->pivot->id}}" href="#" class="checklist-link"><i class="fa @if($checklist->pivot->developer_status) fa-check-square @else fa-square-o @endif"></i> </a>
                                                <span class="m-l-xs @if($checklist->pivot->developer_status) todo-completed @endif">{{$checklist->title}}</span>
                                                @if($checklist->pivot->reviewer_status == 0)
                                                    <small class="label label-success">Not Reviewed</small>
                                                @endif
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if(in_array(auth()->user()->id, $task->users->pluck('id')->toArray()) && auth()->user()->id == $task->reviewer_id)
                        <div class="row" style="margin-top: 5px">
                            <p style="padding-left: 15px;">
                                Checklists :</p>
                            <div class="payment-card" >
                                <ul class="todo-list small-list ui-sortable">
                                    @foreach($checklistCategories as $category => $checklists)
                                        <h4>{{$category}}</h4>
                                        @foreach($checklists as $checklist)
                                            <li style="background: #fff;">
                                                <a data-status="{{$checklist->pivot->reviewer_status}}" data-type="reviewer" data-id="{{$checklist->pivot->id}}" href="#" class="checklist-link"><i class="fa @if($checklist->pivot->reviewer_status) fa-check-square @else fa-square-o @endif"></i> </a>
                                                <span class="m-l-xs @if($checklist->pivot->reviewer_status) todo-completed @endif">{{$checklist->title}}</span>
                                                @if($checklist->pivot->developer_status == 0)
                                                    <small class="label label-success">Developer Not updated</small>
                                                @else
                                                    <small class="label label-success">Developer updated</small>
                                                @endif
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @can('manage-tasks')
                        @if(auth()->user()->id != $task->reviewer_id)
                            <div class="row admin-checklist-row" style="margin-top: 5px">
                                <div class="payment-card" >
                                    <ul class="todo-list small-list ui-sortable">
                                        @foreach($checklistCategories as $category => $checklists)
                                            <h4>{{$category}}</h4>
                                            @foreach($checklists as $checklist)
                                                <li class="admin-checklist" style="background: #fff;">
                                                    <span class="m-l-xs">{{$checklist->title}}</span>
                                                    @if($checklist->pivot->developer_status == 0)
                                                        <small class="dev-pending label label-success">Developer Not Updated</small>
                                                    @else
                                                        <small class="label label-success">Developer Updated</small>
                                                    @endif
                                                    @if($checklist->pivot->reviewer_status == 0)
                                                        <small class="rev-pending label label-success">Not Reviewed</small>
                                                    @else
                                                        <small class="label label-success">Reviewed</small>
                                                    @endif
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    @endcan
                @endif