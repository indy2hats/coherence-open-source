jQuery(document).ready(function() {
    $('.chosen-select').chosen({
        width: "100%"
    });

    $( "#edit_reports_settings" ).on('submit',function( e ) {
    
        $('.field-error').html('');
        e.preventDefault();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        
        $.ajax({
            type: 'POST',
            url: $('#edit_reports_settings').attr('action'),
            data: new FormData($('#edit_reports_settings')[0]),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $(".overlay").remove();
                toastr.success(response.message, 'Saved');
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
});