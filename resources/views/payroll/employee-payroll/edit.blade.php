
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Employee Payroll</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('payroll-user.update', $userPayroll->id)}}" id="edit_form" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    @foreach ($payrollComponents as $key=>$component )                  
                         @if( $loop->iteration%2 !=0 )
                             <div class="row">
                         @endif
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>{{  $key }} <span class="required-label">*</span></label>
                               @if ($loop->iteration==1 || $loop->iteration==2)
                                    <p>{{ $component}}</p>                               
                                @else
                                    <input class="form-control" name="{{ strtolower(str_replace(' ','_',$key)) }}" type="number" value="{{ $component }}" required>
                                    <div class="text-danger text-left field-error" id="label_{{ strtolower(str_replace(' ','_',$key)) }}"></div>
                                @endif 
                            </div>
                        </div>
                        @if( $loop->iteration%2==0 )
                             </div>
                        @endif
                    @endforeach                           
                    <div class="submit-section mt20">
                        <button class="btn btn-primary update-employee-payroll" type="submit">Update</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>