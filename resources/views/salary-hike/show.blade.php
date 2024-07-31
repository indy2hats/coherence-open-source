<div class="row">

<div class="col-md-12 col-lg-8">
        <div class="heading-inline">
            <h3 class="no-margins" style="text-align:left;float:left;">Employee Name :
                <strong>{{$employee->full_name}}</strong>
            </h3>
            <hr style="clear:both;" />
        </div>

        <div class="m-b-md pt-15 tabs-container">
    
            <h4>Employee Hike History</h4>

            <div class="table-responsive hike-table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Hike ({{$salaryCurrency}})</th>
                            <th>Previous Salary ({{$salaryCurrency}})</th>
                            <th>Updated Salary ({{$salaryCurrency}})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employeeHikeHistory as $history)
                            <tr>
                                <td>{{ $history->date }}</td>
                                <td>{{ $history->hike }}</td>
                                <td>{{ $history->previous_salary }}</td>
                                <td>{{ $history->updated_salary }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="ri-information-line"></i> <strong>Salary Hike Details</strong>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-border">
                    <tbody>
                        <tr>
                            <td><strong>Employee Name </strong></td>
                            <td class="text-left">
                                <strong>{{$hikeHistory->user->first_name}} {{$hikeHistory->user->last_name}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Previous Salary </strong></td>
                            <td class="text-left">
                                <strong>{{$hikeHistory->previous_salary}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Updated Salary</strong></td>
                            <td class="text-left"><strong>{{$hikeHistory->updated_salary}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Hike </strong></td>
                            <td class="text-left">
                                <strong>{{$hikeHistory->hike}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date</strong></td>
                            <td class="text-left">
                                <strong>{{$hikeHistory->date}}</strong>
                            </td>
                        </tr>
                       
                        <tr>
                            <td><strong>Notes </strong></td>
                            <td class="text-left">
                                <strong>{{ $hikeHistory->notes }} </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>