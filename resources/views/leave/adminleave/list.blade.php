
            <!-- <ul class="nav nav-tabs">

                <li class="active"><a data-toggle="tab" href="#tab-3">Pending Applications</a></li>

                <li class=""><a data-toggle="tab" href="#tab-4">Previous Applications</a></li>

            </ul> -->

            <div class="tab-content pt20">
                <div id="tab-3" class="tab-pane active table-responsive">
                    <table class="table table-striped listTable1">
                        <thead>
                            <tr>
                                <th>Application Date</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                {{-- <th>LOP</th> --}}
                                <th>Session</th>
                                <th>No: of Days</th>
                                <th>Reason</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $pendingList as $list )
                            <tr>
                                <td data-sort="{{$list->created_at}}">{{date_format(new DateTime($list->created_at),'d/m/Y')}}</td>
                                <td>{{$list->users->full_name}}</td>
                                <td>{{$list->type}}</td>
                                <td>{{$list->from_date_format}}</td>
                                <td>{{$list->to_date_format}}</td>
                                {{-- <td>   <input type="checkbox" class="form-check-input mark-lop" data-id="{{$list->id}}" id="lop_{{$list->id}}" name="lop_{{$list->id}}" value="LOP" @if($list->lop == 'Yes'){{'checked'}}@endif><label class="col-form-labelform-check-label" for="lop_{{$list->id}}"></td> --}}
                                <td>{{$list->session}}</td>
                                <td>{{$list->total_leave_days}}</td>
                                <td>{!! strip_tags($list->reason) !!}</td>
                                <td class="text-center">
                                    <a class="dropdown-item view-remaining-leave" href="#" data-id="{{ $list->user_id }}"> <i class="ri-eye-line m-r-5"></i></a> 
                                    |
                                    <a class="dropdown-item accept-leave" href="#" data-toggle="modal" data-target="#accept_leave" data-id="{{ $list->id }}"> <i class="ri-check-line m-r-5"></i> </a>
                                    |
                                    <a class="dropdown-item reject-leave" href="#" data-toggle="modal" data-target="#reject_reason" data-id="{{ $list->id }}"><i class="ri-close-line m-r-5"></i> </a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
