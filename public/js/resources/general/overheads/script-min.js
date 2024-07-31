
    $(document).keydown(function(e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $('#add_overhead').modal('hide');
            $('#edit_overhead').modal('hide');
            $('#delete_overhead').modal('hide');

        }
    });
    inputsLoader();
    load();
    loadPieExpense();
    listTable();
    listExpenseTable();

    function getTypes() {
        $.get('get-types',
            function(response) {
                var name = [];
                for (var i = response.data.length - 1; i >= 0; i--) {
                    name.push(response.data[i]['name'])
                }
                $(".typeahead_2").typeahead({
                    source: name
                });
            }, 'json');
    }
            
    function getExpenseTypes() {
        $.get('get-expense-types',
            function(response) {
                var name = [];
                for (var i = response.data.length - 1; i >= 0; i--) {
                    name.push(response.data[i]['name'])
                }
                $(".typeahead_expense").typeahead({
                    source: name,         
                    showHintOnFocus: true
                });
            }, 'json');
    }

    var pie;
    var pie_expense;

    $(document).on('change', '#date-chart', function() {
        load();
        loadPieExpense();
    });


    $(document).on('change', '#table-list', function() {
        listTable();
        listExpenseTable();
    });

    $(document).ready(function() {

        pie = Morris.Donut({
            element: 'morris-donut-chart',
            data: [10, 23, 78, 23],
            resize: true,
        });
        
        pie_expense = Morris.Donut({
            element: 'morris-donut-expense-chart',
            data: [10, 23, 78, 23],
            resize: true,
        });
        

        function getTypes() {
           $.get('get-types',
            function(response) {
                var name = [];
                for (var i = response.data.length - 1; i >= 0; i--) {
                    name.push(response.data[i]['name'])
                }
                $(".typeahead_2").typeahead({
                    source: name
                });
            }, 'json');
        }

        $(document).keydown(function(e) {
            // enter key pressed
            if (e.keyCode == 13) {
                $('.create-overhead').click();

            }
        });

        /** 
         * Create overhead form - submit buttom action
         */
        $('.create-overhead').click(function(e) {
            toasterOption();
            $('.field-error').html('');
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = new FormData($('#add_overhead_form')[0]);
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#add_overhead_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    $(".overlay").remove();
                    $('#add_overhead').modal('hide');
                    $('#add_overhead_form').trigger('reset');
                    $("#main").html(res.data);
                    toastr.success(res.message, res.status);
                    load();
                    listTable();
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
        $('#add_overhead').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_overhead_form').trigger('reset');
            $('.chosen-select').val('').trigger('chosen:updated');
        });

        /** 
         * Loading edit overhead form with data to edit modal
         */
        $(document).on('click', '.edit-overhead', function() {
            var overheadId = $(this).data('id');
            editUrl = '/overheads/' + overheadId + '/edit';
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                method: 'GET',
                url: editUrl,
                data: {},
                success: function(response) {
                    $(".overlay").remove();
                    $('#edit_overhead').html(response);
                    $('#edit_overhead').modal('show');
                    getTypes();
                    $('.summernote').summernote();
                }
            });
        });

        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 13) {
                $('.update-overhead').click();

            }
        });

        /**
         * Update overhead form - submit button action
         */
        $(document).on('click', '.update-overhead', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'POST',
                url: $('#edit_overhead_form').attr('action'),
                data: $('#edit_overhead_form').serialize(),
                success: function(res) {
                    $(".overlay").remove();
                    $('#edit_overhead').modal('hide');
                    $('#edit_overhead_form').trigger('reset');
                    $("#main").html(res.data);
                    toastr.success(res.message, res.status);
                    load();
                    listTable();
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#edit_label_' + field).html(error);
                        });
                    }
                }
            });
        });

        /**
         * Adding overhead id to hidden text field in delete model 
         */
        $(document).on('click', '.delete-overhead', function() {
            var deleteOverheadId = $(this).data('id');
            $('#delete_overhead #delete_overhead_id').val(deleteOverheadId);
        });

        /**
         * Delete model continue button action
         */
        $(document).on('click', '#delete_overhead .continue-btn', function() {
            toasterOption();
            var deleteOverheadId = $('#delete_overhead #delete_overhead_id').val();
            var deleteUrl = '/overheads/' + deleteOverheadId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_overhead').modal('hide');
            $.ajax({
                method: 'DELETE',
                url: deleteUrl,
                data: {
                    'date': document.getElementById('table-list').value
                },
                success: function(res) {
                    $(".overlay").remove();
                    $('#delete_overhead').trigger('reset');
                    $("#main").html(res.data);
                    toastr.success(res.message, res.status);
                    load();
                    listTable();
                }
            });
        });

          /**
         * Adding overhead id to hidden text field in delete model 
         */
           $(document).on('click', '.delete-expense', function() {
            var deleteOverheadId = $(this).data('id');
            $('#delete_expense #delete_expense_id').val(deleteOverheadId);
        });

        /**
         * Delete model continue button action
         */
        $(document).on('click', '#delete_expense .continue-btn', function() {
            toasterOption();
            var deleteExpenseId = $('#delete_expense #delete_expense_id').val();
            var deleteUrl = '/expenses/' + deleteExpenseId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_expense').modal('hide');
            $.ajax({
                method: 'DELETE',
                url: deleteUrl,
                data: {
                    'date': document.getElementById('table-list').value
                },
                success: function(res) {
                    $(".overlay").remove();
                    $('#delete_expense').trigger('reset');
                    $("#main").html(res.data);
                    toastr.success(res.message, res.status);
                    load();
                    loadPieExpense();
                    listExpenseTable();
                }
            });
        });

    });

    function load() {
        var setData1 = [];
        var setData2 = [];
        $.ajax({
            method: 'POST',
            url: '/load-chart',
            data: {
                'date': $('#date-chart').val()
            },
            success: function(response) {
                var j = 0;
                // for (var i = 1; j < response.data1.length; i++) {
                //     if (i == response.data1[j]['month']) {
                //         setData1.push(response.data1[j]['amount']);
                //         j++;
                //     } else {
                //         setData1.push(0);
                //     }
                // }
                setData1 = 0;
                total=0;
                for (var i = 0; i < response.data2.length; i++) {
                    setData2.push({
                        label: "" + response.data2[i]['type'],
                        value: response.data2[i]['amount']
                    });
                    total += parseInt(response.data2[i]['amount']);
                }
                $('#total_id').text('Total: Rs.' + total);
                pageLoad(setData1, setData2);
            }
        });
    }

    function pageLoad(setData1, setData2) {
        // var lineData = {
        //     labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        //     datasets: [{
        //         label: "Monthly Expences",
        //         backgroundColor: 'rgba(123,179,148,0.5)',
        //         borderColor: "rgba(12,179,148,0.7)",
        //         ointBackgroundColor: "rgba(12,179,148,1)",
        //         pointBorderColor: "#ff3",
        //         data: setData1
        //     }]
        // };
        // var lineOptions = {
        //     responsive: true
        // };
        // var ctx = document.getElementById("lineChart").getContext("2d");
        // new Chart(ctx, {
        //     type: 'line',
        //     data: lineData,
        //     options: lineOptions
        // });

        pie.setData(setData2);

    }

    function loadPieExpense(){
        $.ajax({
               method: 'POST',
               url: '/load-pie-expenses',
               data: {
                'date': $('#date-chart').val()
               },
               success: function(response) {
                    var data=[];
                     var total=0;
                       for (var i = 0; i < response.data.length; i++) {
                           data.push({
                               label: "" + response.data[i]['type'],
                               value: response.data[i]['amount']
                           });
                           total += parseInt(response.data[i]['amount']);
                       }
                   $('#total_expense_id').text('Total: Rs.' + total);
                    pie_expense.setData(data);
               }
           });
     }

    function listExpenseTable(){
        $.ajax({
            url: '/list-expense-table',
            data: {
                'date': document.getElementById('table-list').value
            },
            method: "POST",
            success: function(response) {
                $('.listExpense').html(response.data);
                $(".listExpenseTable").DataTable();
                $('#data_4 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy-mm",
                viewMode: "years",
                minViewMode: "months"
            });
            }
        });
    }

    /** 
    * Create expense form - submit buttom action
    */
    $('.create-expense').click(function(e) {
        toasterOption();
        $('.field-error').html('');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        var data = new FormData($('#add_expense_form')[0]);
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $('#add_expense_form').attr('action'),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
                $(".overlay").remove();
                $('#add_expense').modal('hide');
                $('#add_expense_form').trigger('reset');
                $("#main").html(res.data);
                toastr.success(res.message, res.status);
                load();
                loadPieExpense();
                listExpenseTable();
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#add_expense_form #label_' + field).html(error);
                    });
                }
            }
        });
    });

            /** 
         * Loading edit expense form with data to edit modal
         */
    $(document).on('click', '.edit-expense', function() {
        var expenseId = $(this).data('id');
        editUrl = '/expenses/' + expenseId + '/edit';
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
            method: 'GET',
            url: editUrl,
            data: {},
            success: function(response) {
                $(".overlay").remove();
                $('#edit_expense').html(response);
                $('#edit_expense').modal('show');
                getExpenseTypes();
                $('.summernote').summernote();
            }
        });
    });
    
     /**
         * Update expense form - submit button action
         */
      $(document).on('click', '.update-expense', function(e) {
        toasterOption();
        $('.field-error').html('');
        e.preventDefault();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
            type: 'POST',
            url: $('#edit_expense_form').attr('action'),
            data: $('#edit_expense_form').serialize(),
            success: function(res) {
                $(".overlay").remove();
                $('#edit_expense').modal('hide');
                $('#edit_expense_form').trigger('reset');
                $("#main").html(res.data);
                toastr.success(res.message, res.status);
                load();
                loadPieExpense();
                listExpenseTable();
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#edit_expense_form #edit_label_' + field).html(error);
                    });
                }
            }
        });
    });

    function listTable() {
        var table = $(".listTable tbody");
        $.ajax({
            url: '/list-table',
            data: {
                'date': document.getElementById('table-list').value
            },
            method: "POST",
            success: function(response) {
                $('.list').html(response.data);
                $('.listTable').dataTable();
                $('#data_4 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy-mm",
                viewMode: "years",
                minViewMode: "months"
            });
            }
        });
    }

  function inputsLoader() {
            $('.listExpenseTable').dataTable();

            $('.summernote').summernote();
            $('#data_3 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                defaultDate: new Date()
            });

            $('#data_3 .input-group.date').datepicker('setDate', new Date());

            $('#data_5 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                format: "dd/mm/yyyy",
                autoclose: true
            });

            $('#data_4 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy-mm",
                viewMode: "years",
                minViewMode: "months"
            });

            $('#data_4 .input-group.date').datepicker('setDate', new Date());
            getTypes();
            getExpenseTypes();
            $('.summernote').summernote();
        
    }
