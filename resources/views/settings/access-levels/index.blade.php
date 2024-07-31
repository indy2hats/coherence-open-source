@extends('layout.main')

@section('content')
    <div class="content-div animated fadeInUp">
        <div class="row " style="padding-bottom: 20px">
            <div class="col-md-6">
                <h2 class="page-title">User Access Levels</h3>
            </div>
            <div class="col-md-6 text-right">
                @can('manage-roles')
                    <button class="btn btn-success" data-toggle="modal" data-target="#add_role"><i class="ri-add-line"></i> Add New Role</button>
                @endcan
                @can('manage-permissions')
                    <button class="btn btn-info" data-toggle="modal" data-target="#add_permission"><i class="ri-add-line"></i> Add New Permission</button>
                @endcan
            </div>
        </div>

        <div class="row panel ibox-content" id="level_container"> 
            @include('settings.access-levels.list')
        </div>
    </div>
    @can('manage-roles')
        @include('settings.access-levels.add-role')
    @endcan
    @can('manage-permissions')
        @include('settings.access-levels.add-permission')
    @endcan
    @include('settings.access-levels.delete')

@endsection

@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ asset('js/resources/settings/access-levels/script-min.js') }}"></script>
@endsection