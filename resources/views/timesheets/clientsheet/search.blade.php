<h2 class="page-title">{{$days['start'] ." - ".$days['end']}}</h3>
<div class="ibox-title">
    <div class="row" id="search-head" data-start-date="{{$days['day1_date']}}">
        <div class="col-md-7">
            <button type="button" class="btn btn-outline btn-link arrow-back" data-date="{{$days['day1_date']}}"><i class="ri-arrow-left-double-line ri-2x"></i></button>


            <button type="button" class="btn btn-outline btn-link todayBtn" data-date="{{date('d/m/Y')}}"><strong><i class="ri-calendar-2-line"></i> This Week</strong></button>


            <button type="button" class="btn btn-outline btn-link arrow-front" data-date="{{$days['day1_date']}}"><i class="ri-arrow-right-double-line ri-2x"></i></button>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input class="form-control datepicker dateInput" type="text" name="date" id="date" value="{{$days['day1_date']}}" />
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select class="chosen-select" name="clientId" id="clientId">
                    <option value="">Select Client</option>
                    @foreach ($clients as $client)
                    <option value="{{$client->id}}" {{ request()->clientId == $client->id ? 'selected': '' }}>{{$client->company_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-primary btn searchSheet" >Search</button>
            </div>
        </div>
    </div>
</div>