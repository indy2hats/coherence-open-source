
    <div class="modal-dialog modal-dialog-centered" role="document" id="ticket_status_edit">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Edit Status</h4>
            </div>
            <div class="modal-body">
                <form  action="{{ route('assets.ticket-status-update') }}" id="ticket_status_update_form" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" id="ticket_id" name="id" value="{{ $ticket->id }}">
                    <div class="row">
                            <div class="form-group">
                                <label>Status <span class="required-label">*</span></label>
                                <select class="chosen-select" id="status_id" name="status_id">
                                    <option value="">Select Status</option>
                                    @foreach ($ticketStatus as $status)
                                    <option value="{{$status->id}}" {{ $status->id == $ticket->status_id ? 'selected' : ''}}>{{$status->title}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_status_id"></div>
                            </div>
                    </div>
                   
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn ticket_status_update">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>