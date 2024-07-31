jQuery(document).ready(function() {
        loadInputs();
        loadTaks();

        $(".chosen-select").on('change', function(e){
            loadTaks();
        });

        $("#user_id").on('change', function(e){
            e.preventDefault();
            $('select#client_id > option').hide();
            $('select#client_id > option[data-id="'+$(this).val()+'"]').show();
            $('#client_id').val('').trigger("chosen:updated");
            $('select#project_id > option').hide();
            $('select#project_id > option[data-user="'+$(this).val()+'"]').show();
            $('#project_id').val('').trigger("chosen:updated");
        });

        $("#client_id").on('change', function(e){
            e.preventDefault();
            $('select#project_id > option').hide();
            $('select#project_id > option[data-id="'+$(this).val()+'"]').show();
            $('#project_id').val('').trigger("chosen:updated");
        });
    });
    function loadInputs() {


    $('.chosen-select').chosen({
      width: "100%"
    });

    $('#daterange').daterangepicker({
        opens: 'left',
          locale: {
            format: 'MMM DD, YYYY'
          },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
}
    function cb(start, end) {
        $('#daterange').val(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
        loadTaks();
    }

    function loadTaks() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/client-sheet-search',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#task_content").html(response.data);
                initTable();
            }
        });
    }

    function initTable()
    {
        var groupColumn = 1;
        if($('.listData tbody tr').length > 1){
            $('.listData').DataTable({
                    pageLength: -1,
                    responsive: true,
                    "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                    dom: '<"html5buttons"B>lTfgitp',
                    "bInfo" : false,
                    "order": [[ groupColumn, 'asc' ]],
                    "columnDefs": [
                        { "visible": false, "targets": 1 }
                    ],
                    buttons: [
                        { extend: 'copy'},
                        {extend: 'csv'},
                        {extend: 'excel', title: 'Timesheet Report'},
                        {extend: 'pdf', title: 'Timesheet Report'},

                        {extend: 'print',
                        customize: function (win){
                                $(win.document.body).addClass('white-bg');
                                $(win.document.body).css('font-size', '10px');

                                $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                        }
                        }
                    ],
                    "initComplete": function (settings, json) {
                        this.api().columns('.sum').every(function () {
                            var column = this;

                            var sum = column
                            .data()
                            .reduce(function (a, b) {
                                a = parseFloat(a, 10);
                                if(isNaN(a)){ a = 0; }

                                b = parseFloat(b, 10);
                                if(isNaN(b)){ b = 0; }

                                return a + b;
                            });

                            $(column.footer()).html('Total Sum: ' + parseFloat(sum).toFixed(2));
                        });

                        this.api().columns('.total').every(function () {
                            var column = this;

                            var sum = column
                            .data()
                            .reduce(function (a, b) {
                                a = parseFloat(a, 10);
                                if(isNaN(a)){ a = 0; }

                                b = parseFloat(b, 10);
                                if(isNaN(b)){ b = 0; }

                                return a + b;
                            });

                            $(column.footer()).html('Total Billed: ' + parseFloat(sum).toFixed(2));
                        });
                    },
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api(), data;
                        // Total over this page
                        sum = api
                                .column('.sum', {filter:'applied'})
                                .data()
                                .reduce(function (a, b) {
                                a = parseFloat(a, 10);
                                if(isNaN(a)){ a = 0; }

                                b = parseFloat(b, 10);
                                if(isNaN(b)){ b = 0; }

                                return a + b;
                            });

                        // Update footer
                        $(api.column('.sum').footer()).html('Total Sum: ' + parseFloat(sum).toFixed(2));

                        sum = api
                                .column('.total', {filter:'applied'})
                                .data()
                                .reduce(function (a, b) {
                                a = parseFloat(a, 10);
                                if(isNaN(a)){ a = 0; }

                                b = parseFloat(b, 10);
                                if(isNaN(b)){ b = 0; }

                                return a + b;
                            });

                        // Update footer
                        $(api.column('.total').footer()).html('Total Billed: ' + parseFloat(sum).toFixed(2));
                    },
                    "drawCallback": function ( settings ) {
                        var salary = {};
                        var api = this.api();
                        var rows = api.rows( {filter:'applied'} ).nodes();
                        var last=null;
                        var groupId = -1;

                        api.column(groupColumn, {filter:'applied'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                            groupId++;
                                $(rows).eq( i ).before(
                                    '<tr class="group"><th colspan="2">'+group+'</th><th class="groupSum '+groupId+'" colspan="5"></th></tr>'
                                );
                                last = group;
                            }
                            if (typeof salary[groupId] == 'undefined') {
                                salary[groupId] = [];
                            }
                            var vals = api.row(api.row($(rows).eq(i)).index()).data();
                            salary[groupId].push(vals[3] ? parseFloat(vals[3]) : 0);
                        } );

                        var i = 0;
                        $.each(salary, function(index, value){
                            var sum = value.reduce(function (a, b) {
                            a = parseFloat(a, 10);
                            if(isNaN(a)){ a = 0; }

                            b = parseFloat(b, 10);
                            if(isNaN(b)){ b = 0; }

                            return a + b;
                        });
                            $('.groupSum.'+index).html('Sum: '+parseFloat(sum).toFixed(2));
                            i++;
                        });
                    }
            });
        }
    }