<div class="ibox">

    <div class="ibox-content ">

        <table class="footable table table-stripped" >
        
            <thead>

                <tr>



                    <th data-toggle="true">Applied On</th>

                    <th data-toggle="true">Created on</th>

                    <th data-hide="phone">Category</th>

                    <th data-hide="phone">Name</th>

                    <th data-hide="phone">Email & Contact No.</th>

                    <th data-hide="all">Machine Test 1</th>

                    <th data-hide="all">Machine Test 2</th>

                    <th data-hide="all">Technical Interview</th>

                    <th data-hide="all">HR Interview</th>

                    <th data-hide="all">Description</th>

                    <th data-hide="phone">Status</th>

                    <th data-hide="phone">Source</th>

                    <th data-hide="phone">Career Start Date</th>

                    <th data-hide="phone">Resume</th>

                    <th class="text-center" data-sort-ignore="true">Action</th>



                </tr>

            </thead>

            <tbody>
                @if(count($candidates) > 0)
                @foreach($candidates as $candidate)

                <tr>

                    <td>

                        {{ $candidate->applied_date }}

                    </td>

                     <td>
                        {{ $candidate->created_at }}
                    </td>

                    <td>

                        {{ $candidate->category }}

                    </td>

                    <td>

                        {{ $candidate->name }}

                    </td>

                    <td>

                        <i class="ri-mail-fill" style="display: block;"> {{ $candidate->email }}</i><i class="fa fa-phone" style="display: block;margin-top: 5px"> {{ $candidate->phone }}</i>

                    </td>

                    <td>{{$candidate->schedule->machine_test1_status ?? ''}}{{ ($candidate->schedule->machine_test1_status?? '') == 'Scheduled'?' - '.$candidate->schedule->machine_test_one_format.' '.$candidate->schedule->machine_test_one_time_format:''}}</td>

                     <td>{{$candidate->schedule->machine_test2_status ?? ''}}{{ ($candidate->schedule->machine_test2_status ?? '') == 'Scheduled'?' - '.$candidate->schedule->machine_test_two_format.' '.$candidate->schedule->machine_test_two_time_format:''}}</td>

                    <td>{{$candidate->schedule->technical_interview_status ?? ''}}{{ ($candidate->schedule->technical_interview_status ?? '') == 'Scheduled'?' - '.$candidate->schedule->technical_interview_format.' '.$candidate->schedule->technical_interview_time_format:''}}</td>

                    <td>{{$candidate->schedule->hr_interview_status ?? ''}}{{ ($candidate->schedule->hr_interview_status ?? '') == 'Scheduled'?' - '.$candidate->schedule->hr_interview_format.' '.$candidate->schedule->hr_interview_time_format:''}}</td>

                    <td>

                        {!! $candidate->description !!}

                    </td>

                    <td>

                        {{ $candidate->status }}

                    </td>
                    <td>

                        {{ $candidate->source }}

                    </td>
                    <td>

                        {{ $candidate->career_start_date_format ?: "Nil" }}

                    </td>
                    <td><a href="{{ asset('storage/'.$candidate->resume) }}" target="_blank">View <i
                                class="fa fa-id-badge"></i></a></td>

                    <td class="text-right">
                        <a class="new-schedule" data-id="{{$candidate->id}}" data-tooltip="tooltip" data-placement="top" title="Schedule"><i class="ri-calendar-2-line"></i></a>
                        | <a class="edit-candidate" data-id="{{$candidate->id}}" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="ri-pencil-line"></i></a>
                        | <a class="delete-candidate" data-toggle="modal" data-target="#delete_candidate" data-id="{{$candidate->id}}" data-tooltip="tooltip" data-placement="top" title="Delete"><i
                                class="ri-delete-bin-line"></i></a>
                    </td>

                </tr>


                @endforeach
                @endif

            </tbody>
            
            <tfoot style="display: none">
                <tr>
                    <td colspan="5">
                        <ul class="pagination float-right"></ul>
                    </td>
                </tr>
            </tfoot>
     
        </table>
        <div class="pagination-div">
            {{ $candidates->appends(['category' => request()->category,
                                     'name' => request()->name,
                                     'status' => request()->status,
                                     'daterange' => request()->daterange])->links() }}
        </div>

    </div>

</div>
