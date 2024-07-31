
<div class="footer">
    <div>
        <strong>Copyright</strong> 2Hats Logic Pvt Ltd &copy; {{ date("Y") }}
    </div>
</div>

<div class="floatingButtonWrap">
    <div class="floatingButtonInner">
        <a href="#" class="floatingButton">
            <i class="epms-icon--1x ri-add-line"></i>
        </a>
        <ul class="floatingMenu">
        	@include('partials.easy-access')
        </ul>
    </div>
</div>
@if(Auth::user()->wish_notify == 1)
    @php
        $wishNotify = \App\Models\UserWish::where('date', date('Y-m-d'))->where('user_id', Auth::user()->id)->first();
        if (!$wishNotify) {
            $wishNotify = \App\Models\UserWish::where('date', date('Y-m-d'))->where('user_id', 0)->first();
        }
    @endphp
	<!-- Creates the bootstrap modal where the image will appear -->
	@if($wishNotify)
		<div class="modal fade" id="wishmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog" style="width:650px;">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        @if($wishNotify->user_id != 0)
                        <h4 class="modal-title" id="myModalLabel">{{$wishNotify->title}} {{Auth::user()->nick_name}}, You are awesome!!</h4>
                    @else
                        <h4 class="modal-title" id="myModalLabel">{{$wishNotify->title}}</h4>
			        @endif
			      </div>
			      <div class="modal-body modal-content-type" data-id="{{$wishNotify->type}}" data-file_type="{{$wishNotify->file_type}}" style="padding: 0px;">
			      	<button id="video_btn" style="display: none;"></button>
                    @if($wishNotify->file_type == 'Image')
			        <a href="{{ asset('storage/'.$wishNotify->image) }}" target="_blank"><img style="width:100%;" src="{{ asset('storage/'.$wishNotify->image) }}" id="imagepreview" src > </a>
                    @elseif($wishNotify->file_type == 'Video')
			        <video  webkit-playsinline="true"
    playsinline="true" id="video" width="320" height="240" controls autoplay style="max-height:500px;padding: 10px;
    outline: none;">
					  <source src="{{ asset('storage/'.$wishNotify->image) }}" type="video/mp4">
					Your browser does not support the video tag.
					</video>
                    @else
                    <div class="row" style="margin: 20px;border: 1px solid #ccc;border-radius: 5px;padding: 5px">{!!$wishNotify->image!!}
                            </div>
                    @endif
			      </div>
			  </div>
			</div>
		</div>
	@endif
@endif

@if(Auth::user()->dsr_notify == 1)
	<div class="modal fade" id="eodmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog" style="width:500px;">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel">Hi, {{Auth::user()->full_name}}</h4>
			      </div>
			      <div class="modal-body text-center text-danger" style="padding:30px;">
			      	<p style="" class="text-center"><b>Please fill your daily status report before you leave.You can find the option to add report on top right near logout.</b></p>
			      	<img src="{{asset('images/dsr.png')}}" style="width:200px;margin-top:20px;opacity: 0.5;" alt="logo">
			      </div>
			  </div>
			</div>
		</div>
@endif

<div id="change_easy_access" class="modal custom-modal fade" role="dialog" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('addEasyAccess')}}" id="add_easy_access_form" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Link <span class="required-label">*</span></label>
                                <input class="form-control" type="text" name="link" id="link" value="">
                            </div>
                        </div>
                    </div>
                </form>
                
                    <div class="submit-section mt20">
                        <button class="btn btn-primary add-to-easy-access">Save</button>
                    </div>
            </div>
        </div>
    </div>
</div>