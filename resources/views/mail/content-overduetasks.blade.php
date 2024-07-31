@extends('mail.mail-layout')
@section('content')

<table>
    <tr>
        <td style="padding: 0px;">
            <div style="width:auto;max-width:100%;display:flex;padding:20px;align-items: center;justify-content: space-between;    border: 1px solid #84868b;">
                <div style="flex: 1 1 auto; width: 50%; float: left;"><h3 style="margin-bottom:0;margin-top: 0;color:#565555; font-size: 16px;">Overdue Tasks</h3></div>
                <div style="flex: 1 1 auto; width: 50%; float: left;"><a style="color: #167297;float: right;" href="{{ url('/overdue-tasks')}}">view all 
                <img src="{{asset('/images/arrow-right.png')}}" alt=""
                            style="width:12px;height:12px;margin-left:5px;display: inline-block;margin-top: 8px;float: right;">
                    </a> </div>
            </div>
        </td>
    </tr>
    @foreach($data as $task)
    <tr style="border-spacing: 0;">
        <td style="border-spacing: 0; padding:0px;">
            <div style="width: auto;max-width:100%; display: flex; align-items:flex-start; justify-content:flex-start;padding:20px;    border: 1px solid #84868b; border-top: 0px;">
                <div style="width:30px;height:auto; color:#84868b;padding-top:4px;min-width: 25px;">
                <img src="{{asset('/images/sticky-note.png')}}" alt=""
                            style="width:16px;height:16px;">
                </div>
                <div>
                    <p style="margin-bottom: 5px; font-size:14px; margin-top: 3px;">{{ $task['project']['project_name'] }} / </p>
                    <h2 style="margin-bottom: 10px;"><a style="font-size:17px;font: weight 500px; color:#707070;text-decoration:none;" href="{{ url('tasks/'.$task['id'])}}">{{ $task['title'] }}</a></h2>
                    
                    <div class="responsive-table" style="width: auto;max-width:100%;display: flex;align-items:flex-start;justify-content:flex-start;">
                        <div style="padding: 5px 5px 5px 0; min-width:160px;">
                        <h3 style="margin-bottom: 0;margin-top:3px"><a style="font-size:14px;font-weight:normal;color:#707070;text-decoration:none;" href="{{ url('tasks/'.$task['id'])}}">{{ $task['code'] }}</a></h3>
                        </div>
                        <div style="padding: 10px 10px 10px 0;">
                            <div style="color: rgb(73, 73, 73);">
                            @foreach($task['users'] as $user)
                                        {{$user['full_name']}}
                                        @if(!$loop->last)
                                                {{', '}}
                                        @endif
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="responsive-table" style="width: auto;max-width:100%;display: flex;align-items:flex-start;justify-content:flex-start;">
                        <div style="padding: 5px 5px 5px 0; min-width:160px; width: 160px; max-width: 160px;">
                        @if($task['status'] == 'Backlog')
                           @php $styleDiv = "color: rgb(225, 14, 14);"; $styleSpan = "background: rgb(225, 14, 14);"; @endphp
                        @elseif($task['status'] == 'In Progress') 
                           @php $styleDiv = "color: rgb(13, 143, 2);";  $styleSpan = "background: rgb(13, 143, 2);"; @endphp
                        @elseif($task['status'] == 'Development Completed') 
                           @php $styleDiv = "color: rgb(209, 185, 28);";  $styleSpan = "background: rgb(209, 185, 28);"; @endphp
                        @elseif($task['status'] == 'Under QA')
                           @php $styleDiv = "color: rgb(2, 80, 143);";  $styleSpan = "background: rgb(2, 80, 143);"; @endphp
                        @else    
                           @php $styleDiv = "color: rgb(5, 5, 5);";  $styleSpan = "background: rgb(5, 5, 5);"; @endphp
                        @endif
                            <div style="display:inline-block;align-items:center; {{ $styleDiv }}">
                            <span aria-hidden="true" 
                            style="width: 12px;height: 12px;border-radius: 50%;margin-right: 5px;min-width: 12px;display:inline-block;align-items: center;    margin-top: 8px;{{ $styleSpan }}">
                            </span>
                            <span>{{ $task['status'] }}</span>
                            </div>
                        </div>
                        <div style="padding: 5px 5px 5px 0;">
                            <div style="color: rgb(73, 73, 73);">
                            {{ $task['end_date'] }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </td>
    </tr>
    @endforeach

</table>
@endsection