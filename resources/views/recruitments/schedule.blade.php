
    <div class="row">
        @if(count($machine_test1))
        <div class="col-md-6 ">
        <div class="ibox">
            <div class="ibox-title schedule-toggle">
                <h4 class="m-b-n">Machine Test 1<button class="btn btn-success btn-circle m-l" type="button">{{count($machine_test1)}}</button></h4>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="epms-icon--1x ri-arrow-up-s-line"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="">
            @foreach($machine_test1 as $test)
            <strong class="new-schedule" data-id="{{$test->candidate->id}}">{{$test->candidate->name}}</strong> <span class="label label-danger" style="float:right;">{{$test->machine_test_one_time_format}}</span>
            @endforeach
            </div>
        </div>
        </div>
        @endif
        @if(count($machine_test2))
        <div class="col-md-6">
          <div class="ibox ">
            <div class="ibox-title schedule-toggle">
                <h4 class="m-b-n">Machine Test 2<button class="btn btn-success btn-circle m-l" type="button">{{count($machine_test2)}}</button></h4>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="epms-icon--1x ri-arrow-up-s-line"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="">
            @foreach($machine_test2 as $test)
            <strong class="new-schedule" data-id="{{$test->candidate->id}}">{{$test->candidate->name}}</strong> <span class="label label-danger" style="float:right;">{{$test->machine_test_two_time_format}}</span>
            @endforeach
            </div>
          </div>
        </div>
        @endif
        @if(count($technical_interview))
        <div class="col-md-6">
          <div class="ibox">
            <div class="ibox-title schedule-toggle">
                <h4 class="m-b-n">Technical Interview <button class="btn btn-success btn-circle m-l" type="button">{{count($technical_interview)}}</button></h4>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="epms-icon--1x ri-arrow-up-s-line"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="">
                @foreach($technical_interview as $test)
            <strong class="new-schedule" data-id="{{$test->candidate->id}}">{{$test->candidate->name}}</strong> <span class="label label-danger" style="float:right;">{{$test->technical_interview_time_format}}</span>
            @endforeach
            </div>
          </div>
        </div>
        @endif
        @if(count($hr_interview))
        <div class="col-md-6">
          <div class="ibox">
            <div class="ibox-title schedule-toggle">
                <h4 class="m-b-n">HR Interview <button class="btn btn-success btn-circle m-l" type="button">{{count($hr_interview)}}</button></h4>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="epms-icon--1x ri-arrow-up-s-line"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="">
            @foreach($hr_interview as $test)
            <strong class="new-schedule" data-id="{{$test->candidate->id}}">{{$test->candidate->name}}</strong> <span class="label label-danger" style="float:right;">{{$test->hr_interview_time_format}}</span>
            @endforeach
            </div>
          </div>
        </div>
        @endif
    @if(count($machine_test1) == 0 & count($machine_test2) == 0 & count($technical_interview) == 0 & count($hr_interview) == 0)
    <div class="col-md-12 ibox-content">
        <h3 class="text-center">No schedules</h3>
    </div>
    @endif
    </div>
