<div class="panel-body">

    <div id="contact-1" class="tab-pane active">
        @if($oneUser)
        <div class="row m-b-lg">

            <div class="col-lg-4 text-center">
                <div class="m-b-sm">

                    <img alt="image" class="img-circle" src="@if($oneUser->image_path){{ asset('storage/'.$oneUser->image_path) }}@else{{ asset('img/user.jpg') }}@endif" style="width: 62px">
                </div>
            </div>
            <div class="col-lg-8">
                <h2> <strong>{{$oneUser->full_name}}</strong> </h2>
            </div>
        </div>
        <div class="client">

            <ul class="list-group clear-list">
                
                @if(!$oneUser->hasRole('client'))
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->employee_id}} </span>

                        Employee ID

                    </li>
                @endif
                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneUser->nick_name}} </span>

                    Nick Name

                </li>

                <li class="list-group-item">

                    <span class="pull-right">{{ $oneUser->email }} </span>

                   Email

                </li>
                <li class="list-group-item">

                    <span class="pull-right"> {{ $oneUser->role->display_name }} </span>

                    Type

                </li>
                @if(!$oneUser->hasRole('client'))
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->joining_date_format }} </span>

                        Joining Date

                    </li>
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->designation->name ?? '' }} </span>

                        Designation

                    </li>
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->department->name ?? '' }} </span>

                        Department

                    </li>
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->monthly_salary}} </span>

                        Monthly Salary

                    </li>
                </ul>
                <h4>Bank Details</h4>
                <ul class="list-group clear-list">
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->bank_name}} </span>

                        Bank Name

                    </li>
                  
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->account_no }} </span>

                        Account Number

                    </li>
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->branch }} </span>

                        Branch

                    </li>
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->ifsc }} </span>

                        IFSC

                    </li>
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->pan }} </span>

                        Pan Number

                    </li>
                    <li class="list-group-item">

                        <span class="pull-right"> {{ $oneUser->uan }} </span>

                        UAN Number

                    </li>
                @endif
            </ul>
            @if(!$oneUser->hasRole('client'))
                <h4>Personal Details</h4>
                <ul class="list-group clear-list">
                    <li class="list-group-item">
                        <span class="pull-right"> {{ $oneUser->phone }}</span>
                        Phone
                    </li>  
                    <li class="list-group-item h-150">                      
                        <span class="pull-right"> @php echo  wordwrap($oneUser->address,30,"<br/>") @endphp</span>
                        Address
                    </li>
                    
                                    
                </ul>
            @endif
            @if($oneUser->users_project->count() > 0)
                <strong>Projects Assigned</strong>
                <ol> 
                @foreach($oneUser->users_project as $list)
                    <li>{{ $list->project->project_name ?? '' }}</li>
                @endforeach
                </ol>
            @endif

        </div>
        @else
        <div class="alert alert-danger">No Users Added to the system.</div>
        @endif
    </div>
    
</div>