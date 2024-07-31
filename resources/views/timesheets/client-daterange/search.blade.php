<div class="ibox-title">
    <div class="row">
        <div class="col-md-7">
        </div>
        <div class="col-md-2">
            <input class="form-control active" type="text" id="daterange" name="daterange"  value="{{$date}}">
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select class="chosen-select" name="clientId" id="clientId">
                    <option value="">Select Client / Account</option>
                    @foreach ($clients as $client)
                    <option value="{{$client->id}}" {{ request()->clientId == $client->id ? 'selected': '' }}>{{$client->company_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-primary btn search" >Search</button>
            </div>
        </div>
    </div>
</div>