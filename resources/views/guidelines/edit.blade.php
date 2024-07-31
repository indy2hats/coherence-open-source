<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Edit Guideline</h4>
        </div>
        <div class="modal-body">
            <form action="{{route('guidelines.update', $item->id)}}" id="edit_form" method="POST" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Tag </label>
                             <select class="chosen-select" id="category" name="category[]" multiple>
                                @foreach($data as $type)
                                <option value="{{$type->title}}" {{in_array($type->title,unserialize($item->type))?'selected':''}}>{{$type->title}}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-left field-error" id="label_edit_category"></div>
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
                            <input class="form-control" type="text" name="title" id="title" value="{{$item->title}}">
                            <div class="text-danger text-left field-error" id="label_edit_title"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                           <label>Content <span class="required-label">*</span></label>
                            <textarea class="form-control summernote" rows="10" id="content" name="content">{!! $item->content !!}</textarea>
                            <div class="text-danger text-left field-error" id="label_edit_content"></div>
                        </div>
                    </div>
                </div>

                <div class="submit-section">
                    <button class="btn btn-primary submit-btn update-guideline">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>