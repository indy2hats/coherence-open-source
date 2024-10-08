
$('.clientTable').DataTable({
                paging:false,

                info:false,

                ordering:false,

                responsive: true,

                dom: '<"html5buttons"B>lTfgitp',

                buttons: [

                    { extend: 'copy'},

                    {extend: 'csv'},

                    {extend: 'excel', title: 'Hours Spent on Projects'},

                    {extend: 'pdf', title: 'Hours Spent on Projects'},



                    {extend: 'print',

                     customize: function (win){
                      $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div><h2>Hours Spent on Projects</h2></div>'
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


            $(document).ready(function () {
                loadInputs();
                loadPerformance();
                $(".search").on("click", function (e) {
                    e.preventDefault();
                    loadPerformance();
                });
            });
            function loadInputs() {
                var date = new Date();
                date.setDate(date.getDate() - 1);
                $('input[name="daterange"]').daterangepicker({
                    opens: "left",
                    locale: {
                        format: "DD/MM/YYYY",
                    },
                    maxDate: date,
                });
            }

            function loadPerformance() {
                openLoader();
                $.ajax({
                    method: "POST",
                    url: "/clientSearch",
                    data: $("#task-search-form").serialize(),
                    success: function (response) {
                        closeLoader();
                        $("#client_content").html(response.data);
                        initTable();
                    },
                });
            }

            function initTable() {
                $(".listData").DataTable({
                    pageLength: 25,
                    responsive: true,
                    lengthMenu: [
                        [25, 50, -1],
                        [25, 50, "All"],
                    ],
                    dom: '<"html5buttons"B>lTfgitp',
                    bInfo: false,
                    buttons: [
                        { extend: "copy" },
                        { extend: "csv" },
                        { extend: "excel", title: "Client Report" },
                        { extend: "pdf", title: "Client Report" },

                        {
                            extend: "print",
                            customize: function (win) {
                                $(win.document.body).addClass("white-bg");
                                $(win.document.body)
                                    .css("font-size", "10px")
                                    .prepend("<div><h2>Client</h2></div>");

                                $(win.document.body)
                                    .find("table")
                                    .addClass("compact")
                                    .css("font-size", "inherit");
                            },
                        },
                    ],
                });
            }
            

