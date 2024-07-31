
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Change Base Currency</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('changeCurrency')}}" id="change_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Select Base Currency <span class="required-label">*</span></label>
                                 <select class="chosen-select" id="currency" name="currency">
                                    @foreach($list as $key=>$value)
                                    <option value="{{$key}}" {{$key == $base?'selected':''}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn change-currency">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
