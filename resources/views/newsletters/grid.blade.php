<div class="wrapper wrapper-content ">
    <div class="flexmasonry-layout">
        @foreach($newsletters as $newsletter)
            <div>
                <div class="ibox no-margins">
                    <div class="ibox-content text-center p-md">
                        <h4 class="m-b-xxs">{{$newsletter->title}} </h4>
                        <p>{{$newsletter->publish_date_format}} </p>
                        <div class="m-t-md">
                            <div class="">
                                <a target="_blank" href="{{asset('storage/'.$newsletter->newsletter)}}"><img class="img-fluid img-responsive img-shadow" src="{{asset('storage/'.$newsletter->screen_shot)}}"  alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
                 

