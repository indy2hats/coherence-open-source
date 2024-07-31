<div id="add-salary-component" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add Salary Component </h4>
            </div>
            <div class="modal-body">
                <form action="{{route('salary-component.store')}}" id="add_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="title" id="title">
                                <div class="text-danger text-left field-error" id="label_title"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-md-8">
                            <div class="form-group">
                                <label>Type <span class="required-label">*</span></label>
                                <select class="form-control chosen-select" id="type" name="type">
                                    @foreach ($type as $option)
                                    <option value="{{ $option }}">{{ ucwords($option)}}</option>                                        
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_type"></div>
                            </div>
                        </div>
                   
                        <div class="col-sm-4"> 
                            <label>Status <span class="required-label">*</span></label>
                            <div class="form-group ">                                
                                <input type="checkbox" class="form-check-input" name="status" checked="" id="status">
                                <label class="col-form-labelform-check-label" for="status">Active</label>                    
                            </div>
                        </div>
                    </div>
                    <div class="submit-section mt20">
                        <button type="submit" class="btn btn-primary create-salary-component">Add</button>
                    </div>
                </form>             
            </div>
        </div>
    </div>
</div>