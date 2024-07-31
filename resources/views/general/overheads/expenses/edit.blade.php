<!-- Edit Expense Modal -->
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Expense</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('expenses.update', $expense->id)}}" id="edit_expense_form" method="POST" autocomplete="off">
                    @csrf
                    <input name="_method" type="hidden" value="PUT">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <div class="form-group" id="data_5">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span>
                                        <input type="text" class="form-control" name="date" id="date" value="{{ $expense->date }}" readonly>
                                    </div>
                                </div>
                                <div class="text-danger text-left field-error" id="edit_label_date"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Type <span class="required-label">*</span></label>
                                <input type="text" placeholder="Enter the type" class="typeahead_expense form-control" name="type" value="{{$expense->type}}"/>
                                <div class="text-danger text-left field-error" id="label_type"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Amount <span class="required-label">*</span></label>
                                 <input class="form-control" type="number" name="amount" min="0" data-decimal="2"  value="{{$expense->amount}}">
                                <div class="text-danger text-left field-error" id="label_amount"></div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-expense">Update</button>
                </div>
        </div>
    </div>
</div>

<!-- /Edit Expense Modal -->