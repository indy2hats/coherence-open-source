@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox tabs-container">
            <div class="ibox-title">
                <h5>Edit Report Settings</h5>               
            </div>
            <div class="ibox-content">
                <form action="{{route('reports_settings.update')}}" id="edit_reports_settings" method="POST" autocomplete="off"  enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label"> Employee Hours Tracking Email Recipients</label>
                        <div class="col-md-10">
                            <div class="form-group form-focus select-focus focused">
                                <select class="chosen-select" id="employeeHourEmailRecipients" name="employeeHourEmailRecipients[]" multiple>
                                    @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{ (in_array($role->id, $choosenData['employeeHourEmailRecipients'])) ? 'selected': '' }}
                                        >{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label"> Below <label id='minWeeklyHours'> {{ $minWeeklyHours ?? 0 }} </label> Hours Employees Tracker Email Recipients</label>
                        <div class="col-md-10">
                            <div class="form-group form-focus select-focus focused">
                                <select class="chosen-select" id="weeklyLowHoursEmailRecipients" name="weeklyLowHoursEmailRecipients[]" multiple>
                                    @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{ (in_array($role->id, $choosenData['weeklyLowHoursEmailRecipients'])) ? 'selected': '' }}
                                        >{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Daily Time Tracker Excluded Departments </label>
                        <div class="col-md-10">
                            <div class="form-group form-focus select-focus focused">
                                <select class="chosen-select" id="dailyMailExcludedDepartments" name="dailyMailExcludedDepartments[]" multiple>
                                    @foreach ($departments as $department)
                                    <option value="{{$department->id}}" {{ (in_array($department->id, $choosenData['dailyMailExcludedDepartments'])) ? 'selected': '' }}
                                        >{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label"> Task Overdue Email Recipients</label>
                        <div class="col-md-10">
                            <div class="form-group form-focus select-focus focused">
                                <select class="chosen-select" id="taskOverdueEmailRecipients" name="taskOverdueEmailRecipients[]" multiple>
                                    @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{ (in_array($role->id, $choosenData['taskOverdueEmailRecipients'])) ? 'selected': '' }}
                                        >{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                    </div>
                    @foreach ($settings as $key => $config)
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ $config['label']}}</label>
                        <div class="col-md-10">
                            <input type="text" placeholder="Enter the {{$config['label']}}" class="form-control" value="{{ $config['value'] }}" name="{{ $key }}" id="{{ $key }}"/>
                            <div class="text-danger text-left field-error" id="label_{{ $key}}"></div>
                        </div>                        
                    </div>
                    @endforeach                   
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Weekly Report Runs On</label>
                        <div class="col-md-10">
                            <select class="chosen-select" id="weekly_report_cron_day" name="weekly_report_cron_day" >
                              @for ($i=0;$i<7;$i++)
                                   @php
                                    $value= ($i==6) ?  0 : $i+1;
                                    $selected= (reset($choosenData['weeklyReportCronDay'])==$value)? "selected":'';
                                    @endphp
                                    <option value="{{ $value}}" {{ $selected }}>{{ jddayofweek($i,1) }}</option>
                               @endfor                           
                            </select>                            
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ $otherSettings['show_daily_status_report_page']->label }}</label>
                        <div class="col-md-10">
                            <input type="checkbox" id="show_daily_status_report_page" name="show_daily_status_report_page"  {{ $otherSettings['show_daily_status_report_page']->value == '1' ? 'checked' : '' }}  style="display: inline-block;">
                        </div>                        
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group row">
                        <div class="col-md-4 col-md-offset-2">
                            <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/settings/reports/script.min.js') }}"></script>
@endsection