@if ($taskRejectionStatus)
<div class="rejections">
    @include('employeetasks.showrejections')
</div>
@endif
<div class="row" style="padding-top: 25px">
    <div class="col-lg-12">
        <div class="ibox-title">
            <div class="row">
                <div class="col-md-8">
                    <strong>
                        <h3 class="page-title" style="font-size: 20px">Sessions</h3>
                    </strong>
                </div>
                <div class="col-md-4 text-right">
                    <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add-session"><i class="ri-add-line"></i> Add Session</a>
                </div>
            </div>
        </div>
            @if($taskSession)
        <div class="ibox-content">

            <div class="table table-hover listTable">

                <table class="table table-hover sessionTable" style="width:100%">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>Hours</th>

                            <th>Comment</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>
                        @foreach($taskSession as $list)
                        <tr>
                            <td>{{date_format (new DateTime($list->created_at), 'd-M-Y, l')}}</td>
                            <td>{{floor($list->total / 60 ).'h '.($list->total % 60).'m'}}</td>
                            <td>{{strlen($list->comments)>50?substr(($list->comments), 0,50).' ...':$list->comments}}</td>
                            <td><a data-tooltip="tooltip" data-placement="top" title="Edit"><i data-toggle="modal" data-id="{{$list->id}}" data-target="#edit_task_session" class="ri-pencil-line edit-task-session" aria-hidden="true"></i></a> | <a data-tooltip="tooltip" data-placement="top" title="Delete"><i data-toggle="modal" data-target="#delete_task_session" class="ri-delete-bin-line" aria-hidden="true" data-id="{{$list->id}}" id="delete-session"></i></a></td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

                @endif

    </div>
</div>
