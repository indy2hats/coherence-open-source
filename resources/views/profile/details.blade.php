<div class="wrapper wrapper-content">

    <div class="row animated fadeInRight">

        <div class="col-md-4">

            <div class="ibox float-e-margins">

                <div class="ibox-title">

                    <h5>Profile</h5>

                </div>

                <div>

                    <div class="ibox-content no-padding border-left-right">

                        <img alt="image" class="img-responsive profile-img" src="@if($details->image_path){{ asset('storage/'.$details->image_path) }}@else{{ asset('img/user.jpg') }}@endif">

                    </div>

                    <div class="ibox-content profile-content">

                        <h4><strong>{{$details->full_name}}</strong></h4>

                        <div class="client">

                            <ul class="list-group clear-list">
                                @if (!$details->hasRole('client') )
                                    <li class="list-group-item">
 
                                    Employee ID
                                     <span class="pull-right"> {{ $details->employee_id}} </span>


                                    </li>                                    
                                @endif
                                <li class="list-group-item">


                                Nick Name
                                    <span class="pull-right"> {{ $details->nick_name}} </span>

                                </li>

                                <li class="list-group-item">

                                Email
                                    <span class="pull-right">{{ $details->email }} </span>

                                </li>
                                @if (!$details->hasRole('client') )
                                    <li class="list-group-item">


                                    Phone
                                    <span class="pull-right"> {{ $details->phone }}</span>

                                    </li>
                                @endif
                                <li class="list-group-item">

                                    <span class="pull-right"> {{ $details->role->display_name }} </span>

                                    Type

                                </li>
                                
                                @if (!$details->hasRole('client') )
                                    <li class="list-group-item">


                                    Joining Date
                                        <span class="pull-right"> {{ $details->joining_date_format }} </span>

                                    </li>
                                    <li class="list-group-item">


                                    Designation

                                    <span class="pull-right"> {{ $details->designation->name ?? '' }} </span>

                                    </li>
                                    <li class="list-group-item">

                                    Department

                                        <span class="pull-right"> {{ $details->department->name ?? '' }} </span>
                                    </li>
                                    <li class="list-group-item">


                                    Monthly Salary
                                        <span class="pull-right"> {{ $details->monthly_salary}} </span>

                                    </li>
                                @endif
                            </ul>
                            @can('manage-projects')
                                <strong>Projects Assigned</strong>
                                <ol>
                                    @foreach($details->users_project as $list)
                                    <li> {{ $list->project->project_name ?? '' }}</li>
                                    @endforeach
                                </ol>
                            @endcan
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                @hasrole('client')
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right"><i class="fa fa-asterisk"></i></span>
                                <h5>Overview</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        <h2 class="no-margins">{{count($clientCompanies)}}</h2>
                                        <small>Companies</small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <h2 class="no-margins">{{$clientProjectsCount}}</h2>
                                        <small>Projects</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endhasrole

                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Overall</span>
                            <h5>Completed Tasks</h5>
                        </div>
                        <div class="ibox-content">
                            <h2 class="no-margins">{{ $counts['completed'] }}</h2>
                            <div class="stat-percent font-bold text-success"><small>Total Tasks: </small>{{ $counts['total'] }} </div>
                            <small> Tasks</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-warning pull-right"><i class="ri-time-line"></i></span>
                            <h5>Tasks</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <h2 class="no-margins">{{$counts['upcoming']}}</h2>
                                    <small>Upcoming Tasks</small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h2 class="no-margins">{{$counts['ongoing']}}</h2>
                                    <small>Ongoing Tasks</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @hasrole('administrator|project-manager|employee')
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-danger pull-right">Important</span>
                                <h5>Task Rejections</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        <h2 class="no-margins"><strong>{{$rejectionCount}}</strong></h2>
                                        <small>Tasks</small>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <h2 class="no-margins"><strong style="color: red">@if($rejectionIndex > 0) -{{$rejectionIndex}} @else 0 @endif</strong></h2>
                                        <small>points</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endhasrole

            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">Overall</span>
                            <h5>Total Hours Worked</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <h2 class="no-margins">{{ floor($totalHours->total/60).'h '.($totalHours->total%60).'m'}}</h2>
                                    <small>Total</small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h2 class="no-margins">{{ floor($totalHours->billed/60).'h '.($totalHours->billed%60).'m'}}</h2>
                                    <small>Billed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">This Week</span>
                            <h5>{{ floor($total['total']/60).'h '.($total['total']%60).'m'}}</h5>
                        </div>
                        <div class="ibox-content">
                            <div id="sparkline1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                    @hasrole('project-manager|employee')
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Total Leaves : {{$leave['lop']+$leave['casual']+$leave['medical']}}</h5>
                                </div>
                                <div class="ibox-content">
                                    <div id="donut" style="max-height:300px;"></div>
                                </div>
                            </div>
                        </div>
                    @endhasrole

                    @hasrole('administrator|project-manager|employee')
                    <div class="col-lg-6">
                        <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Task Rejection Splits</h5>
                                </div>
                                <div class="ibox-content">
                                    <div id="pie"></div>
                                </div>
                            </div>
                    </div>
                    @endhasrole
            </div>

            @hasrole('client')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Companies & Projects</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <table class="table table-bordered table-hover">
                                        @foreach ($clientCompanies as $key=>$company)
                                        <tr>
                                            <th>{{$company->company_name}}</th>
                                            <td>
                                                <table class="table table-striped table-bordered">
                                                    @foreach ($company->project as $project)
                                                        <tr><td>{{$project->project_name}}</td></tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                        </tr> 
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endhasrole

        </div>

    </div>

</div>