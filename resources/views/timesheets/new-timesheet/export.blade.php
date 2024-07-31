<table>
    <thead>
    <tr>
        <th>Task</th>
        <th>Date</th>
        <th>Actual Hours Spent</th>
        <th>Billed Hours</th>
        <th>Details</th>
        <th>Description</th>
        <th>Estimate Time</th>
        <th>Time Taken in Selected Range</th>
        <th>Total Time Taken so far</th>
        <th>Billed Hours in Selected Range</th>
        <th>Billed Hours so far</th>
        <th>Task Link</th>
    </tr>
    </thead>
    <tbody>
        @php
            $previousProject = null;
            $previousTask = null;
            $hoursByTask = [];
            $hoursBilled = [];

            foreach ($taskSessions as $taskSession) {
                $hoursByTask[$taskSession['task']] = $taskSession['hours'];
                if (isset($hoursByTask[$taskSession['task']])) {
                    $hoursByTask[$taskSession['task']] += $taskSession['hours'];
                }

                $hoursBilled[$taskSession['task']]  = $taskSession['billed_hours'];
                if (isset($hoursBilled[$taskSession['task']])) {
                    $hoursBilled[$taskSession['task']] += $taskSession['billed_hours'];
                }
            }
        @endphp

        @foreach($taskSessions as $taskSession)
            @if($previousProject != $taskSession['project_name'] )
                <tr>
                    <td colspan="12" style="text-align:center">
                    @php
                        $previousProject = $taskSession['project_name'];
                    @endphp
                    Project : {{ $taskSession['project_name'] }}
                    </td>
                </tr>
            @endif
            @if ($previousTask != $taskSession['task'])
                @php $taskId = $taskSession['taskId']; @endphp
                <tr>
                    <td colspan="5">
                    @php
                        $previousTask = $taskSession['task'] ;
                    @endphp
                        {{ $taskSession['task'] }}
                    </td>
                    <td>
                        {{ strip_tags($taskSession['taskDescription']) }}
                    </td>
                    <td>
                        {{ $taskSession['taskEstimatedTime'] }}
                    </td>
                    <td>
                        @php
                            echo floor($hoursByTask[$taskSession['task']]/60).'h '.($hoursByTask[$taskSession['task']]%60).'m';
                        @endphp
                    </td>
                    <td>
                        @if(isset($totalTaskTimeTaken[$taskId]['totalTimeTaken']))
                            {{ floor($totalTaskTimeTaken[$taskId]['totalTimeTaken']/60).'h '.($totalTaskTimeTaken[$taskId]['totalTimeTaken']%60).'m' }}
                        @else
                            0h 0m
                        @endif
                    </td>
                    <td>
                        @php
                            echo floor($hoursBilled[$taskSession['task']]/60).'h '.($hoursBilled[$taskSession['task']]%60).'m';
                        @endphp
                    </td>
                    <td>
                        @php
                            echo floor($totalTaskTimeTaken[$taskId]['billedTotal']/60).'h '.($totalTaskTimeTaken[$taskId]['billedTotal']%60).'m';
                        @endphp
                    </td>
                    <td>
                        {{ $taskSession['task_url'] }}
                    </td>
                </tr>
            @endif
            <tr>
                <td></td>
                <td>{{ date('M d, Y',strtotime($taskSession['date'])) }}</td>
                <td>{{ floor($taskSession['hours']/60).'h '.($taskSession['hours']%60).'m' }}</td>
                <td>{{ floor($taskSession['billed_hours']/60).'h '.($taskSession['billed_hours']%60).'m' }}</td>
                <td>{{ $taskSession['comments'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
