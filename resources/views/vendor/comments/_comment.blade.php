@inject('markdown', 'Parsedown')
@php($markdown->setSafeMode(true))

@if(isset($reply) && $reply === true)
<div id="comment-{{ $comment->getKey() }}" class="media">
    @else
    <li id="comment-{{ $comment->getKey() }}" class="media">
        @endif
        <img style="max-width:35px;" class="mr-3 img-circle" src="@if($comment->commenter->image_path){{ asset('storage/'.$comment->commenter->image_path) }}@else{{ asset('img/user.jpg') }}@endif" alt="{{ $comment->commenter->name ?? $comment->guest_name }} Avatar">
        <h5 class="mt-0 mb-1" style="display: inline-block;padding-left: 5px;">{{ $comment->commenter->full_name ?? $comment->guest_name }} <small class="text-muted">- {{ $comment->created_at->format('F d, Y')  }}</small></h5>
        <div class="media-body" style="padding-left:40px;">

            <div style="padding-left: 4px; font-size: 14px;">
                {!! $comment->comment !!}
            </div>

            <div>
                @can('reply-to-comment', $comment)
                <button data-toggle="modal" data-action="{{ route('comments.reply', $comment->getKey()) }}" data-target="#comment-reply-modal" class="btn btn-sm btn-link"> <i class="fa fa-mail-reply"></i> Reply</button>
                @endcan
                @can('edit-comment', $comment)
                <button data-toggle="modal" data-action="{{ route('comments.update', $comment->getKey()) }}" data-target="#comment-edit-modal" data-comment="{{ $comment->comment }}" class="btn btn-sm btn-link"><i class="fa fa-edit"></i> Edit</button>
                @endcan
                @can('delete-comment', $comment)
                <button data-toggle="modal" data-target="#comment-delete-modal" data-action="{{ route('comments.destroy', $comment->getKey()) }}" class="btn btn-sm btn-link text-danger" data-comment="{{ $comment->comment }}"><i class="ri-delete-bin-line"></i> Delete</button>
                @endcan
            </div>

            <br />{{-- Margin bottom --}}

            {{-- Recursion for children --}}
            @if($grouped_comments->has($comment->getKey()))
            @foreach($grouped_comments[$comment->getKey()] as $child)
            @include('comments::_comment', [
            'comment' => $child,
            'reply' => true,
            'grouped_comments' => $grouped_comments
            ])
            @endforeach
            @endif

        </div>
        @if(isset($reply) && $reply === true)
</div>
@else
</li>
@endif