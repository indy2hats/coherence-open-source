<div id="download-payroll-file" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Export Payroll File </h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('payroll.export')}}" id="export_form" method="POST"  enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="filter" id="filter" />
                    <div class="row">
                        <div class="col-sm-10 col-md-10">
                            <div class="form-group">
                                <label>Month <span class="required-label">*</span></label>
                                <input class="form-control payroll-datepicker dateInput" type="text" name="month_year" id="month_year" placeholder="Select Month" value="{{ date('M Y') }}" />
                                <div class="text-danger text-left field-error" id="label_month_year"></div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="submit-section mt20" >
                        <button type="submit" id="export-btn" class="btn btn-primary download-file">Download</button>
                    </div>
                </form>             
            </div>
        </div>
    </div>
</div>