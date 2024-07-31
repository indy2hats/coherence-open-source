<div class="card">
    <div class="card-body">
        @if($errors->has('commentable_type'))
        <div class="alert alert-danger" role="alert">
            {{ $errors->first('commentable_type') }}
        </div>
        @endif
        @if($errors->has('commentable_id'))
        <div class="alert alert-danger" role="alert">
            {{ $errors->first('commentable_id') }}
        </div>
        @endif
        <form id="save_comments" method="POST" action="{{ route('comments.store') }}">
            @csrf
            @honeypot
            <input type="hidden" name="commentable_type" value="\{{ get_class($model) }}" />
            <input type="hidden" name="commentable_id" value="{{ $model->getKey() }}" />
            <input type="hidden" id="load_href" value="{{url('/tasks/view-comments/'.$task->id)}}" />
            <input type="hidden" name="comment_mentions" id="comment_mentions" value="" />
            {{-- Guest commenting --}}
            @if(isset($guest_commenting) and $guest_commenting == true)
            <div class="form-group">
                <label for="message">Enter your name here:</label>
                <input type="text" class="form-control @if($errors->has('guest_name')) is-invalid @endif"
                    name="guest_name" />
                @error('guest_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="message">Enter your email here:</label>
                <input type="email" class="form-control @if($errors->has('guest_email')) is-invalid @endif"
                    name="guest_email" />
                @error('guest_email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

            <div class="form-group">
                <textarea class="form-control summernote @if($errors->has('message')) is-invalid @endif"
                    placeholder="Write comment..." name="message" rows="3"></textarea>
                <div class="text-danger text-left field-error" id="label_message"></div>
            </div>
            <div class="text-right">
                <button id="cmnt_submit" type="submit"
                    class="btn btn-sm btn-primary m-t-n-xs"><strong>Post</strong></button>
            </div>
        </form>
    </div>
</div>
<br />