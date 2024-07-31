<div id="create_guideline" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Guideline</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('guidelines.store')}}" id="add_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tag </label>
                                  <select class="chosen-select" id="category" name="category[]" multiple>
                                    @foreach($data as $item)
                                    <option value="{{$item->title}}">{{$item->title}}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger text-left field-error" id="label_category"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Add New Tag</label>
                                <input class="form-control" type="text" name="new_tag" id="new_tag">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Title <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="title" id="title">
                                <div class="text-danger text-left field-error" id="label_title"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Content <span class="required-label">*</span></label>
                                <textarea class="form-control summernote" rows="10" name="content"></textarea>
                                <div class="text-danger text-left field-error" id="label_content"></div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create-guideline">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>