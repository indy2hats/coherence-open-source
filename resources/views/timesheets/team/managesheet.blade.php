<div class="row">
    <form id="my-team-timesheet-filter-form"  action="{{route('team-timesheet-search')}}" method="post">
        @csrf {{ csrf_field() }}

        @include('timesheets.team.filters.calender')
        @include('timesheets.team.filters.user-dropdown')

    </form>
    <div class="col-md-8 text-right float-right ml-auto m-b-30">
        @can('manage-team')
        <button class="btn btn-success manage-team-btn" data-toggle="modal" data-target="#add_team"><i class="ri-add-line"></i> Manage Team</button>
        @endcan
    </div>
</div>

@include('timesheets.team.sheet')

