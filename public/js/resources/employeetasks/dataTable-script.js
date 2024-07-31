
    $('#complete-dt').dataTable({
        processing: true,
        serverSide: true,
        order: [[5, "desc"]],
        info: false,   
        lengthChange: false,
        ordering: true, 
        ajax: {
            "url": "/completed-tasks-list",
            "type": "POST",
        },
        lengthMenu: [
            [ 15, 50, 100],
            [ '15', '50', '100']
        ],
        columnDefs: [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": false
            },
            {
                'targets': [4],
                'orderable': false,
            }
        ],
        columns: [
            {
                data: "status",
                className: "task-status",
                render: function (data, type, row) {
                    return '<span class="label label-success">'+row.status+'<span>'; 
                }
            },
            {
                data: "code",
                name: "code",
                className: "project-code",
                render: function (data, type, row) {
                    return '<a href="/tasks/'+row.id+'">'+row.code+'</a>';
                }
            },
            {
                data: "title",
                name: "title",
                className: "project-title",
                render: function (data, type, row) {
                    return '<a href="/tasks/'+row.id+'">'+row.title+'</a> <br /><small>Created '+row.created_at_format+'</small>';
                }
            },
            {   
                data: "percent_complete",
                name: "percent_complete",
                className: "project-completion",
                render: function (data, type, row) {
                    var columnData = '<small>Completion with: '+row.percent_complete+'%</small>';
                    columnData+= '<div class="progress progress-mini"><div style="width: '+row.percent_complete+'%;" class="progress-bar"></div></div>';

                    return columnData;
                }
            },
            {
                data: "title",
                name: "title",
                className: "project-actions",
                render: function (data, type, row) {
                    return '<a href="/tasks/'+row.id+'"><i class="ri-eye-line"></i></a>';
                }
            },
            {
                data: "created_at",
                name: "created_at",   
            }
        ]
    });

   $('#upcoming-dt').dataTable({
        processing: true,
        serverSide: true,
        order: [[5, "desc"]],
        info: false,   
        lengthChange: false,
        ordering: true, 
        ajax: {
            "url": "/upcoming-tasks-list",
            "type": "POST",
        },
        lengthMenu: [
            [ 15, 50, 100],
            [ '15', '50', '100']
        ],
        columnDefs: [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": false
            },
            {
                'targets': [4],
                'orderable': false,
            }
        ],
        columns: [
            {
                data: "status",
                className: "task-status",
                render: function (data, type, row) {
                    return '<span class="label label-success">'+row.status+'<span>'; 
                }
            },
            {
                data: "code",
                name: "code",
                className: "project-code",
                render: function (data, type, row) {
                    return '<a href="/tasks/'+row.id+'">'+row.code+'</a>';
                }
            },
            {
                data: "title",
                name: "title",
                className: "project-title",
                render: function (data, type, row) {
                    return '<a href="/tasks/'+row.id+'">'+row.title+'</a> <br /><small>Created '+row.created_at_format+'</small>';
                }
            },
            {   
                data: "percent_complete",
                name: "percent_complete",
                className: "project-completion",
                render: function (data, type, row) {
                    var columnData = '<small>Completion with: '+row.percent_complete+'%</small>';
                    columnData+= '<div class="progress progress-mini"><div style="width: '+row.percent_complete+'%;" class="progress-bar"></div></div>';

                    return columnData;
                }
            },
            {
                data: "title",
                name: "title",
                className: "project-actions",
                render: function (data, type, row) {
                    return '<a href="/tasks/'+row.id+'"><i class="ri-eye-line"></i></a>';
                }
            },
            {
                data: "created_at",
                name: "created_at",   
            }
        ]
    });

    $('#ongoing-dt').dataTable({
    processing: true,
    serverSide: true,
    order: [
        [5, "desc"]
    ],
    info: false,
    lengthChange: false,
    ordering: true,
    ajax: {
        url: "/ongoing-tasks-list",
        type: "POST",
    },
    lengthMenu: [
        [15, 50, 100],
        ['15', '50', '100']
    ],
    columnDefs: [{
            "targets": [5],
            "visible": false,
            "searchable": false
        },
        {
            'targets': [4],
            'orderable': false,
        }
    ],
    columns: [{
            data: "status",
            className: "task-status",
            render: function(data, type, row) {
                var customClass = 'label ';
                if (row.status == 'In Progress') {
                    customClass += 'label-danger';
                }
                if (row.status == 'Development Completed') {
                    customClass += 'label-warning';
                }
                if (row.status == 'Under QA') {
                    customClass += 'info';
                }
                if (row.status == 'On Hold') {
                    customClass += 'success';
                }
                if (row.status == 'Awaiting Client') {
                    customClass += 'plain';
                }
                if (row.status == 'Client Review') {
                    customClass += 'warning';
                }

                return '<span class="' + customClass + '">' + row.status + '<span>';
            }
        },
        {
            data: "code",
            name: "code",
            className: "project-code",
            render: function(data, type, row) {
                return '<a href="/tasks/' + row.id + '">' + row.code + '</a>';
            }
        },
        {
            data: "title",
            name: "title",
            className: "project-title",
            render: function(data, type, row) {
                return '<a href="/tasks/' + row.id + '">' + row.title + '</a> <br /><small>Created ' +
                    row.created_at_format + '</small>';
            }
        },
        {
            data: "percent_complete",
            name: "percent_complete",
            className: "project-completion",
            render: function(data, type, row) {
                var columnData = '<small>Completion with: ' + row.percent_complete + '%</small>';
                columnData += '<div class="progress progress-mini"><div style="width: ' + row
                    .percent_complete + '%;" class="progress-bar"></div></div>';

                return columnData;
            }
        },
        {
            data: "title",
            name: "title",
            className: "project-actions",
            render: function(data, type, row) {
                return '<a href="/tasks/' + row.id + '"><i class="ri-eye-line"></i></a>';
            }
        },
        {
            data: "created_at",
            name: "created_at"
        }
    ]
});