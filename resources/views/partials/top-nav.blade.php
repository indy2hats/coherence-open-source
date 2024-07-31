<script src="{{ asset('js/all.js') }}"></script>
<script src="{{ asset('js/resources/partials/task-script.min.js') }}"></script>  

<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="header-wrap row align-items-center position-relative">
        <div class="header-left col-sm-2">
            <a href="/dashboard" class="brand">
                <img src="{{asset('images/2hats-logo.png')}}" alt="logo">
            </a>
        </div>
        <div class="header-right col-sm-10">
            <div class="search-wrap">
                <div class="g-search-item pull-right">
                    <div class="inner-addon right-addon">
                        <i class="ri-search-line epms-icon epms-icon--1x"></i>
                        <i class="fa fa-spinner fa-lg" style="display: none"></i>
                        <input autocomplete="off" placeholder="Search projects, tasks, jira ids, comments.." type="text"  name="q" id="gSearch" class="form-control global-search-box">
                    </div>
                </div>
                <div id="globalSearchResultWrapper" class="global-search"></div>
            </div>
            <ul class="navbar-controls">
                @php
                $eodDate = '';
                $currentTime = \Carbon\Carbon::now('Asia/Kolkata')->toTimeString();
                    if(strtotime($currentTime)<=strtotime('18:00:00') && strtotime($currentTime)>=strtotime('06:00:00')) {
                } else {
                    $eodDate = \Carbon\Carbon::now()->format('Y-m-d');
                }
                @endphp
                @if(!empty(Helper::showDailyStatusReportPage()))
                    @if($eodDate != '' && !Auth::user()->checkIfDsrEntered($eodDate))
                    <li class="eod_report" id="eod_report_li">
                        <a href="#" data-toggle="modal" data-target="#add_eod_report" id="eodReportLink" style="width: auto">
                            <i class="ri-add-line"></i> Add EOD Report
                        </a>
                    </li>
                    @endif
                @endif

                <!-- <li class="dropdown epms-notify ">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
                        <span class="notification-count">8</span>
                        <i class="ri-notification-3-line epms-icon--1x"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                    <li class="info-element">
                            <a href="#" class="dropdown-item">
                                </a><div class=""><a href="#" class="dropdown-item">
                                    <span class="label label-danger">In Progress</span>
                                    <h4 class="">Task Title</h4>
                                    </a><div class="d-inline-flex time w-100"><a href="#" class="dropdown-item"><div class="text-muted"><i class="ri-time-line"></i>8 hrs</div></a><a href="#" class=""><i class="ri-arrow-right-line"></i></a></div>
                                </div>
                            
                        </li>
                        
                        <li class="info-element">
                            <a href="#" class="dropdown-item">
                                </a><div class=""><a href="#" class="dropdown-item">
                                    <span class="label label-danger">In Progress</span>
                                    <h4 class="">Task Title</h4>
                                    </a><div class="d-inline-flex time w-100"><a href="#" class="dropdown-item"><div class="text-muted"><i class="ri-time-line"></i>8 hrs</div></a><a href="#" class=""><i class="ri-arrow-right-line"></i></a></div>
                                </div>
                            
                        </li>
                        <li class="info-element">
                            <a href="#" class="dropdown-item">
                                </a><div class=""><a href="#" class="dropdown-item">
                                    <span class="label label-danger">In Progress</span>
                                    <h4 class="">Task Title</h4>
                                    </a><div class="d-inline-flex time w-100"><a href="#" class="dropdown-item"><div class="text-muted"><i class="ri-time-line"></i>8 hrs</div></a><a href="#" class=""><i class="ri-arrow-right-line"></i></a></div>
                                </div>
                            
                        </li>
                        <li class="info-element">
                            <a href="#" class="dropdown-item">
                                </a><div class=""><a href="#" class="dropdown-item">
                                    <span class="label label-danger">In Progress</span>
                                    <h4 class="">Task Title</h4>
                                    </a><div class="d-inline-flex time w-100"><a href="#" class="dropdown-item"><div class="text-muted"><i class="ri-time-line"></i>8 hrs</div></a><a href="#" class=""><i class="ri-arrow-right-line"></i></a></div>
                                </div>
                            
                        </li>
                        <li class="info-element">
                            <a href="#" class="dropdown-item">
                                </a><div class=""><a href="#" class="dropdown-item">
                                    <span class="label label-danger">In Progress</span>
                                    <h4 class="">Task Title</h4>
                                    </a><div class="d-inline-flex time w-100"><a href="#" class="dropdown-item"><div class="text-muted"><i class="ri-time-line"></i>8 hrs</div></a><a href="#" class=""><i class="ri-arrow-right-line"></i></a></div>
                                </div>
                            
                        </li>
                   
                   
                        
                    </ul>
                </li> -->

                <li>
                    <a href="{{ route('showProfile') }}">
                    <i class="ri-user-line epms-icon epms-icon--1x"></i>
                    </a>
                </li>

                <li>
                    <a href="{{ route('changePassword') }}">
                    <i class="ri-settings-5-line epms-icon epms-icon--1x"></i>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('logout') }}">
                    <i class="ri-logout-circle-r-line epms-icon epms-icon--1x"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="ibox-content breadcrumbs-outer-div">
    {{ Breadcrumbs::render() }}
    <button id="pause-task" class=" {{Session::has('taskRunning') ? '': 'hidden'  }}"><i class="ri-pause-line"></i></button>

</div>

<!-- Create Task Modal -->
<div id="add_eod_report" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add EOD Report</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('dailyStatusReport.store')}}" id="add_eod_form" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="added_on" value="{{date('Y-m-d')}}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>What did you do today? <span class="required-label">*</span></label>
                                <textarea rows="4" class="form-control" placeholder="Enter your reply here"
                                    name="todays_task" id="todays_task"></textarea>
                                <div class="text-danger text-left field-error" id="label_todays_task"></div>
                            </div>

                            <div class="form-group">
                                <label>Are there any obstacles hindering your progress? <span
                                        class="required-label">*</span></label>
                                <textarea rows="4" class="form-control" placeholder="Enter your reply here"
                                    name="impediments"></textarea>
                                <div class="text-danger text-left field-error" id="label_impediments"></div>
                            </div>

                            <div class="form-group">
                                <label>Do you have any plans for tomorrow? </label>
                                <textarea rows="4" class="form-control" placeholder="Enter your reply here"
                                    name="tommorows_task"></textarea>
                                <div class="text-danger text-left field-error" id="label_tommorows_task"></div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section mt20">
                        <button class="btn btn-primary submit-btn create-eod-report">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>