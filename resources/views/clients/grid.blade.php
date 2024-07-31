<div class="wrapper wrapper-content grid">

<div class="row">

    <div class="col-sm-8">

        <div class="panel">

            <div class="panel-body">
            <form action="{{route('getClientGrid')}}" method="post" autocomplete="off" id="client-filter-form">
	        @csrf
                <div class="ibox-content panel mb10">
                    <div class="row d-flex" style="padding-top: 10px;padding-bottom: 10px">
                        <div class="input-group col-sm-5 col-md-5" style="margin-bottom:10px;">
                            <input type="text" placeholder="Search client" class="input form-control typeahead_name search-client" value="{{ $clientCompany ?? request()->clientCompany }}">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn btn-primary search-button"> <i class="fa fa-search"></i> Search</button>
                            </span>
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <select class="chosen-select" id="country" name="country">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country=>$client)
                                <option value="{{$country}}" {{request()->country == $country ? 'selected' : ''}}>{{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <select class="chosen-select" id="account-manager-id" name="account_manager_id">
                                <option value="">Select Account Manager</option>
                                @foreach ($employees as $employee)
                                <option value="{{$employee->id}}" {{request()->accountManagerId == $employee->id ? 'selected' : ''}}>{{$employee->full_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 col-md-4 weekrange" style="margin-bottom: 10px;">
                            <div class="form-group">
                                <input class="form-control weekpicker dateInput" type="text" name="daterange" id="daterange" placeholder="Created Date Range" style="height: 30px;border-radius: 5px" value="{{request()->daterange ?? ''}}"/>
                            </div>
                        </div>
                        <div class="col-sm-8 col-md-8 form-group text-right">
                            <button class="btn  btn-w-m btn-info filter-reset">Clear Filters</button>
                        </div>
                    </div>
                </div>
            </form>
                <div class="clients-list">

                           @include('clients.list')

                </div>

            </div>

        </div>

    </div>

    <div class="col-sm-4">

        <div class="panel viewDetails">
            @include('clients.view')        

        </div>

    </div>

</div>


                 

