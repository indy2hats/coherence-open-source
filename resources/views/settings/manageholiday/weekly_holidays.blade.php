<!-- Weekly Holiday Modal -->
<div id="weekly_holiday" class="modal custom-modal animated fadeInUp" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Weekly Holidays</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="weekly-holidays">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" @if(in_array('Sunday',$days)) checked @endif id="Sunday" value="Sunday"><label class="col-form-labelform-check-label" for="Sunday">Sunday</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" @if(in_array('Monday',$days)) checked @endif id="Monday" value="Monday"><label class="col-form-labelform-check-label" for="Monday">Monday</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" @if(in_array('Tuesday',$days)) checked @endif id="Tuesday" value="Tuesday"><label class="col-form-labelform-check-label" for="Tuesday">Tuesday</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" @if( in_array('Wednesday',$days)) checked @endif id="Wednesday" value="Wednesday"><label class="col-form-labelform-check-label" for="Wednesday">Wednesday</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" @if(in_array('Thursday',$days)) checked @endif id="Thursday" value="Thursday"><label class="col-form-labelform-check-label" for="Thursday">Thursday</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" @if(in_array('Friday',$days)) checked @endif id="Friday" value="Friday"><label class="col-form-labelform-check-label" for="Friday">Friday</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" @if(in_array('Saturday',$days)) checked @endif id="Saturday" value="Saturday"><label class="col-form-labelform-check-label" for="Saturday">Saturday</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <div class="text-right">
                       <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Apply</a>   
                        </div>
                    </div>
        </div>
    </div>
</div>
<!-- /Weekly Holiday Modal -->