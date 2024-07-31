
      //data tables
$(document).ready(function(){  
    $('.chosen-select').chosen({
      width: "100%"
    }); 
    loadTaks();

        $(".search").on('click', function(e){
            e.preventDefault();
            loadTaks();
        }); 
});

function loadTaks() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/overdue-tasks',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#overdue_content").html(response.data);
                initTable();
            }
        });
    }
function initTable()
{
    $('.overdueTaskTable').DataTable({
    paging:false,

                info:false,

                ordering:false,

                responsive: true,

                dom: '<"html5buttons"B>lTfgitp',

                buttons: [

                    { extend: 'copy'},

                    {extend: 'csv'},

                    {extend: 'excel', title: 'List of Overdue Tasks'},

                    {extend: 'pdf', title: 'List of Overdue Tasks'},



                    {extend: 'print',

                     customize: function (win){
                        $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<div><h2>List of Overdue Tasks</h2></div>'
                        );
                            $(win.document.body).addClass('white-bg');

                            $(win.document.body).css('font-size', '10px');



                            $(win.document.body).find('table')

                                    .addClass('compact')

                                    .css('font-size', 'inherit');

                    }

                    }

                ]

});
}
