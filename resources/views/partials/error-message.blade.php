@if ($message = Session::get('error'))
<div class="text-danger text-left">
	{{ $message }}
</div>
@endif