$(document).ready(function() {
    inputsLoader();

    $(document).keydown(function(e) {
        if (e.keyCode == 27) {
            $('#create_credential').modal('hide');
        }
    });

    $(document).on('keyup', '#type_id', function(event) {
        if (event.keyCode === 13) {
            $('.create-new').click();
        }
    });

    $('.create-new').click(function(e) {
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        var data = $('#create_credential_id').serialize();
        var projectId = $('#add_project_name').val();
        $('.field-error').html('');
        $.ajax({
            method: 'POST',
            url: $("#create_credential_id").attr('action'),
            data: data,
            success: function(response) {
                $(".overlay").remove();
                $('#create_credential').modal('hide');
                $("#table").html(response.data);
                toastr.success(response.message, 'Saved');
                inputsLoader();
                $('#select_project_name').val(projectId).change();
                credentialTable(projectId);
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    $('#create_credential').on('hidden.bs.modal', function() {
        $(this).find('.text-danger').html('');
        $('#create_credential_id').trigger('reset');
        $('.chosen-select').val('').trigger('chosen:updated');
        $('.summernote').summernote('reset');
    });

    $('#create_credential').on('show.bs.modal', function() {
        var target = $('.note-editing-area').get(0);

        var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
            $('.summernote').trigger('summernote.change');
            }
        });
        });

        var config = { childList: true, subtree: true };
        observer.observe($(target)[0], config);
    });



    $(document).on('click', '.delete-credential-data', function() {
        var deletefileId = $(this).data('id');
        $('#delete_credential #delete_credential_id').val(deletefileId);
    });

    $(document).on('click', '#delete_credential .continue-btn', function() {
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        var deletefileId = $('#delete_credential #delete_credential_id').val();
        $('#delete_credential').modal('hide');
        var projectId = $('#select_project_name').val();
        deleteUrl = '../../credentials/' + deletefileId;
        $.ajax({
            type: 'DELETE',
            url: deleteUrl,
            data: {},
            success: function(response) {
                $(".overlay").remove();
                toastr.success(response.message, 'Deleted');
                $("#table").html(response.data);
                inputsLoader();
                credentialTable(projectId);
            }
        });
    });

    $(document).on('click', '.edit-data', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        editUrl = '../../credentials/' + id + '/edit';
        $.ajax({
            method: 'GET',
            url: editUrl,
            data: {},
            success: function(response) {
                $('#edit_credential').html(response);
                $(".overlay").remove();
                $('#edit_credential').modal('show');
                $('.summernote').summernote();
            }
        });
    });

    $(document).on('keyup', '#edit_type_id', function(event) {
        if (event.keyCode === 13) {
            $('.update-data').click();
        }
    });

    $(document).on('click', '.update-data', function(e) {
        $('.field-error').html('');
        e.preventDefault();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        var projectId = $('#edit_project_name').val();
        $.ajax({
            type: 'PATCH',
            url: $('#edit_credential_id').attr('action'),
            data: $('#edit_credential_id').serialize(),
            success: function(response) {
                $(".overlay").remove();
                $('#edit_credential').modal('hide');
                toastr.success(response.message, 'Updated');
                inputsLoader();
                credentialTable(projectId);
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_edit_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('click', '.share-Data', function(e) {
        $('.field-error').html('');
        e.preventDefault();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
            type: 'POST',
            url: $('#share_credential_id').attr('action'),
            data: $('#share_credential_id').serialize(),
            success: function(response) {
                $(".overlay").remove();
                $('#share_credential').modal('hide');
                toastr.success(response.message, 'Updated');
                inputsLoader();
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_edit_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('change', '.select_project_name', function(e) {
        e.preventDefault();
        var id = $(this).val();
        $('#list_user_id').val('').trigger('chosen:updated');
        credentialTable(id);
    });

    $(document).on('change', '.select_user_id', function(e) {
        e.preventDefault();
        var id = $(this).val();
        $('#list_project_id').val('').trigger('chosen:updated');
        userCredentialTable(id);
    });

    $(document).on('click', '.share-data', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        shareUrl = '../../share-credentials/' + id;
        $.ajax({
            method: 'GET',
            url: shareUrl,
            data: {},
            success: function(response) {
                $('#edit_credential').html(response);
                $(".overlay").remove();
                $('#edit_credential').modal('show');
                $('.summernote').summernote();
                inputsLoader();
            }
        });
    });

    $(document).on('click', '.copyText', function(e) {
        var data = $(this).parent().find('span').html();
        toastr.success('Copied');
        inputsLoader();
        copyToClipboard(data)
    });

    $(document).on('click', '.copyPass', function(e) {
        var data = $(this).parent().find('input').val();
        toastr.success('Copied');
        inputsLoader();
        copyToClipboard(data)
    });
});

function inputsLoader() {
    $('.credentialsTable').dataTable();
    $('.usercredentialsTable').dataTable();
    $('.summernote').summernote();
    $('.chosen-select').chosen({
        width: "100%"
    });
}

function copyToClipboard(data) {
    var textArea = document.createElement("textarea");
    textArea.value = data;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
}

function credentialTable(id) {
    $.ajax({
        type: 'get',
        url: 'credentials/' + id,
        success: function(response) {
            $('.overlay').remove();
            $('.list').html(response.data);
            inputsLoader();
        }
    });
}

function userCredentialTable(id) {
    $.ajax({
        type: 'get',
        url: 'user-credentials/' + id,
        success: function(response) {
            $('.overlay').remove();
            $('.list').html(response.data);
            inputsLoader();
        }
    });
}