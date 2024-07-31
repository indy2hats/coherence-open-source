jQuery(document).ready(function() {
    inputloader();
    $('.summernote').summernote({
    tooltip: false,
    dialogsInBody: true,
    dialogsFade: false,
    toolbar: [
        [ 'style', [ 'style' ] ],
        [ 'font', [ 'bold', 'italic', 'underline'] ],
        [ 'fontname', [ 'fontname' ] ],
        [ 'fontsize', [ 'fontsize' ] ],
        [ 'para', [ 'ol', 'ul', 'paragraph' ] ],
        [ 'insert', [ 'link'] ],
        [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
    ]
});

$( "#edit_company_info" ).on('submit',function( e ) {
    
    $('.field-error').html('');
    e.preventDefault();
    $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
    
    $.ajax({
        type: 'POST',
        url: $('#edit_company_info').attr('action'),
        data: new FormData($('#edit_company_info')[0]),
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
            $(".overlay").remove();
            toastr.success(response.message, 'Saved');
            setInterval(function() {location.reload()},500);
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

function inputloader() {
    $('#company_financial_year_from').datepicker({
         todayBtn: "linked",
         keyboardNavigation: false,
         forceParse: false,
         format: "dd/mm",
         changeYear: false,
         autoclose: true,
     });
     $('#company_financial_year_to').datepicker({
         keyboardNavigation: false,
         forceParse: false,
         format: "dd/mm",
         changeYear: false,
         autoclose: true
     });
 }