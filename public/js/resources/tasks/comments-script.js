
    $(document).ready(function() {
        $(document).on('click', '.view-comments', function(e) {
            e.preventDefault();
            var href = $(this).attr('data-href');
            openLoader();
            reload(href);
        });

        $(document).on('click', '#cmnt_submit', function(e) {
            e.preventDefault();
            openLoader();
            var href = $("#load_href").val();
            $.ajax({
                type: 'POST',
                url: $('#save_comments').attr('action'),
                data: $('#save_comments').serialize(),
                success: function(response) {
                    reload(href);
                },
                error: function(error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.delete-submit', function(e) {
            e.preventDefault();
            openLoader();
            var href = $("#load_href").val();
            $.ajax({
                type: 'POST',
                url: $(this).parents('form').attr('action'),
                data: $(this).parents('form').serialize(),
                success: function(response) {
                    $('#comment-delete-modal').modal('hide');
                    reload(href);
                },
                error: function(error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.reply-submit', function(e) {
            e.preventDefault();
            openLoader();
            var href = $("#load_href").val();
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: $(this).parents('form').attr('action'),
                data: $(this).parents('form').serialize(),
                success: function(response) {
                    $('#comment-reply-modal').modal('hide');
                    reload(href);
                },
                error: function(error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $this.parents('form').find('#label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.edit-submit', function(e) {
            e.preventDefault();
            openLoader();
            var href = $("#load_href").val();
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: $(this).parents('form').attr('action'),
                data: $(this).parents('form').serialize(),
                success: function(response) {
                    $('#comment-edit-modal').modal('hide');
                    reload(href);
                },
                error: function(error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $this.parents('form').find('#label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('show.bs.modal','#comment-edit-modal', function (e) {
            var formAction = $(e.relatedTarget).data('action');
            var commentHtml = $(e.relatedTarget).data('comment');

            $(e.target).find('form').attr('action', formAction);
            var summerNoteElem = $(e.target).find('.summernote');

            summerNoteElem.summernote("code", commentHtml);
        });

        $(document).on('show.bs.modal','#comment-reply-modal', function (e) {
            var formAction = $(e.relatedTarget).data('action');
            $(e.target).find('form').attr('action', formAction);
            //var summerNoteElem = $(e.target).find('.summernote');
            //summerNoteElem.summernote("code", "");
            /*summerNoteElem.summernote({
                height: 400,
                airMode: true
            });*/
        });

        $(document).on('show.bs.modal','#comment-delete-modal', function (e) {
            var formAction = $(e.relatedTarget).data('action');
            $(e.target).find('form').attr('action', formAction);
            var commentHtml = $(e.relatedTarget).data('comment');

            $(e.target).find('#comment-body').html(commentHtml);

        });

    });

    function reload(href) {
        $.ajax({
            type: 'GET',
            url: href,
            data: {},
            success: function(data) {
                $('#commentsWrapper').html(data.data);
                $("#load_href").val(href);
                inputsLoader();
                closeLoader();
            }
        });
    }

