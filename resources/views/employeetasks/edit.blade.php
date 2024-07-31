    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Session</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('task-session.update',$session->id)}}" method="post" autocomplete="off" id="edit_session_form">
                    <input type="hidden" name="task_id" value="{{ $session->task_id }}">
                    @csrf
                        <div class="row">
                            <div class='col-sm-12'>
                                <label class="col-form-label">Date <span class="required-label">*</span></label>
                                <div class="form-group" id="data_1">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="ri-calendar-2-line"></i></span><input type="text" class="form-control" value="{{Carbon\Carbon::parse($session->created_at)->format('d/m/Y') }}" name="date">
                                    </div>
                                </div>
                                <div class="text-danger text-left field-error" id="label_edit_date"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Time<span class="required-label">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" name="total" id="edit-session-box-time" value="{{round($session->total / 60, 2)}}" required>
                                        <div class="text-danger text-left field-error" id="label_edit_total"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-focus select-focus focused">
                                <label for="">Session Type </label>
                                <select class="chosen-select" id="session_type" name="session_type">
                                   <!--  <option @if($session->session_type == 'development') selected @endif value="development">Development</option>
                                    <option @if($session->session_type == 'project-management') selected @endif value="project-management">Project Management</option>
                                    <option @if($session->session_type == 'qa') selected @endif value="qa">QA</option>
                                    <option @if($session->session_type == 'others') selected @endif value="others">Others</option> -->
                                    @foreach(\App\Models\SessionType::all() as $sessionType)
                                        <option @if( $sessionType->slug == $session->session_type ) selected @endif value="{{$sessionType->slug}}">{{$sessionType->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Comments <span class="required-label">*</span></label>
                                    <div>
                                        <textarea rows="4" class="form-control" name="comments" id="edit-session-box-comments">{{$session->comments}}</textarea>
                                    </div>
                                    <div class="text-danger text-left field-error" id="label_edit_comments"></div>
                                </div>
                            </div>
                        </div>
                </form>
                <div class="row">
                    <div class="submit-section text-right col-sm-12">
                        <button class="btn btn-primary submit-btn edit-session">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
