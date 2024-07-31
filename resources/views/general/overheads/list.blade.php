
<div class="col-lg-12">
    <div class="ibox float-e-margins">
       <div class="row">
            <div class="col-lg-3">
                <h2 class="m-b-lg page-title">Monthly Overheads</h2>
                
            </div>
            <div class="col-lg-2 col-lg-offset-7 text-right">
                <div class="form-group" id="data_4">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control" id="table-list" value="{{request()->date}}">
                    </div>
                </div>
            </div>
       </div>
      
        <div class="ibox-content panel">
            <table class="table table-striped listTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $lists as $list )
                    <tr>
                        <td>{{date_format(new DateTime($list->date),'d M Y')}}</td>
                        <td>{{$list->type}}</td>
                        <td>{{$list->amount}}</td>
                        <td>{!!$list->description!!}
                        <td><a class='dropdown-item edit-overhead' href='#' data-id='{{$list->id}}' data-tooltip="tooltip" data-placement="top" title="Edit"> <i class='ri-pencil-line m-r-5'></i></a> | <a class='dropdown-item delete-overhead' href='#' data-toggle='modal' data-target='#delete_overhead' data-id='{{$list->id}} ' data-tooltip="tooltip" data-placement="top" title="Delete"><i class='ri-delete-bin-line m-r-5'></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>