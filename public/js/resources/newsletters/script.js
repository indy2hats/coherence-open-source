
	$(document).ready(function() {


		$(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#add_client').modal('hide');
                $('#edit_client').modal('hide');
                $('#delete_client').modal('hide');

            }
        });

		/** to save on enter key */
		$(document).on('keyup','#add_client_form', function (event) {
        if (event.keyCode === 13) {
          $('.create-client').click();
      }
	});

		$('#fromdate').datepicker({
            format: "mm-yyyy",
    		viewMode: "months", 
   			minViewMode: "months",
            autoclose: true
        });
		/** 
		 * Create client form - submit buttom action
		 */
		$(document).on('click', '.create-client', function(e) {
			$('.field-error').html('');
			e.preventDefault();
			openLoader();
			var data = new FormData($('#add_newsletter_form')[0]);
			$.ajax({
				type: 'POST',
				url: $('#add_newsletter_form').attr('action'),
				data: data,
				contentType: false,
				cache: false,
				processData: false,
				success: function(response) {
					$(".overlay").remove();
					$('#add_client').modal('hide');
					toastr.success(response.message, 'Saved');
					getNewsletterList('');
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
		 * Removing validation errors and reset form on model window close
		 */
		$('#add_client').on('hidden.bs.modal', function() {
			$(this).find('.text-danger').html('');
			$('#add_newsletter_form').trigger('reset');
		});

		/** 
		 * Loading edit client form with data to edit modal
		 */
		$(document).on('click', '.edit-client', function() {
			var clientId = $(this).data('id');
			editUrl = '/clients/' + clientId + '/edit';
			openLoader();
			$.ajax({
				method: 'GET',
				url: editUrl,
				data: {},
				success: function(response) {
					$(".overlay").remove();
					$('#edit_client').html(response);
					$('#edit_client').modal('show');
				}
			});
		});


		/** 
		 * Loading view client form with data to view modal
		 */

		$(document).on('click', '.view-client', function() {

			var clientId = $(this).data('id');
			showUrl = '/clients/' + clientId;
			$('#view_client').modal('show');
			$.ajax({
				method: 'GET',
				url: showUrl,
				data: {},
				success: function(response) {
					$('#view_client').html(response);
					$('#view_client').modal('show');
					
				}
			});
		});

		/** to update on enter key */
		$(document).on('keyup','#edit_client_form', function (event) {
        if (event.keyCode === 13) {
          $('.edit_client').click();
      }
	});

		/**
		 * Update client form - submit button action
		 */
		$(document).on('click', '.update-client', function(e) {
			$('.field-error').html('');
			e.preventDefault();
			$("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
			var data = new FormData($('#edit_client_form')[0]);
			$.ajax({
				type: 'POST',
				url: $('#edit_client_form').attr('action'),
				data: data,
				contentType: false,
				cache: false,
				processData: false,
				success: function(response) {
					$(".overlay").remove();
					$('#edit_client').modal('hide');
					toastr.success(response.message, 'Updated');
					getClientList('');
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

		/**
		 * Adding client id to hidden text field in delete model 
		 */
		$(document).on('click', '.delete-client', function() {
			var deleteClientId = $(this).data('id');
			$('#delete_client #delete_client_id').val(deleteClientId);
		});

		/**
		 * Delete model continue button action
		 */
		$(document).on('click', '#delete_client .continue-btn', function() {
			var deleteClientId = $('#delete_client #delete_client_id').val();
			$("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
			$('#delete_client').modal('hide');
			$.ajax({
				method: 'DELETE',
				url: '/clients/' + deleteClientId,
				data: {},
				success: function(response) {
					$(".overlay").remove();
					toastr.success(response.message, 'Deleted');
					getClientList('');
				},
				error: function(error) {
					$(".overlay").remove();
					toastr.warning("Delete the Projects before you delete company", 'Warning');
				}
			});
		});

		
	});

		/**
		 * Function to display client grid
		 */
		function getNewsletterList(company) {
			$.ajax({
				method: 'POST',
				url: '/get-newsletter-grid',
				data: {
				},
				success: function(response) {
					$('.grid-row').html(response.data);
					
				}
			});
		}

