<div class="row">
    <div class="col-md-12">
        <strong>
            <h2 class="page-title">New Applications </h3>
        </strong>
    </div>
</div>
<div class="content-div animated fadeInUp">
    <div class="list ibox-content">
        <div class="table-responsive">
            <div class="col-md-12">
                <div>
                    <table class="table cur-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Session</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)

                            <tr>
                                <td>{{$item->users->full_name}}</td>
                                <td>{{$item->date}}</td>
                                <td>{{$item->session}}</td>
                                <td>{!! strip_tags($item->reason) !!}</td>
                                <td>
                                    <a class="dropdown-item accept-application" href="#" data-toggle="modal"
                                        data-target="#accept_application" data-id="{{ $item->id }}"> <i
                                            class="ri-check-line m-r-5"></i> </a>
                                    |
                                    <a class="dropdown-item reject-application" href="#" data-toggle="modal"
                                        data-target="#reject_reason" data-id="{{ $item->id }}"><i
                                            class="ri-close-line m-r-5"></i></a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <strong>
            <h2 class="page-title">Approved Applications </h3>
        </strong>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <select class="chosen-select" id="user" name="user">
                <option value="">Select User</option>
                @foreach($users as $user)
                <option value="{{$user->id}}">
                    {{$user->full_name}}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group" id="data_3">

            <div class="input-group date">

                <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text"
                    class="form-control year-search" id="date-chart" value="{{$year}}">

            </div>

        </div>
    </div>
</div>

<div class="content-div animated fadeInUp">
    <div class="previous-app ibox-content">
        @include('compensatory.admin.previous')
    </div>
</div>