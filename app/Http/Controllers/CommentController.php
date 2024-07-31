<?php

namespace App\Http\Controllers;

use App\Events\UserTaskCommenReplyNotify;
use App\Events\UserTaskCommentNotify;
use App\Events\UserTaskMentionedNotify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravelista\Comments\Comment;
use Laravelista\Comments\CommentControllerInterface;
use Spatie\Honeypot\ProtectAgainstSpam;

date_default_timezone_set('Asia/Kolkata');

class CommentController extends Controller implements CommentControllerInterface
{
    public function __construct()
    {
        $this->middleware('web');

        if (Config::get('comments.guest_commenting') == true) {
            $this->middleware('auth')->except('store');
            $this->middleware(ProtectAgainstSpam::class)->only('store');
        } else {
            $this->middleware('auth');
        }
    }

    /**
     * Creates a new comment for given model.
     */
    public function store(Request $request)
    {
        // If guest commenting is turned off, authorize this action.
        if (Config::get('comments.guest_commenting') == false) {
            Gate::authorize('create-comment', Comment::class);
        }

        // Define guest rules if user is not logged in.
        if (! Auth::check()) {
            $guest_rules = [
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|string|email|max:255',
            ];
        }

        // Merge guest rules, if any, with normal validation rules.
        Validator::make($request->all(), array_merge($guest_rules ?? [], [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|string|min:1',
            'message' => 'required|string'
        ]))->validate();

        $model = $request->commentable_type::findOrFail($request->commentable_id);

        $commentClass = Config::get('comments.model');
        $comment = new $commentClass;

        if (! Auth::check()) {
            $comment->guest_name = $request->guest_name;
            $comment->guest_email = $request->guest_email;
        } else {
            $comment->commenter()->associate(Auth::user());
        }

        $comment->commentable()->associate($model);
        $comment->comment = $request->message;
        $comment->approved = ! Config::get('comments.approval_required');
        $comment->save();

        event(new UserTaskCommentNotify(Comment::find($comment->id)));
        if (request('comment_mentions')) {
            $mentions = ltrim(request('comment_mentions'), ',');
            $mentions = explode(',', $mentions);
            $users = User::whereIn('id', $mentions)->get();
            event(new UserTaskMentionedNotify(Comment::find($comment->id), $users));
        }
    }

    /**
     * Updates the message of the comment.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('edit-comment', $comment);

        Validator::make($request->all(), [
            'message' => 'required|string'
        ])->validate();

        $comment->update([
            'comment' => $request->message
        ]);

        if (request('comment_edit_mentions')) {
            $mentions = explode(',', request('comment_edit_mentions'));
            $users = User::whereIn('id', $mentions)->get();
            event(new UserTaskMentionedNotify(Comment::find($comment->id), $users));
        }
    }

    /**
     * Deletes a comment.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete-comment', $comment);

        if (Config::get('comments.soft_deletes') == true) {
            $comment->delete();
        } else {
            $comment->forceDelete();
        }
    }

    /**
     * Creates a reply "comment" to a comment.
     */
    public function reply(Request $request, Comment $comment)
    {
        Gate::authorize('reply-to-comment', $comment);

        Validator::make($request->all(), [
            'message' => 'required|string'
        ])->validate();

        $commentClass = Config::get('comments.model');
        $reply = new $commentClass;
        $reply->commenter()->associate(Auth::user());
        $reply->commentable()->associate($comment->commentable);
        $reply->parent()->associate($comment);
        $reply->comment = $request->message;
        $reply->approved = ! Config::get('comments.approval_required');
        $reply->save();

        event(new UserTaskCommenReplyNotify(Comment::find($reply->id)));

        if (request('comment_reply_mentions')) {
            $mentions = ltrim(request('comment_reply_mentions'), ',');
            $mentions = explode(',', $mentions);
            $users = User::whereIn('id', $mentions)->get();
            event(new UserTaskMentionedNotify(Comment::find($reply->id), $users));
        }
    }
}
