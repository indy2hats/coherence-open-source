jQuery(document).ready(function () {
    loadInputs();
    loadTaks();

    $(".chosen-select").on('change', function (e) {
        loadTaks();
    });

    $("#user_id").on('change', function (e) {
        e.preventDefault();
        $('select#client_id > option').hide();
        $('select#client_id > option[data-id="' + $(this).val() + '"]').show();
        $('#client_id').val('').trigger("chosen:updated");
        $('select#project_id > option').hide();
        $('select#project_id > option[data-user="' + $(this).val() + '"]').show();
        $('#project_id').val('').trigger("chosen:updated");
    });

    $("#client_id").on('change', function (e) {
        e.preventDefault();
        $("#company_head").removeClass('noExport');
        $('select#project_id > option').hide();
        $('select#project_id > option[data-id="' + $(this).val() + '"]').show();
        $('#project_id').val('').trigger("chosen:updated");

        if ($(this).val() != "") {
            $("#company_head").addClass('noExport');
        }
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
    
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $('.summernote').summernote({
        dialogsInBody: true,
        dialogsFade: false,
        callbacks:{
            onImageUpload: function(files, editor) {
                for(let i=0; i < files.length; i++) {
                    sendFile(files[i], $(this));
                }
            }
        }
    });
   
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
        success: function (response) {
            closeLoader();
            $("#task_content").html(response.data);
            initTable();
        }
    });
}

function initTable() {
    var groupColumn = 1;
    if($('.listData tbody tr').length > 1){
        $('.listData').DataTable({
            pageLength: -1,
            responsive: true,
            "lengthMenu": [
                [25, 50, -1],
                [25, 50, "All"]
            ],
            dom: '<"html5buttons"B>lTfgitp',
            "bInfo": false,
            "order": [
                [groupColumn, 'asc']
            ],
            "columnDefs": [{
                "visible": false,
                "targets": 1
            }],
            buttons: [{
                    extend: 'copy'
                },
                {
                    extend: 'csv'
                },
                {
                    extend: 'excel',
                    title: 'Timesheet Report'
                },
                {
                    extend: 'pdf',
                    title: 'Timesheet Report' + (($("#client_id").val() != "") ? " - " + $("#client_id option:selected").text() : ""),
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },

                {
                    extend: 'print',
                    customize: function (win) {
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
                            if (isNaN(a)) {
                                a = 0;
                            }

                            b = parseFloat(b, 10);
                            if (isNaN(b)) {
                                b = 0;
                            }

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
                            if (isNaN(a)) {
                                a = 0;
                            }

                            b = parseFloat(b, 10);
                            if (isNaN(b)) {
                                b = 0;
                            }

                            return a + b;
                        });

                    $(column.footer()).html('Total Billed: ' + parseFloat(sum).toFixed(2));
                });

                this.api().columns('.total-taken').every(function () {
                    var column = this;

                    var sum = column
                        .data()
                        .reduce(function (a, b) {
                            a = parseFloat(a, 10);
                            if (isNaN(a)) {
                                a = 0;
                            }

                            b = parseFloat(b, 10);
                            if (isNaN(b)) {
                                b = 0;
                            }

                            return a + b;
                        });

                    $(column.footer()).html('Total Spent: ' + parseFloat(sum).toFixed(2));
                });
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;
                // Total over this page
                sum = api
                    .column('.sum', {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function (a, b) {
                        a = parseFloat(a, 10);
                        if (isNaN(a)) {
                            a = 0;
                        }

                        b = parseFloat(b, 10);
                        if (isNaN(b)) {
                            b = 0;
                        }

                        return a + b;
                    });

                // Update footer
                $(api.column('.sum').footer()).html('Total Sum: ' + parseFloat(sum).toFixed(2));

                sum = api
                    .column('.total', {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function (a, b) {
                        a = parseFloat(a, 10);
                        if (isNaN(a)) {
                            a = 0;
                        }

                        b = parseFloat(b, 10);
                        if (isNaN(b)) {
                            b = 0;
                        }

                        return a + b;
                    });

                // Update footer
                $(api.column('.total').footer()).html('Total Billed: ' + parseFloat(sum).toFixed(2));

                sum = api
                    .column('.total-taken', {
                        filter: 'applied'
                    })
                    .data()
                    .reduce(function (a, b) {
                        a = parseFloat(a, 10);
                        if (isNaN(a)) {
                            a = 0;
                        }

                        b = parseFloat(b, 10);
                        if (isNaN(b)) {
                            b = 0;
                        }

                        return a + b;
                    });

                // Update footer
                $(api.column('.total-taken').footer()).html('Total Spent: ' + parseFloat(sum).toFixed(2));
            },
            "drawCallback": function (settings) {
                var salary = {};
                var api = this.api();
                var rows = api.rows({
                    filter: 'applied'
                }).nodes();
                var last = null;
                var groupId = -1;

                api.column(groupColumn, {
                    filter: 'applied'
                }).data().each(function (group, i) {
                    if (last !== group) {
                        groupId++;
                        $(rows).eq(i).before(
                            '<tr class="group"><th colspan="2">' + group + '</th><th class="groupSum ' + groupId + '" colspan="6"></th></tr>'
                        );
                        last = group;
                    }
                    if (typeof salary[groupId] == 'undefined') {
                        salary[groupId] = [];
                    }
                    var vals = api.row(api.row($(rows).eq(i)).index()).data();
                    salary[groupId].push(vals[3] ? parseFloat(vals[3]) : 0);
                });

                var i = 0;
                $.each(salary, function (index, value) {
                    var sum = value.reduce(function (a, b) {
                        a = parseFloat(a, 10);
                        if (isNaN(a)) {
                            a = 0;
                        }

                        b = parseFloat(b, 10);
                        if (isNaN(b)) {
                            b = 0;
                        }

                        return a + b;
                    });
                    $('.groupSum.' + index).html('Sum: ' + parseFloat(sum).toFixed(2));
                    i++;
                });

                if ($("#client_id").val() != "") {
                    $("#company_head").addClass('noExport');
                }
            }
        });
    }
}

$(document).ready(function () {

    $(document).on('click', '.view-comments', function (e) {
        e.preventDefault();
        var href = $(this).attr('data-href');
        openLoader();
        reload(href);
    });

    $(document).on('click', '#cmnt_submit', function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        $.ajax({
            type: 'POST',
            url: $('#save_comments').attr('action'),
            data: $('#save_comments').serialize(),
            success: function (response) {
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('click', '.delete-submit', function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        $.ajax({
            type: 'POST',
            url: $(this).parents('form').attr('action'),
            data: $(this).parents('form').serialize(),
            success: function (response) {
                $('#comment-delete-modal').modal('hide');
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('click', '.reply-submit', function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: $(this).parents('form').attr('action'),
            data: $(this).parents('form').serialize(),
            success: function (response) {
                $('#comment-reply-modal').modal('hide');
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $this.parents('form').find('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('click', '.edit-submit', function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: $(this).parents('form').attr('action'),
            data: $(this).parents('form').serialize(),
            success: function (response) {
                $('#comment-edit-modal').modal('hide');
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $this.parents('form').find('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('show.bs.modal', '#comment-edit-modal', function (e) {
        var formAction = $(e.relatedTarget).data('action');
        var commentHtml = $(e.relatedTarget).data('comment');

        $(e.target).find('form').attr('action', formAction);
        var summerNoteElem = $(e.target).find('.summernote');
        summerNoteElem.summernote({
            height: 200, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false, // set focus to editable area after initializing summernote
            hint: {
                mentions: mentions,
                match: /\B@(\w*)$/,
                search: function (keyword, callback) {
                callback($.grep(this.mentions, function (item) {
                        keyword = keyword.toLowerCase();  
                        return item.name.toLowerCase().indexOf(keyword) == 0;
                }));
                },
                template: function (item) {
                    return item.name;
                },
                content: function (item) {
                    var mentionedUsers = $('#comment-edit-mentions').val();
                        mentionedUsers = mentionedUsers.replace(/^,|,$/g,'');
                        $('#comment-edit-mentions').val(mentionedUsers+','+item.id);
                        return $('<span><span style="background: #ddd;padding: 5px;text-align: center;border-radius: 10px;">@'+item.name+'</span>&nbsp; &nbsp;</span>')[0];
                    
                }    
            }
        });
        summerNoteElem.summernote("code", commentHtml);
    });

    $(document).on('show.bs.modal', '#comment-reply-modal', function (e) {
        
        var formAction = $(e.relatedTarget).data('action');
        $(e.target).find('form').attr('action', formAction);
        var summerNoteElem = $(e.target).find('.summernote');
        summerNoteElem.summernote({
            height: 200, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false, // set focus to editable area after initializing summernote
            hint: {
                mentions: mentions,
                match: /\B@(\w*)$/,
                search: function (keyword, callback) {
                callback($.grep(this.mentions, function (item) {
                        keyword = keyword.toLowerCase();  
                        return item.name.toLowerCase().indexOf(keyword) == 0;
                }));
                },
                template: function (item) {
                    return item.name;
                },
                content: function (item) {
                    var mentionedUsers = $('#comment-reply-mentions').val();
                        mentionedUsers = mentionedUsers.replace(/^,|,$/g,'');
                        $('#comment-reply-mentions').val(mentionedUsers+','+item.id);
                        return $('<span><span style="background: #ddd;padding: 5px;text-align: center;border-radius: 10px;">@'+item.name+'</span>&nbsp; &nbsp;</span>')[0];
                    
                }    
            }
        });
        summerNoteElem.summernote('code','');
    });

    $(document).on('show.bs.modal', '#comment-delete-modal', function (e) {
        var formAction = $(e.relatedTarget).data('action');
        $(e.target).find('form').attr('action', formAction);
        var commentHtml = $(e.relatedTarget).data('comment');

        $(e.target).find('#comment-body').html(commentHtml);

    });

});
$(document).on('show.bs.modal', "#view-comments-modal", function (e) {
    var summerNoteElem = $(e.target).find('.summernote');
    summerNoteElem.summernote({
        height: 200, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: false, // set focus to editable area after initializing summernote
        hint: {
            mentions: mentions,
            match: /\B@(\w*)$/,
            search: function (keyword, callback) {
              callback($.grep(this.mentions, function (item) {
                    keyword = keyword.toLowerCase();  
                    return item.name.toLowerCase().indexOf(keyword) == 0;
              }));
            },
            template: function (item) {
                return item.name;
            },
            content: function (item) {
                var mentionedUsers = $('#comment_mentions').val();
                    mentionedUsers = mentionedUsers.replace(/^,|,$/g,'');
                    $('#comment_mentions').val(mentionedUsers+','+item.id);
                    return $('<span><span style="background: #ddd;padding: 5px;text-align: center;border-radius: 10px;">@'+item.name+'</span>&nbsp; &nbsp;</span>')[0];
                
            }    
        }
    });
})
$('.modal-dialog').draggable({
    handle: ".modal-header"
});
function reload(href) {
    $.ajax({
        type: 'GET',
        url: href,
        data: {},
        success: function (data) {
            $('#commentsWrapper').html(data.data);
            $('#view-comments-modal').modal('show');
            //$("#load_href").val(href);
            //inputsLoader();
            closeLoader();
        }
    });
}


$(document).on('click', '.create-modal' , function (e) {
    e.preventDefault();
    $('.datetimepicker').datepicker('setDate', new Date());
    $('#add_task').modal('show');
});

$(document).on('click', '.add-task', function(e) {
        toasterOption();
        e.preventDefault();
        $('.field-error').html('');
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );

        var data = new FormData($('#add_task_form')[0]);
        $.ajax({
            url: $('#add_task_form').attr('action'),
            data: data,
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $('#add_task').modal('hide');
                $(".overlay").remove();
                toastr.success(response.message, 'Created');
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.status == 422) {
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                  
                }
            }
        });
    });


 /**
     * Removing validation errors and reset form on model window close
     */
 $('#add_task').on('hidden.bs.modal', function() {
    $(this).find('.text-danger').html('');
    $('#add_task_form').trigger('reset');
    $('.summernote').summernote('reset');

    //}
});

$('#add_task').on('shown.bs.modal', function() {
    $('.summernote').summernote({
        tooltip: false,
        dialogsInBody: true,
        dialogsFade: false,
        callbacks: {
            onImageUpload: function(files, editor) {
                for (let i = 0; i < files.length; i++) {
                    sendFile(files[i], $(this));
                }
            }
        }
    });
});
