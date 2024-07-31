<div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Hike ({{$salaryCurrency}})</th>
                    <th>Date</th>
                    @can('manage-salary-hike')<th>Action</th>@endcan
                </tr>
            </thead>
            <tbody>
                @if(count($employeeHikeHistory) > 0)
                @foreach ($employeeHikeHistory as $employeeHike)
                <tr>
                    <td>{{ $employeeHike->user->first_name }} {{ $employeeHike->user->last_name }}</td>

                    <td>{{ $employeeHike->hike }}</td>
                    <td>{{ $employeeHike->date }}</td>
                    <td><a class="dropdown-item" href="salary-hike/{{ $employeeHike->id }}"  data-tooltip="tooltip"
                        data-placement="top" title="View"><i class="ri-eye-line"></i></a></td>
                </tr>
                @endforeach
                @else
                <tr>
                     <td colspan="7" align="center">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="pagination-div">
            {{$employeeHikeHistory->links()}}
        </div>
</div>