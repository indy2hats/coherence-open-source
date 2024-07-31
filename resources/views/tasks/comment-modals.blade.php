<style>
    /* Todo :style fix */
   #comment-edit-modal .note-editor img{
            max-width: 800px!important;
            max-height: 800px!important;
    }
</style>
<div class="modal custom-modal edit-modal fade" id="view-comments-modal" tabindex="" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close edit-close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title text-center">Comments</h4>
			</div>
			<div class="modal-body" id="commentsWrapper">
			</div>
        </div>
    </div>
</div>
<div class="modal custom-modal edit-modal fade" id="comment-edit-modal" tabindex="" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                @method('PUT')
                @csrf
                <input type='hidden' name="comment_edit_mentions" id='comment-edit-mentions' value=''>
                <div class="modal-header">
                    <button type="button" class="close edit-close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title text-center">Edit Comment</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message">Update your message here:</label>
                        <textarea required class="form-control summernote" name="message" rows="5">Comment</textarea>
                        <div class="text-danger text-left field-error" id="label_message"></div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase edit-close" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-outline-success btn-primary text-uppercase edit-submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal custom-modal reply-modal fade" id="comment-reply-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                @csrf
                <input type='hidden' name="comment_reply_mentions" id='comment-reply-mentions'  value=''>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title text-center">Reply to Comment</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea required class="form-control summernote" placeholder="Write comment..." name="message" rows="5"></textarea>
                        <div class="text-danger text-left field-error" id="label_message"></div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-outline-success btn-primary text-uppercase reply-submit">Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal delete-modal fade" id="comment-delete-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="comment-delete-form" action="" method="POST">
                @method('DELETE')
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Do you really want to delete this comment?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="comment-body"></div>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-outline-danger btn-danger text-uppercase delete-submit">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var mentions = [
                    @foreach(App\Models\User::active()->get() as $user)
                        {id: '{{ $user->id }}', name: '{{ $user->first_name." ".$user->last_name }}'},
                    @endforeach
];
</script>
<style>
.note-popover.popover { z-index: 9999 !important; }
</style>