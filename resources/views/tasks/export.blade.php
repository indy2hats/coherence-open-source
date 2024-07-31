<table>
    <thead>
    <tr>
        <th>Employee</th>
        <th>Date</th>
        <th>Hours</th>
        <th>Type</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tbody>
        @php 
        $total = $taskSessions->sum('total');
        @endphp
        @foreach($taskSessions as $list)
        <tr>
            <td>{{$list->user->full_name ?? 'Deleted User' }}</td>
            <td>{{\Carbon\Carbon::parse($list->created_at)->format('M d, Y')}}</td>
            <td data-total="{{$list->total}}" data-start="{{$list->start_time}}"
                @if(Carbon\Carbon::parse($list->start_time)->format('d/m/Y') == date('d/m/Y') &&
                $list->current_status == "started") class="time" @endif><span
                    class="timer">{{floor($list->total / 60 ).'h '.($list->total % 60).'m'}}</span></td>
            <td>{{ucwords(str_replace('-', ' ', $list->session_type))}}</td>
            <td>{!! nl2br(trim(htmlentities($list->comments))) !!}</td>
        </tr>
        @endforeach

        @if($taskSessions)
            <tr>
                <td></td>
                <td><strong>Total</strong></td>
                <td>
                    <strong id="total_time">{{floor($total / 60 ).'h '.($total % 60).'m'}}</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>