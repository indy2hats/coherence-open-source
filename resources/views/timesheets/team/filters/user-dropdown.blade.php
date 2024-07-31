<div class="col-sm-2 col-md-2">
    <div class="form-group">
        <select class="chosen-select user-filter" id="user_id" name="filter[user_id]">
            <option value="">Select Employee</option>
            @php $users = \App\Models\User::notClients()->get();@endphp
            @if($users)
            @foreach($reportees as $reportee)
            @php $selected = ((isset($filter['user_id']) && $filter['user_id'] != '') &&
                              ($filter['user_id'] == $reportee->id)) ? 'selected' : '';
            @endphp
            <option value="{{$reportee->id}}" {{ $selected }}>{{$reportee->first_name.' '.$reportee->last_name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>