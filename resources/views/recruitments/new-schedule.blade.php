
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Schedule Test</h4>
            </div>
            <div class="modal-body">
               <form action="{{route('updateSchedule')}}" id="update_schedule" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="schedule_id" id="schedule_id" value="{{$schedule->id}}">
                    <div class="row">                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Machine Test 1 Status <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="machine_test_status" name="machine_test1_status">
                                    <option value="Not Done" {{ $schedule->machine_test1_status == 'Not Done' ? 'selected':''}}>Not Done</option>
                                    <option value="Scheduled" {{ $schedule->machine_test1_status == 'Scheduled' ? 'selected':''}}>Scheduled</option>
                                    <option value="Failed" {{ $schedule->machine_test1_status == 'Failed' ? 'selected':''}}>Failed</option>
                                    <option value="Cleared" {{ $schedule->machine_test1_status == 'Cleared' ? 'selected':''}}>Cleared</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_category"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Machine Test Date<span class="required-label">*</span></label>
                                <input class="form-control datetimepicker-new" type="text" name="machine_test1" value="{{$schedule->machine_test_one_date}}">
                                <div class="text-danger text-left field-error" id="label_machine_test1"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Time<span class="required-label">*</span></label>
                                 <div class="input-group clockpicker" data-autoclose="true">

                                <input type="text" class="form-control" value="{{$schedule->machine_test_one_time}}" name="machine_test1_time">

                                <span class="input-group-addon">

                                    <span class="ri-time-line"></span>

                                </span>

                            </div>
                            <div class="text-danger text-left field-error" id="label_machine_test1_time"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Machine Test 2 Status <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="machine_test2_status" name="machine_test2_status">
                                    <option value="Not Done" {{ $schedule->machine_test2_status == 'Not Done' ? 'selected':''}}>Not Done</option>
                                    <option value="Scheduled" {{ $schedule->machine_test2_status == 'Scheduled' ? 'selected':''}}>Scheduled</option>
                                    <option value="Failed" {{ $schedule->machine_test2_status == 'Failed' ? 'selected':''}}>Failed</option>
                                    <option value="Cleared" {{ $schedule->machine_test2_status == 'Cleared' ? 'selected':''}}>Cleared</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_category"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Machine Test Date<span class="required-label">*</span></label>
                                <input class="form-control datetimepicker-new" type="text" name="machine_test2" value="{{$schedule->machine_test_two_date}}">
                                <div class="text-danger text-left field-error" id="label_machine_test2"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Time<span class="required-label">*</span></label>
                                 <div class="input-group clockpicker" data-autoclose="true">

                                <input type="text" class="form-control" value="{{$schedule->machine_test_two_time}}" name="machine_test2_time">

                                <span class="input-group-addon">

                                    <span class="ri-time-line"></span>

                                </span>

                            </div>
                            <div class="text-danger text-left field-error" id="label_machine_test2_time"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Techinical Interview Status <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="technical_interview_status" name="technical_interview_status">
                                    <option value="Not Done" {{ $schedule->technical_interview_status == 'Not Done' ? 'selected':''}}>Not Done</option>
                                    <option value="Scheduled" {{ $schedule->technical_interview_status == 'Scheduled' ? 'selected':''}}>Scheduled</option>
                                    <option value="Failed" {{ $schedule->technical_interview_status == 'Failed' ? 'selected':''}}>Failed</option>
                                    <option value="Cleared" {{ $schedule->technical_interview_status == 'Cleared' ? 'selected':''}}>Cleared</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_category"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Technical Interview Date<span class="required-label">*</span></label>
                                <input class="form-control datetimepicker-new" type="text" name="technical_interview" value="{{$schedule->technical_interview_date}}">
                                <div class="text-danger text-left field-error" id="label_technical_interview"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Time<span class="required-label">*</span></label>
                                 <div class="input-group clockpicker" data-autoclose="true">

                                <input type="text" class="form-control" value="{{$schedule->technical_interview_time}}" name="technical_interview_time">

                                <span class="input-group-addon">

                                    <span class="ri-time-line"></span>

                                </span>

                            </div>
                            <div class="text-danger text-left field-error" id="label_technical_interview_time"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>HR Interview Status <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="hr_interview_status" name="hr_interview_status">
                                    <option value="Not Done" {{ $schedule->hr_interview_status == 'Not Done' ? 'selected':''}}>Not Done</option>
                                    <option value="Scheduled" {{ $schedule->hr_interview_status == 'Scheduled' ? 'selected':''}}>Scheduled</option>
                                    <option value="Failed" {{ $schedule->hr_interview_status == 'Failed' ? 'selected':''}}>Failed</option>
                                    <option value="Cleared" {{ $schedule->hr_interview_status == 'Cleared' ? 'selected':''}}>Cleared</option>
                                </select>
                                <div class="text-danger text-left field-error" id="label_category"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>HR Interview Date<span class="required-label">*</span></label>
                                <input class="form-control datetimepicker-new" type="text" name="hr_interview" value="{{$schedule->hr_interview_date}}">
                                <div class="text-danger text-left field-error" id="label_hr_interview"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Time<span class="required-label">*</span></label>
                                 <div class="input-group clockpicker" data-autoclose="true">

                                <input type="text" class="form-control" value="{{$schedule->hr_interview_time}}" name="hr_interview_time">

                                <span class="input-group-addon">

                                    <span class="ri-time-line"></span>

                                </span>

                            </div>
                            <div class="text-danger text-left field-error" id="label_hr_interview_time"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Overall Status <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="status" name="status">
                                    <option value="Pending" {{$schedule->candidate->status == 'Pending'?'selected':''}}>Pending</option>
                                    <option value="Processing" {{$schedule->candidate->status == 'Processing'?'selected':''}}>Processing</option>
                                    <option value="Selected" {{$schedule->candidate->status == 'Selected'?'selected':''}}>Selected</option>
                                    <option value="Rejected" {{$schedule->candidate->status == 'Rejected'?'selected':''}}>Rejected</option>
                                    <option value="On Hold" {{$schedule->candidate->status == 'On Hold'?'selected':''}}>On Hold</option>
                                    <option value="Can be Considered" {{$schedule->candidate->status == 'Can be Considered'?'selected':''}}>Can be Considered</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                 <div class="modal-btn delete-action">
                    <div class="row" style="padding-top: 10px">
                        
                        <div class="col-sm-6 text-left">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default cancel-btn">Cancel</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-primary continue-btn">Save</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
