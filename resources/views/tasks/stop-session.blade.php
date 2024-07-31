<div class="modal custom-modal fade" id="stop_session" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Stop Task Session</h4>
            </div>
            <div class="modal-body">
                <div class="form-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-focus select-focus focused">
                                <label for="">Session Type </label>
                                <select class="chosen-select" id="stop_session_type" name="session_type">
                                    @foreach(\App\Models\SessionType::all() as $sessionType)
                                        <option 
                                            @if(auth()->user()->session_slug == $sessionType->slug) selected @endif
                                            value="{{$sessionType->slug}}">
                                            {{$sessionType->title}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Describe what you have done in this session? <span class="required-label">*</span></label>
                                <div>
                                    <textarea rows="4" class="form-control" id="stop_session_comment"></textarea>
                                    <div class="text-danger text-left field-error" id="label_comment"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-btn stop-action">
                    <div class="row" style="padding-top: 10px">
                        <div class="col-sm-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default float-right cancel-btn">Cancel</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="javascript:void(0);" class="btn btn-primary continue-btn">Stop Session</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>