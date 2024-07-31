
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
                $('#create_asset_type').modal('hide');
                $('#edit_asset_type').modal('hide');
                $('#delete_asset_type').modal('hide');
            }
        });


        /**
         * Create ticket status - submit button action
         */
        $('.create-ticket-status').click(function(e) {
            //document.getElementById('create_asset').reset();
            toasterOption();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('.field-error').html('');
            e.preventDefault();

            $.ajax({
                method: 'POST',
                url: $('#add_ticket_status_form').attr('action'),
                data: $('#add_ticket_status_form').serialize(),
                success: function(response) {
                    $(".overlay").remove();
                    $("#create_ticket_status").modal('hide');
                    toastr.success(response.message, 'Saved');
                    setTimeout(loadTicketStatus, 2000);
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

        /**
         * Status edit form loading when clicking edit
         */
        $(document).on('click', '.edit-ticket-status', function(e) {
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var statusId = $(this).data('id');
            var editUrl = '/ticket-status/' + statusId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_ticket_status').html(data);
                    inputsLoader();
                    loadIchecks();
                    $(".overlay").remove();
                    $("#edit_ticket_status").modal('show');
                }
            });

        });


        /**
         * Edit ticket status form - submit button action
         */
        $(document).on('click', '.update-ticket-status', function(e) {
            toasterOption();
            $('.field-error').html('');
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#edit_ticket_status_form').attr('action'),
                data: $('#edit_ticket_status_form').serialize(),
                success: function(response) {
                    $(".overlay").remove();
                    toastr.success(response.message, 'Updated');
                    $('#edit_ticket_status').modal('hide');
                    $(".main").html(response.data);
                    $('.chosen-select').val('').trigger('chosen:updated'); 
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#edit_ticket_status_form #label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.delete-ticket-status', function() {
            var statusId = $(this).data('id');
            $('#delete_ticket_status #delete_status_id').val(statusId);
        });

        /**
         * Delete status - button click action
         */
        $(document).on('click', '#delete_ticket_status .continue-btn', function() {
            var statusId = $('#delete_ticket_status #delete_status_id').val();
            deleteUrl = '/ticket-status/' + statusId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_ticket_status').modal('hide');
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
                        setTimeout(loadTicketStatus, 2000);
                    } else {
                        toastr.error(response.message, "Failed");
                    }
                },
                error: function(error) {
                }
            });
        });


        function loadTicketStatus() {  
            window.location="/ticket-status"; 
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

    
    





