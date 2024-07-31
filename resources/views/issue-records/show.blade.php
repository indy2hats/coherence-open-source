@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">{{$issue->title}}</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success edit-issue" data-id="{{$issue->id}}" aria-hidden="true"><i class="ri-pencil-line"></i> Edit Issue</a>
        <a  href="#" class="btn btn-w-m btn-danger delete_issue_onclick" data-id="{{$issue->id}}" data-toggle="modal" data-target="#delete_issue" aria-hidden="true"><i class="ri-delete-bin-line"></i> Delete Issue</a>
    </div>
</div>
<div class="list animated fadeInUp">
    <div class="ibox-content">
    	<ul class="list-group clear-list">
            <li class="list-group-item row" style="border-top:none;">
                <span class="col-sm-2"> <label>Title</label> </span>
                <span class="col-sm-10"> {{$issue->title}} </span>
            </li>
            <li class="list-group-item row">
                <span class="col-sm-2"> <label>Category</label> </span>
                <span class="col-sm-10"> {{ucwords(str_replace('-', ' ', $issue->category))}} </span>
            </li>
            <li class="list-group-item row">
                <span class="col-sm-2"> <label>Project</label> </span>
                <span class="col-sm-10"> <a href="{{url('/projects/'.$issue->project->id)}}">{{$issue->project->project_name}}</a> </span>
            </li>
            <li class="list-group-item row">
                <span class="col-sm-2"> <label>Added By</label> </span>
                <span class="col-sm-10"> {{$issue->addedBy->full_name ?? ''}} </span>
            </li>
            <li class="list-group-item row">
                <span class="col-sm-2"> <label>Added On</label> </span>
                <span class="col-sm-10"> {{$issue->created_at}} </span>
            </li>
            <li class="list-group-item row">
                <span class="col-sm-2"> <label>Issue</label> </span>
                <span class="col-sm-10"> {!! nl2br($issue->description) !!} </span>
            </li>
            <li class="list-group-item row">
                <span class="col-sm-2"> <label>Solution</label> </span>
                <span class="col-sm-10"> {!! nl2br($issue->solution) !!} </span>
            </li>
        </ul>
    </div>
</div>
<div id="edit_issue" class="modal custom-modal fade" role="dialog">
</div>
@include('issue-records.create-category')
@include('issue-records.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/issue-records/script-min.js') }}"></script>
@endsection