<!-- Branches Modal -->
<div class="modal custom-modal fade" id="save-selected-filters" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close branch-close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Save Filter</h4>
            </div>
            <div class="modal-body">
                </table>
                <form id="add_filter_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="append-new">
                        <div class="row" style="padding-top:10px;">
                            <div class="col-sm-12">
                                <div class="input-group" style="width: 100%;">
                                <input required type="text" class="form-control required input-value" type="text" name="filterName" id="filterName" placeholder="Filter Name">
                                <div class="text-danger text-left field-error" id="filterSaveError"></div>
                                <div class="text-success text-left field-error" id="filterSaveSuccess"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="submit-section mt20 text-right">
                    <button class="btn btn-primary save-filter-form">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Branches Modal -->
