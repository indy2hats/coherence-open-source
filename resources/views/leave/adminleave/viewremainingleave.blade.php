<!-- Remaining Leave Modal -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Remaining Leaves</h4>
            </div>
            <div class="modal-body">
                <div class="form-header">
                    <table class="table table-striped">
                        <thead>
                        <th>Medical</th>
                        <th>Casual</th>
                        <th>LOP Taken</th>
                        <th>Compensatory Taken</th>
                        <th>Compensatory Balance</th>
                        @if(array_key_exists('paternity',$balance))
                            <th>Paternity</th>
                            @endif
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$balance['medical']}}</th>
                            <td>{{$balance['casual']}}</td>
                            <td>{{$balance['lop']}}</td>
                            <td>{{$balance['compensatory']}}</td>
                            <td>{{$balance['compensatory_available']-$balance['compensatory']}}</td>
                            @if(array_key_exists('paternity',$balance))
                            <td>{{ $balance['paternity'] }}</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-btn">
                    <div class="row" style="padding-top: 10px">
                       
                        <div class="col-sm-12 text-right">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /Remaining Leave Modal -->