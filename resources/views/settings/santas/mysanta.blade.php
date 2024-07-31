
<!DOCTYPE html>
<!-- saved from url=(0032) -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>Secret Santa Page</title>

	<!-- Behavioral Meta Data -->
	<link href="{{ asset('img/newfav.png') }}" rel="icon" type="image/png">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="{{asset('santa/css/bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('santa/css/style.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('santa/css/media-query.css')}}">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Lobster%7CParisienne%7CRaleway:200,300" rel="stylesheet">
</head>
<body>
	<div id="loader"></div>
	<!-- Parallax Container -->
	<div id="container" class="parallax-container snow">
		<ul id="christmas_scene" class="christmas-scene" >
			<li class="layer" data-depth="0.80" ><div class="layer-5 layer-photo photo-zoom"></div></li>
			<li class="layer" data-depth="0.60" ><div class="layer-4 layer-photo photo-zoom"></div></li>
			<li class="layer" data-depth="0.40" ><div class="layer-3 layer-photo photo-zoom"></div></li>
			<li class="layer" data-depth="0.20" ><div class="layer-2 layer-photo photo-zoom"></div></li>
			<li class="layer" data-depth="0.00" ><div class="layer-1 layer-photo"></div></li>
		</ul>
		<!-- Countdown Container -->
		<div id="countdown_container">
			<div class="col-md-3 col-xs-3 countdown-globe"><span id="days">00</span>
				<div class="col-md-12 padding-none">Days</div>
			</div>
			<div class="col-md-3 col-xs-3 countdown-globe"><span id="hours">00</span>
				<div class="col-md-12 padding-none">Hours</div>
			</div>
			<div class="col-md-3 col-xs-3 countdown-globe"><span id="minutes">00</span>
				<div class="col-md-12 padding-none">Minutes</div>
			</div>
			<div class="col-md-3 col-xs-3 countdown-globe"><span id="seconds">00</span>
				<div class="col-md-12 padding-none">Seconds</div>
			</div>
		</div>
		<!-- Merry Christmas Text -> You can replace with anything you like! -->
		<div class="merry-christmas-text">Merry Christmas</div>

		<!-- Happy new year 2017 photo -->
		<div class="happy-new-year"></div>

		<!-- Contact Pole Image -> From here the E-mail modal is triggered -->
		<div id="mail_pole">
			<img src="{{asset('santa/images/santa-clause.png')}}" class="img-responsive" alt="mail-pole">
		</div>
		<!-- Christmas Tree -->
		<img src="{{asset('santa/images/christmas-tree.png')}}" alt="christmas-tree" id="christmas_tree" >

		<!-- Social Media Icons Container -->
		<div class="logo-container">
			<audio  hidden="" controls="" autoplay="" loop="">
				<source src="{{asset('santa/audio/jingle_bell.mp3')}}" type="audio/mp3">
			</audio>
			<a href="{{url('/')}}" title="Home"><img src="{{asset('santa/images/happy-new-year.png')}}" alt="facebook-social-icon"></a>
		</div>
		<div class="icons-container">
			<a href="#" data-toggle="modal" data-target="#santa_modal"><img data-toggle="tooltip" data-placement="top" title="View My Friend" src="{{asset('santa/images/santa.png')}}" alt="google-plus-social-icon"></a>
			<a href="#" data-toggle="modal" data-target="#contact_modal" ><img data-toggle="tooltip" data-placement="top" title="Send A Message" src="{{asset('santa/images/send-message.png')}}" alt="facebook-social-icon"></a>
			<a href="#" data-toggle="modal" data-target="#message_modal"><img data-toggle="tooltip" data-placement="top" title="View Messages" src="{{asset('santa/images/messages.png')}}" alt="facebook-social-icon"></a>
			@if ($santa->send_wish == 0)
				<a id="wish_btn" href="#" data-toggle="modal" data-target="#wish_modal"><img data-toggle="tooltip" data-placement="top" title="Make A Wish" src="{{asset('santa/images/make-a-wish.png')}}" alt="facebook-social-icon"></a>
			@endif
		</div>
	</div>

	<!-- Send E-mail Modal -->
	<div class="modal fade" id="contact_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class="col-md-12 mail-container">
						<div class="col-md-12 padding-none bg-color">
							<div class="col-md-12 text-center">It's Christmas!</div>
							<div class="col-md-12 text-center">Send your secret friend a message!</div>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<form class="col-md-12 padding-none" style="padding-top: 20px;">
								<div class="col-md-6 col-xs-12">
                                    @csrf
									<!-- <div class="col-md-12 padding-none">
										<input type="text" class="mail-first-name" id="form_first_name" placeholder="First Name">
									</div>
									<div class="col-md-12 padding-none">
										<input type="text" class="mail-last-name" id="form_last_name" placeholder="Last Name">
									</div>
									<div class="col-md-12 padding-none">
										<input type="text" class="mail-email" id="form_valid_email" placeholder="E-mail">
                                    </div> -->
									<div class="col-md-12 padding-none">
										<textarea name="message" class="mail-message" id="form_message" placeholder="Message"></textarea>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 text-center">
									<img src="{{asset('santa/images/form-image.png')}}" alt="form-pattern-image">
									<button class="btn form-submit-button" type="submit" id="submit_form_btn">Send</button>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-12 padding-none mail-container hidden">
                        <button type="button" class="close" style="right:10px;top:-5px;z-index:1;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<div class="col-md-12 padding-none bg-color thank-you-msg text-large" id="form_success_msg"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Send E-mail Modal -->
	<div class="modal fade" id="santa_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class="col-md-12 mail-container">
						<div class="col-md-12 padding-none bg-color">
							<div class="col-md-12 text-center">You will be the secret santa to this person!</div>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<form class="col-md-12 padding-none" style="padding-top: 20px;">
								<div class="col-md-6 col-xs-12">
									<!-- <div class="col-md-12 padding-none">
										<input type="text" class="mail-first-name" id="form_first_name" placeholder="First Name">
									</div>
									<div class="col-md-12 padding-none">
										<input type="text" class="mail-last-name" id="form_last_name" placeholder="Last Name">
									</div>
									<div class="col-md-12 padding-none">
										<input type="text" class="mail-email" id="form_valid_email" placeholder="E-mail">
									</div> -->
									<div class="col-md-12 padding-none" style="padding-top:15px;font-size: 24px;">
										<p>{{$santa->giftee->user->full_name}}</p>
										<p>Ph: {{ $santa->giftee->phone}}</p>
										<p></p>
										<p></p>
										<p>{!! nl2br($santa->giftee->address) !!}
										</p>
									</div>
                                </br></br>
								</div>
								<div class="col-md-6 col-xs-12 text-center">
									<img src="{{ asset('storage/'.$santa->giftee->image) }}" alt="form-pattern-image">
								</div>

								<div class="col-md-12" style="padding-top:10px;font-size: 24px;">
									<p>Friend's Wish</p>
									<p></p>
									<p style="font-size: 20px;">{!! nl2br($santa->wish) !!}</p>
								</div>

							</form>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- Send E-mail Modal -->
	<div class="modal fade" id="message_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class="col-md-12 mail-container">
						<div class="col-md-12 padding-none bg-color">
							<div class="col-md-12 text-center">Message From Santa!</div>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<form class="col-md-12 padding-none" style="padding-top: 20px;">
								<div class="col-md-12 col-xs-12">

                                    <div class="col-md-12 padding-none" style="padding-top:15px;font-size: 18px;">
                                        @forelse($messages as $message)
                                            <p>{{$message->content}}</p>
                                            <hr>
                                        @empty
                                            <p>No Messages Received.</p>
                                        @endforelse
									</div>
								</div>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>


	<!-- Send E-mail Modal -->
	<div class="modal fade" id="wish_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class="col-md-12 mail-container">
						<div class="col-md-12 padding-none bg-color">
							<div class="col-md-12 text-center">It's Christmas!</div>
							<div class="col-md-12 text-center">Make a wish to your secret santa!</div>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<form class="col-md-12 padding-none" style="padding-top: 20px;">
								<div class="col-md-6 col-xs-12">
                                    @csrf
									<!-- <div class="col-md-12 padding-none">
										<input type="text" class="mail-first-name" id="form_first_name" placeholder="First Name">
									</div>
									<div class="col-md-12 padding-none">
										<input type="text" class="mail-last-name" id="form_last_name" placeholder="Last Name">
									</div>
									<div class="col-md-12 padding-none">
										<input type="text" class="mail-email" id="form_valid_email" placeholder="E-mail">
                                    </div> -->
									<div class="col-md-12 padding-none">
										<textarea name="message" class="mail-message" id="form_message" placeholder="Make a wish!"></textarea>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 text-center">
									<img src="{{asset('santa/images/wish-image.png')}}" alt="form-pattern-image">
									<button class="btn form-submit-button" type="submit" id="submit_form_btn">Send</button>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-12 padding-none mail-container hidden">
                        <button type="button" class="close" style="right:10px;top:-5px;z-index:1;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<div class="col-md-12 padding-none bg-color thank-you-msg text-large" id="form_success_msg"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="{{asset('santa/js/jquery-3.1.1.min.js')}}"></script>
	<script src="{{asset('santa/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('santa/js/jquery.countdown.js')}}"></script>
	<script src="{{asset('santa/js/jquery.parallax.js')}}"></script>
	<script src="{{asset('santa/js/snow.js')}}"></script>
	<script src="{{asset('santa/js/main.js')}}"></script>
	<script>
		// Parallax Init
		$('#christmas_scene').parallax({
			scalarX: 5,
			scalarY: 15,
			invertY: false
		});

        $('#contact_modal').on('hidden.bs.modal', function () {
            $("#contact_modal .mail-container").removeClass('hidden');
		    $("#contact_modal #form_success_msg").parent().addClass('hidden');
		});

		$('[data-toggle="tooltip"]').tooltip();

		$("body").one('click', function(){
			const audio = document.querySelector("audio");
			audio.volume = 0.2;
			audio.play();
		});
	</script>


</body></html>
