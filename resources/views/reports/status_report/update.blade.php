@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-8">
            <strong>
                <h2 class="page-title">Employee Daily Status Report - {{auth()->user()->dsr_late_date_show}}</h3>
            </strong>
    </div>

    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content animated fadeInUp">
                
                <div id="performance_content">
                    <div>
                        <div class="row">
                            <form action="{{route('dailyStatusReport.update')}}" id="update_eod_form" method="POST" autocomplete="off">
                                @csrf
                                <input type="hidden" name="added_on" value="{{auth()->user()->dsr_late_date}}">
                                <div class="row"> 
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>What did you do on {{auth()->user()->dsr_late_date_show}}? <span class="required-label">*</span></label>
                                            <textarea rows="4" class="form-control" placeholder="Enter your reply here" name="todays_task" id="todays_task">{{ $content }}</textarea>
                                            @if($errors->has('todays_task'))
                                                <div class="text-danger text-left field-error" id="label_todays_task">{{ $errors->first('todays_task') }}</div>
                                            @endif
                                            
                                        </div>

                                        <div class="form-group">
                                            <label>Are there any obstacles hindering your progress? <span class="required-label">*</span></label>
                                            <textarea rows="4" class="form-control" placeholder="Enter your reply here" name="impediments"></textarea>
                                            @if($errors->has('impediments'))
                                                <div class="text-danger text-left field-error" id="label_impediments">{{ $errors->first('impediments') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label>Do you have any plans for today? </label>
                                            <textarea rows="4" class="form-control" placeholder="Enter your reply here" name="tommorows_task"></textarea>
                                            <div class="text-danger text-left field-error" id="label_tommorows_task"></div>
                                        </div>
                                    </div>
                                </div>
                                               
                                <div class="submit-section mt20">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

</div>

@endsection
@section('after_scripts')

@endsection
