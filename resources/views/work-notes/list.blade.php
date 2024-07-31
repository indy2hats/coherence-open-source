     
<div class="col-lg-12">
    @if (!empty($workNotes))
        <ul class="notes">
            @foreach ($workNotes as $note)
                <li>
                    <div>
                        <small>{{$note->updated_at_format}}</small>
                        <p class="note-content"><textarea class="note-content-text" data-id="{{$note->id}}">{{$note->content}}</textarea></p>
                        <a href="javascript:void(0)" class="delete-note" data-id="{{$note->id}}"><i class="ri-delete-bin-line"></i></a>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>