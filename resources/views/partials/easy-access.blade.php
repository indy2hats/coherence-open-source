
        	@foreach(unserialize(Auth::user()->easy_access) as $item)
            <li>
                <a href="{{$item['link']}}" target="_blank">{{$item['name']}}</a>
            </li>
            @endforeach


            @foreach(App\Models\TaskSession::getPausedTasksofUser() as $pausedTask)
            <li>
                <a href="/tasks/{{$pausedTask->task->id}}" target="_blank">{{Str::limit($pausedTask->task->title,60)}}</a>
            </li>
            @endforeach