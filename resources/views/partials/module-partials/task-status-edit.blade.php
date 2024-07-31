
@foreach($types as $type)
    <option {{$task->status == $type->title ? 'selected' : ''}}>
    	{{$type->title}}</option>
@endforeach