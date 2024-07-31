

            <div class="table table-hover listTable">

                <table class="table table-hover rejectionTable" style="width:100%">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>User</th>

                            <th>Severity</th>

                            <th>Reason</th>

                            <th>Comments</th>

                            <th></th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($taskRejections as $list)
                        @php
                        $qaIssuesList = $list->reason;
                        $issueList = explode('_',$qaIssuesList)
                        @endphp
                        <tr>
                            <td>{{date_format (new DateTime($list->updated_at), 'd/m/Y')}}</td>
                            <td>{{$list->users->full_name}}</td>
                            <td class="project-completion text-center">
                                <span @if($list->severity == 'Critical')class="label label-danger block"
                                    @elseif($list->severity == 'High')class="label label-warning
                                    block" @elseif($list->severity == 'Medium')class="label label-info block"
                                    "@elseif($list->severity == 'Low')class="label label-plain block"@endif>{{$list->severity}}</span>
                            </td>
                            <td>
                                @foreach($qaIssues as $issue)
                                @if(in_array($issue->id,$issueList))
                                <span class="label label-plain block" style="margin: 5px;">
                                {{$issue->title}} </span>
                                @endif
                                @endforeach
                            </td>
                            <td>{!! $list->comments !!}</td>
                            <td>
                            @if((auth()->user()->id == $list->rejected_by) && (auth()->user()->designation->name == 'Quality Analyst'))
                            <a class="delete-task-rejection" data-toggle="modal" data-target="#delete-task-rejection" data-id="{{$list->id}}" data-tooltip="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line delete-rejection"></i></a>
                            @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>