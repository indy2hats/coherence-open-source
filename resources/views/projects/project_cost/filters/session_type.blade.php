<div class="col-sm-2 col-md-2">
    <div class="form-group no-margins">
        <select class="chosen-select financial-filter" id="session_type" name="session_type">
            <option value="">Session Type</option>
            @foreach(\App\Models\SessionType::all() as $sessionType)
                <option value="{{ $sessionType->slug }}">{{ $sessionType->title }}</option>
            @endforeach
        </select>
    </div>
</div>