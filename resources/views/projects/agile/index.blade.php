@extends('layout.main')

@section('content')
<!-- page title -->

@can('manage-tasks')
    <div class="row" style="text-align:right;margin-bottom:20px">
        <div class="col-md-12 col-md-12">
            <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_task"><i class="ri-add-line"></i> Add Task</a>
        </div>
    </div>    
@endcan

<div class="row">
    <div class="col-md-4 col-md-4">
        <strong>
            <h2 id="project_id" data-id="{{$project->id}}" class="page-title">Agile Board - {{$project->project_name}}</h3>
        </strong>
    </div>
    <div class="col-md-2 col-md-2">
    <a href="/projects/{{$project->id}}" class="btn btn-w-m btn-info">Switch to List View</a>
    </div>
    <div class="col-sm-2 col-md-2">
        <div class="form-group form-focus select-focus focused">
            <form action="/search-board/{{$project->id}}" method="GET" id="search_form">
                @csrf
                <input type="hidden" name="project_id" value="{{$project->id}}">
                <select class="chosen-select" id="task_id" name="task_id" onchange="$('#search_form').submit();">
                    <option value="">All Parent Tasks</option>
                    @foreach($parentTasks as $task)
                    <option value="{{$task->id}}" {{request()->task_id == $task->id ? 'selected' : ''}}>{{$task->title}}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    <div class="col-md-4 col-md-4" style="text-align:right">
        @can('view-project-credentials')
            <a href="/project-credentials/{{ $project->id }}"><button class="btn btn-info"><i class="ri-eye-off-line"></i> Manage Credentials</button></a>
        @endcan
        @can('view-project-documents')
            <a href="/project-documents/{{ $project->id }}"><button class="btn btn-primary"><i class="ri-attatchment-2"></i> Manage Documents</button></a>
        @endcan
    </div>
</div>

@include('tasks.create')
@include('tasks.create-tag')
<!-- /page title -->
<div class="content-div animated fadeInUp">

    <div class="list" style="width:100%; overflow-x: scroll;white-space: nowrap;max-height: calc(70vh);">

        @include('projects.agile.board')

    </div>

</div>
<?php
 $ids = '';
 $len = count($data);
 $i=0;
 foreach($data as $item){
        $ids = $ids . "#".preg_replace('/\s+/', '_',strtolower($item['type']));
        if ($i != $len - 1){
            $ids = $ids . ', ';

        }
        $i++;
    }
?>
<div data-id="{{$ids}}" class="div-ids"></div>

@endsection

@section('after_scripts')
<style type="text/css">
    .board-div {
        width: 26%;
        display: inline-table;
    }
    .new-col{
        position: relative;
    }

</style>

<script src="{{ asset('js/plugins/touchpunch/jquery.ui.touch-punch.min.js') }}"></script>
<script>
    $(document).ready(function() {

        $('.chosen-select').chosen({
            width: "100%"
        });
        
        $($('.div-ids').attr('data-id')).sortable({

            connectWith: ".connectList",

            update: function(event, ui) {

                update(event.target.id, $("#" + (event.target.id)).sortable("toArray"));

            }

        }).disableSelection();


    });

    function getBoard() {
         $.ajax({
            method: 'POST',
            url: '/update-order',
            data: {
                'id':$('#task_id').val() ,
            },
            success: function(response) {
                $('.list').html(response.data);
                loadBoardScript();
            },
            error: function(error) {
                toastr.error('Something went wrong!', 'Error');
            }
        });
    }


    function update(status, list) {
        var parent_id='';
        if($('#task_id').val() != ''){
            parent_id=$('#task_id').val();
        }
        $.ajax({
            method: 'POST',
            url: '/update-order',
            data: {
                'status': status,
                'list': list,
                'parent_id':parent_id
            },
            success: function(response) {},
            error: function(error) {
                toastr.error('Something went wrong!', 'Error');
            }
        });
    }

    $(document).on('click','.single-click-event',function (e) {
        e.preventDefault();
        window.location.href = "/tasks/"+$(this).attr('id');
    });
</script>

<link href="{{ asset('css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/plugins/c3/c3.min.js') }}"></script>
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('js/resources/projects/view-script-min.js') }}"></script>
@endsection