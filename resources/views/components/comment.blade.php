<div class="media mt-4">
    <img class="mr-3 rounded-circle" alt="Bootstrap Media Preview" src="{{ asset('storage/avatar/default.jpg') }}" />
    <div class="media-body">
        <div class="row">
            <div class="col-12 d-flex">
                <h5>{{ $comment->user->name }} </h5>
                <span> &nbsp- {{ $comment->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <div class="text-describe">
            {!! $comment->content !!}
        </div>
        <div class="reply reply-box my-2">
            <div class="my-2 mb-2">
                <div class="reply mr-2 d-inline like-comment">
                    <a href="#!"><span><i class="fa fa-thumbs-up"></i>like</span></a>
                </div>
                <div class="reply mr-2 d-inline reply-comment" onclick="openComment({{ $comment->id }})"
                    data-id={{ $comment->id }}>
                    <a href="#!"><span><i class="fa fa-reply"></i> trả lời</span></a>
                </div>
                @if ($comment->user_id == Auth::user()?->id)
                    <div class="reply mr-2 d-inline delete-comment" data-id={{ $comment->id }}
                        onclick='deleteComment({{ $comment->id }})'>
                        <a href="#!"><span><i class="fa fa-trash"></i>&nbsp Xóa</span></a>
                    </div>
                @endif
            </div>
            <div class="reply-form d-none">
                <textarea name="reply" data-parent="{{ $comment->parent_comment_id ?: 0 }}" data-id="{{ $comment->id }}"
                    id='my-editor-{{ $comment->id }}' data-parent={{ $comment->parent_comment_id ?: 0 }}
                    class="form-control my-editor my-2 mx-2 "></textarea>
                <button class="btn btn-primary my-2 btn-comment" onclick="submitComment(this)">Gửi</button>
            </div>

        </div>

    </div>
</div>
@if ($comment->children)
    @foreach ($comment->children as $child)
        <x-comment :comment="$child" />
    @endforeach
@endif
