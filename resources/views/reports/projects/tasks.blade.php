@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-12">
        <strong><h2 class="page-title">{{ $project->project_name }} - Task List</h3></strong>
                    </div>
</div>
  <div class="row">

                <div class="col-lg-12">

                <div class="ibox float-e-margins">

                    

                    <div class="ibox-content">
                        <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover taskTable">

                    <thead>

                    <tr>

                        <th>Task Title</th>

                        <th>Billable Hours</th>

                        <th>Non-Billable Hours</th>

                        <th>Total Hours</th>

                    </tr>

                    </thead>

                    <tbody>
                          @foreach($tasks as $task)


                        <td>{{$task->title}}</td>
                        <td>{{floor($task->billed_today/60).'h '.($task->billed_today%60).'m'}}</td>
                        <td>{{floor(($task->total - $task->billed_today)/60).'h '.(( $task->total - $task->billed_today)%60).'m'}}</td>
                        <td>{{floor($task->total/60).'h '.($task->total%60).'m'}}</td>

                    </tr>
                    @endforeach
                    </tbody>
                    </table>

                        </div>



                    </div>

                </div>

            </div>

            </div>
@endsection
@section('after_scripts')
@include('reports.projects.page-script')
@endsection
