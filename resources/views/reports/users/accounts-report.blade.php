@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-12">
        <strong>
            <h2 class="page-title">User Account Report</h3>
        </strong>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">

        <div class="ibox float-e-margins">
            <div class="ibox-content ">
                <div class="row">
                    <div class="col-lg-3 col-lg-offset-9 p-w-xl">
                        <div class="form-group arrow">
                            <select id='status' class="form-control chosen-select"
                                placeholder="Select 2FA status">
                                <option value="">Select 2FA Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="ibox-content animated fadeInUp">
                    <div class="table-responsive">
                        <table class="table table-stripped data-table userAccountReportTable">
                            <thead>
                                <tr>

                                    <th>User Name</th>
                                    <th>User Role</th>
                                    <th>Two Factor Enabled</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/reports/users/script-min.js') }}"></script>
@endsection