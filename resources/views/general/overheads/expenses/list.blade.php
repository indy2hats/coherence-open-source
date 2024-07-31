
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="col-lg-8">
            <h2 class="m-b-lg  page-title">Monthly Expenses</h2>
        </div>
        <div class="ibox-content panel">
            <table class="table table-striped listExpenseTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $expenses as $list )
                    <tr>
                        <td>{{date_format(new DateTime($list->date),'d M Y')}}</td>
                        <td>{{$list->type}}</td>
                        <td>{{$list->amount}}</td>
                        <td><a class='dropdown-item edit-expense' href='#' data-id='{{$list->id}}' data-tooltip="tooltip" data-placement="top" title="Edit"> <i class='fa fa-pencil m-r-5'></i></a> |
                         <a class='dropdown-item delete-expense' href='#' data-toggle='modal' data-target='#delete_expense' data-id='{{$list->id}} ' data-tooltip="tooltip" data-placement="top" title="Delete"><i class='fa fa-trash-o m-r-5'></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>