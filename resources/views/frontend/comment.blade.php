<div class="container my-5 bg-white">
    <div class="row">
        <div class="col-md-12 px-5 py-3" style="color:#ab201c">
            <div class="comment-title py-2">
                <h3> <i class="fa fa-comments"></i>&nbsp Bình luận ({{ count($comments) }})</h3>
            </div>
            <div class="reply-form">
                <textarea name="reply" id='my-editor-root' data-id="0" placeholder="hãy để lại bình luận của bạn"
                    class="form-control my-editor my-2 mx-2 "></textarea>
                <button class="btn btn-primary my-2 btn-comment d-none " onclick="submitComment(this)">Gửi</button>
            </div>
            @foreach ($comments as $comment)
                <div class="media mt-4">
                    <img class="mr-3 rounded-circle" alt="Bootstrap Media Preview"
                        src="{{ asset('storage/avatar/default.jpg') }}" />
                    <div class="media-body">
                        <div class="row">
                            <div class="col-12 d-flex">
                                <h5>{{ $comment->user->name }}</h5>
                                <span> &nbsp- {{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="text-describe">
                            {!! $comment->content !!}
                        </div>
                        <div class="reply reply-box my-2">
                            <div class="my-2 mb-2">
                                <div class="reply mr-2 d-inline like-comment">
                                    <a href="#!"><span><i class="fa fa-thumbs-up"></i>&nbsp like</span></a>
                                </div>
                                <div class="reply mr-2 d-inline reply-comment"
                                    onclick="openComment({{ $comment->id }})" data-id={{ $comment->id }}>
                                    <a href="#!"><span><i class="fa fa-reply"></i>&nbsp trả lời</span></a>
                                </div>
                                @if ($comment->user_id == Auth::user()?->id)
                                    <div class="reply mr-2 d-inline delete-comment" data-id={{ $comment->id }}
                                        onclick='deleteComment({{ $comment->id }})'>
                                        <a href="#!"><span><i class="fa fa-trash"></i>&nbsp Xóa</span></a>

                                    </div>
                                @endif

                            </div>
                            <div class="reply-form d-none">
                                <textarea name="reply" data-id="{{ $comment->id }}" data-parent="{{ $comment->parent_comment_id ?: 0 }}"
                                    id='my-editor-{{ $comment->id }}' class="form-control my-editor my-2 mx-2 "></textarea>
                                <button class="btn btn-primary my-2 btn-comment"
                                    onclick="submitComment(this)">Gửi</button>
                            </div>
                        </div>
                        @if ($comment->children?->isNotEmpty())
                            <x-comment :comment="$comment->children[0]" />
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@prepend('extraJs')
    <script src="https://cdn.tiny.cloud/1/81iawetr3yfpfrx8f1c50zi82yehktcwrj2t0vh9uf48umrd/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script type="text/javascript">
        var userCheck = "<?= Auth::user()->id ?? 0 ?>";

        function runTiny(id) {
            var editor_config = {
                path_absolute: "/",
                selector: '#' + id,
                relative_urls: false,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table directionality",
                    "emoticons template paste textpattern"
                ],
                // setup: function(editor) {
                //     editor.on('focus', function(e) {
                //         if (userCheck == 0) {
                //             return alert('hãy đăng nhập trước khi bình luận');
                //         }
                //     });
                //     editor.on('blur', function(e) {
                //         console.log("blur");
                //     });
                // },
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                file_picker_callback: function(callback, value, meta) {
                    var x = window.innerWidth || document.documentElement.clientWidth ||
                        document
                        .getElementsByTagName(
                            'body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight ||
                        document
                        .getElementsByTagName('body')[0].clientHeight;
                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' +
                        meta
                        .fieldname;
                    if (meta.filetype == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }
                    tinyMCE.activeEditor.windowManager.openUrl({
                        url: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no",
                        onMessage: (api, message) => {
                            callback(message.content);
                        }
                    });
                },
            };
            // console.log(parentElement.find('.my-editor').attr('id'));
            // Find and display the .my-editor textarea
            // parentElement.find('.my-editor').removeClass("d-none");
            tinymce.init(editor_config);
        }

        function openComment(id) {
            if (userCheck == 0) {
                return alert('hãy đăng nhập trước khi bình luận');
            } else {
                var textarea = `my-editor-${id}`;
                var parentElement = $(`#${textarea}`).closest('.reply-form');
                parentElement.removeClass('d-none');
                runTiny(textarea);
            }

        }

        function deleteComment(id) {
            if (userCheck == 0) {
                return alert('hãy đăng nhập trước khi bình luận');
            } else {
                if (confirm('Bạn có chắc muốn xóa comment này ?')) {
                    var url = "{{ route('fe.removeComment', ':id') }}";
                    url = url.replace(':id', id);
                    const removeElement = $(`.delete-comment[data-id="${id}"]`).closest('.media');
                    $.ajax({
                        type: "get",
                        url: url,
                        dataType: "text",
                        // data: {
                        //     id: id,
                        // },
                        success: function(response) {
                            if (response == 0) {
                                return alert('Đã có lỗi xảy ra');
                            }
                            if (response == 1) {
                                removeElement.remove();
                            }

                        }
                    });
                }

            }
        }
        $("#my-editor-root").focus(function() {
            if (userCheck == 0) {
                alert('hãy đăng nhập trước khi bình luận');
                $('#my-editor-root').blur()
            } else {
                var id = $(this).attr('id');
                var parentElement = $(this).closest('.reply-form');
                var btn = parentElement.find('.btn-comment');
                btn.removeClass('d-none');
                runTiny(id);
            }
        });

        function submitComment(that) {
            var parent = $(that).parent();
            var textarea = $(that).parent().find('.my-editor').attr('id');
            var content = tinymce.get(textarea).getContent();
            var parent_id = $(that).parent().find('.my-editor').data("id");
            var ancestor = $(that).parent().find('.my-editor').data("parent");
            var manga_id = $('#manga_id').val();
            $.ajax({
                url: "{{ route('fe.makeComment') }}",
                type: "post",
                dataType: "text",
                data: {
                    "_token": "{{ csrf_token() }}",
                    content: content,
                    parent_id: parent_id,
                    manga_id: manga_id,
                },
                success: function(result) {
                    var reply =
                        `<div class="media mt-4"><img class="mr-3 rounded-circle" alt="Bootstrap Media Preview" src="{{ asset('storage/avatar/default.jpg') }}" /><div class="media-body"><div class="row"><div class="col-12 d-flex"><h5>{{ Auth::user()?->name }} </h5><span> &nbsp- 1 phút trước </span></div></div><div class="text-describe">${content}</div><div class="reply reply-box my-2"><div class="my-2 mb-2"><div class="reply mr-2 d-inline like-comment"><a href="#!"><span><i class="fa fa-thumbs-up"></i>&nbsp like</span></a></div><div class="reply mr-2 d-inline reply-comment" onclick="openComment(${result})" data-id=${result}><a href="#!"><span><i class="fa fa-reply"></i>&nbsp trả lời</span></a></div><div class="reply mr-2 d-inline delete-comment" data-id=${result}
                        onclick='deleteComment(${result})'><a href="#!"><span><i class="fa fa-trash"></i>&nbsp Xóa</span></a></div></div><div class="reply-form d-none"><textarea name="reply" data-id="${result}" data-parent="${parent_id}" id='my-editor-${result}' class="form-control my-editor my-2 mx-2 "></textarea><button class="btn btn-primary my-2 btn-comment" onclick="submitComment(this)">Gửi</button></div></div></div></div>`;
                    if (parent_id == 0) {
                        parent.after(reply);
                    } else {
                        // parent.closest('.media-body').parent().parent().append(reply);
                        console.log(parent.closest('.media'));
                        // parent.closest('.media').parent().append(reply);
                        if (ancestor == 0) {
                            parent.closest('.media-body').append(reply);
                        } else {
                            // console.log(parent.closest('.media-body').parent().parent());
                            parent.closest('.media-body').parent().parent().append(reply);
                        }
                        parent.addClass('d-none');
                        // console.log(parent.closest('.media').closest('.media'));
                        // // parent.closest('.media').parent().append(reply);
                    }
                }
            });
            tinyMCE.get(textarea).setContent('');
        };
    </script>
@endprepend
