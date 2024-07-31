
    jQuery(document).ready(function() {
        loadInputs();
        loadTaks();

        $(".search").on('click', function(e){
            e.preventDefault();
            loadTaks();
        });
    });
    function loadInputs() {


    $('.chosen-select').chosen({
      width: "100%"
    });

    $('input[name="daterange"]').daterangepicker({
          opens: 'left',
          locale: {
            format: 'DD/MM/YYYY'
          }
        }); 
    }

    function loadTaks() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/task-bounce',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#task_content").html(response.data);
                if($('.listData tbody tr').length > 1){
                    $(".listData").dataTable({
                            "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
                        });
                }
            }
        });
    }
