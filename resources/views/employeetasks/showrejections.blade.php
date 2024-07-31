@if($taskRejections->count()>0)
<div class="row">
    <div class="col-lg-12">
        <div class="ibox-title">
            <div class="row">
                <div class="col-md-8">
                    <strong>
                        <h3 class="page-title" style="font-size: 20px">Rejection Details</h3>
                    </strong>
                </div>
            </div>
        </div>
        <div class="ibox-content">

            <div class="table table-hover listTable">

                <table class="table table-hover rejectionTable" style="width:100%">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>Severity</th>

                            <th>Reason</th>

                        </tr>

                    </thead>

                    <tbody>
                        @foreach($taskRejections as $list)
                        @if($list->reason != '')
                        <tr>
                            <td>{{date_format (new DateTime($list->created_at), 'd-M-Y, l')}}</td>
                            <td>{{$list->severity}}</td>
                            <td>{!! $list->reason !!}</td>
                        </tr>
                        @else
                        <tr>
                            <td>{{date_format (new DateTime($list->created_at), 'd-M-Y, l')}}</td>
                            <td colspan="2">Response Awaiting</td>
                        </tr>
                        @endif
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>
@endif