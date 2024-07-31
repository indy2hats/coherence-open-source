<div id="create_technology" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Technology</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('project-technologies.store')}}" id="add_technology_form" method="POST" autocomplete="off">
                    @csrf
                    
                            <div class="form-group">
                                <label>Name <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="name" id="name">
                                <div class="text-danger text-left field-error" id="label_name"></div>
                            </div>
                    
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create_technology">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>