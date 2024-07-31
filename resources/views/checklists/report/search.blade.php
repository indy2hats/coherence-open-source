
    <div class="row">
        <div class="col-md-3">
            <h2 id="formatted_date"></h2>
        </div>
        <div class="col-md-3 ">
            <button type="button" class="btn btn-outline btn-link arrow-back no-padding" data-date=""><i class="ri-arrow-left-double-line ri-2x"></i></button>


            <button type="button" data-date="{{$date}}" class="btn btn-outline btn-link todayBtn"><strong><i class="ri-calendar-2-line"></i> Today </strong></button>


            <button type="button" class="btn btn-outline btn-link arrow-front no-padding" data-date=""><i class="ri-arrow-right-double-line ri-2x"></i></button>
        </div>
        <form id="task-search-form">
       <div class="col-md-2">
        @hasrole('administrator') 
            <div class="form-group">
                <select class="chosen-select" name="user_id" id="user_id">
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                    <option value="{{$user->id}}" >{{$user->full_name}}</option>
                    @endforeach
                </select>
            </div>
            @endhasrole
        </div>
        <div class="col-md-2">    
            <div class="form-group">
                <select class="chosen-select" name="checklist_id" id="checklist_id">
                    <option value="">Select Checklist</option>
                    @foreach ($checklists as $item)
                    <option value="{{$item->id}}" >{{$item->title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <input class="form-control active datepicker" type="text" id="daterange" name="daterange" value="{{$date}}">
            </div>   
        </div>           
        </form> 
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-primary btn searchSheet">Search</button>
            </div>
        </div>  
    </div>
