<div class="row">
        <div class="col-md-7 pull-left">
            <strong>
                <h2 class="page-title">{{$item->title}}</h3>
            </strong>
        </div>
        <div class="col-md-5 text-right ml-auto m-b-30">
            <a href="#" data-id="{{$item->id}}" class="btn btn-w-m btn-success edit-guideline" data-toggle="modal" data-target="#edit_guideline"><i class="ri-eye-line"></i> Edit</a>
            <a href="#" data-id="{{$item->id}}" class="btn btn-w-m btn-success delete-guideline" data-toggle="modal" data-target="#delete_guideline"><i class="ri-delete-bin-line" aria-hidden="true"></i> Delete</a>
        </div>
    </div>
    
    <div class="content-div animated fadeInUp">
        <div class="ibox-content">
            {!! $item->content !!}
        </div>
    </div>