<div class="col-sm-2 col-md-2">
    <div class="form-group no-margins">
        <select class="chosen-select leave-filter" id="user_id" name="user_id">
            <option value="">Select Employee</option> 
            <?php $users = \App\Models\User::notClients()->get();?>             
            @if($users)
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                @endforeach 
            @endif         
        </select>    
    </div> 
</div>