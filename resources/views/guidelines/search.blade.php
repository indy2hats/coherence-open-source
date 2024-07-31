
                        <div class="col-sm-12">
                            <div class="ibox-content">
                            <div class="form-group col-md-4">
                                 <select class="chosen-select category_type" id="category_type" name="category_type">
                                    <option value="">Select Tag</option>
                                    @foreach($data as $item)
                                    <option value="{{$item->title}}">{{$item->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#create_tag"><i class="ri-add-line"></i> Add Tag</a>
                            </div>
                        </div>
