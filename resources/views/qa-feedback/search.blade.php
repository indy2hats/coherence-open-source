<div class="ibox-title">    
    <div class="row">
        <div class="col-md-5">
        </div>
        <form id="user-search-form" onsubmit="return false;">
            @csrf
            <div class="col-md-3">
                <input class="form-control active" type="text" id="daterange" name="daterange" value="">
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <select class="chosen-select" name="by_user" id="by_user">
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}" {{ request()->userId == $user->id ? 'selected': '' }}>{{$user->full_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
          
            <div class="col-md-1">
                <div class="form-group">
                    <button class="btn btn-primary btn search" >Search</button>
                </div>
            </div>
        </form>
    </div>
</div>