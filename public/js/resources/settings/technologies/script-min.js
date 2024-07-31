
$(document).ready(function() {


    inputsLoader();

    $(document).on("show.bs.modal", '.modal', function (event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        $(this).css("z-index", zIndex);
        setTimeout(function () {
            $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1).addClass("modal-stack");
        }, 0);
    }).on("hidden.bs.modal", '.modal', function (event) {
        console.log("Global hidden.bs.modal fire");
        $(".modal:visible").length && $("body").addClass("modal-open");
    });
    $(document).on('inserted.bs.tooltip', function (event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var tooltipId = $(event.target).attr("aria-describedby");
        $("#" + tooltipId).css("z-index", zIndex);
    });
    $(document).on('inserted.bs.popover', function (event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var popoverId = $(event.target).attr("aria-describedby");
        $("#" + popoverId).css("z-index", zIndex);
    });

    $(document).keydown(function(e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $('#create_technology').modal('hide');
            $('#edit_asset_type').modal('hide');
            $('#delete_asset_type').modal('hide');
        }
    });


    /**
     * create_technology - submit button action
     */
    $('.create_technology').click(function(e) {
        toasterOption();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $('.field-error').html('');
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: $('#add_technology_form').attr('action'),
            data: $('#add_technology_form').serialize(),
            success: function(response) {
                $(".overlay").remove();
                $("#create_technology").modal('hide');
                toastr.success(response.message, 'Saved');
                setTimeout(window.location.reload(), 2000); 
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#add_technology_form #label_' + field).html(error);
                    });
                }
            }
        });
    });

    /**
     * Asset Type edit form loading when clicking edit
     */
    $(document).on('click', '.edit-technology', function(e) {
        e.preventDefault();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        var assetTypeId = $(this).data('id');
        var editUrl = '/project-technologies/' + assetTypeId + '/edit';

        $.ajax({
            type: 'GET',
            url: editUrl,
            data: {},
            success: function(data) {
                $('#edit_technology').html(data);
                inputsLoader();
                loadIchecks();
                $(".overlay").remove();
                $("#edit_technology").modal('show');
            }
        });

    });


    /**
     * Edit asset type form - submit button action
     */
    $(document).on('click', '.update-technology', function(e) {
        toasterOption();
        $('.field-error').html('');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $('#edit_technology_form').attr('action'),
            data: $('#edit_technology_form').serialize(),
            success: function(response) {
                $(".overlay").remove();
                if(response.status === "success"){
                    toastr.success(response.message, 'Updated');
                    $('#edit_technology').modal('hide');
                    $(".main").html(response.data);
                    $('.chosen-select').val('').trigger('chosen:updated'); 
                }else{
                    toastr.error(response.message, "Failed");
                }
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#edit_technology_form #label_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('click', '.delete-technology', function() {
        var deleteTechnology = $(this).data('id');
        $('#delete_technology #delete_technology_id').val(deleteTechnology);
    });

    /**
     * Delete asset - button click action
     */
    $(document).on('click', '#delete_technology .continue-btn', function() {
        var deleteTechnologyId = $('#delete_technology #delete_technology_id').val();
        deleteUrl = '/project-technologies/' + deleteTechnologyId;
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $('#delete_technology').modal('hide');
        $.ajax({
            type: 'DELETE',
            url: deleteUrl,
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.overlay').remove();
                if (response.status === "success") {
                    toastr.success(response.message, "Deleted");
                    setTimeout(loadTechnologies, 2000);
                } else {
                    toastr.error(response.message, "Failed");
                }
            },
            error: function(error) {
            }
        });
    });


    function loadTechnologies() {  
        window.location="/project-technologies"; 
    } 
    
});



function loadIchecks() {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
}

function inputsLoader() {
    $('.chosen-select').chosen({
        width: "100%"
    });
}








