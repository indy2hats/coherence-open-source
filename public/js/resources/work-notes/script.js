
$(document).ready(function() {

    $('.add-work-note').click(function() {
        $.ajax({
            method: 'POST',
            url: '/work-notes',
            data:{},
            success: function(response) {
                $("#notes").html(response.data);
                toastr.success(response.message, 'Added');
            }, error: function(error) {
                // console.log(error);
            }
        })
    });


    $(document).on('change', '.note-content-text', function() {  
        var noteId = $(this).data('id');

        $.ajax({
            method: 'PATCH',
            url: '/work-notes/'+noteId,
            data:{
                content: $(this).val()
            },
            success: function(response) {
                $("#notes").html(response.data);
            }, error: function(error) {
                // console.log(error);
            }
        })
    });

    $(document).on('click', '.delete-note', function() {
        var noteId = $(this).data('id');

        $.ajax({
            method: 'DELETE',
            url: '/work-notes/'+noteId,
            data:{},
            success: function(response) {
                $("#notes").html(response.data);
                toastr.success(response.message, 'Deleted');
            }
        })
    });
});
