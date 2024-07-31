<table class="table table-striped listTable2">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Completed Tasks</th>
            <th>Tasks Rejected</th>
            <th>Critical Severity Rejections</th>
            <th>High Severity Rejections</th>
        </tr>
    </thead>
    <tbody> 
        @foreach( $reports as $report )
       
            <tr>
                <td>{{$report->full_name}}</td>
                <td>{{$report->users_task->count()}}</td>
                <td>{{$report->total_rejections}}</td>
                <td>{{$report->critical_rejections_count}}</td>
                <td>{{$report->high_rejections_count}}</td>
            </tr>
        @endforeach
    </tbody>
</table>