<div class="col-sm-2 col-md-2">
    <div class="form-group no-margins">
        <select class="chosen-select financial-filter" id="user" name="user">
            <option value="">Select User</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>
