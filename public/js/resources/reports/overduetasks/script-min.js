
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
    var overdueTaskTable = $('.overdueTaskTable').DataTable({
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

                ],  
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
                    updateTotalHours(api, 'estimated');
                    updateTotalHours(api, 'spend');
                    updateTotalRows(api);
                  
                },

});

}


function updateTotalHours(api, className) {
    var totalMinutes = api.column('.' + className, { filter: 'applied' })
        .data()
        .reduce(function (acc, value) {
            var hours = 0;
            var minutes = 0;
            var match = value.match(/^(\d+)h?\s*(\d+)?m?$/);
            if (match) {
                hours = parseInt(match[1]);
                minutes = match[2] ? parseInt(match[2]) : 0;
            }
            return acc + hours * 60 + minutes;
        }, 0);
    var hours = Math.floor(totalMinutes / 60);
    var minutes = totalMinutes % 60;

    $(api.column('.' + className).footer()).html(hours + 'h ' + minutes + 'm');
}

function updateTotalRows(api) {
    var rowCount = api. data(). rows({ filter: 'applied' }). count() ;
    $(api.column(0).footer()).html('Showing '+rowCount + ' entries');
}
