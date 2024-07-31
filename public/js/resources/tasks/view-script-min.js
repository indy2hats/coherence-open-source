loadCurrentTaskSession();
checkExceedTime();
checkExistingSession();
loadInputs();

function loadInputs() {
    $(".chosen-select").chosen({
        width: "100%",
    });
    $(".datepicker").datepicker({
        startView: 1,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "M / yyyy",
        viewMode: "months",
        minViewMode: "months",
    });
    $(function () {
        $("#daterange").daterangepicker({
            opens: "left",
            locale: {
                format: "MMM DD, YYYY",
            },
            autoUpdateInput: false,
        });

        $("#daterange").on("apply.daterangepicker", function (ev, picker) {
            $(this).val(
                picker.startDate.format("MMM DD, YYYY") +
                    " - " +
                    picker.endDate.format("MMM DD, YYYY")
            );
        });

        $("#daterange").on("cancel.daterangepicker", function (ev, picker) {
            $(this).val("");
        });
    });
}

function loadCurrentTaskSession() {
    $.ajax({
        type: "GET",
        url: "/check-task-session",
        data: {
            "task-id": $.cookie("running_task"),
        },
        success: function (response) {
            if (response.flag == true) {
                if (
                    response.status == "started" ||
                    response.status == "resume"
                ) {
                    $("#pause-task").removeClass("hidden");
                    $("#stop-task #timer-button").text("STOP");
                    if (typeof $.cookie("running_task") === "undefined") {
                        $.cookie("running_task", response.id, {
                            expires: 1,
                            path: "/",
                        });
                        $.cookie("taskStartedBy", currentUser, {
                            expires: 1,
                            path: "/",
                        });
                        $.cookie("timerRunning", "true", {
                            expires: 1,
                            path: "/",
                        });
                        loadCurrentTaskSession();
                    }
                }
            }
        },
    });
}

var interval = 0;
$(".chosen-select").chosen({ width: "100%" });
var timerRunning = $.cookie("timerRunning");
var currentUser = 1;
var totalSeconds = 0;
var finish_state = false;
inputsLoader();
$(document).ready(function () {
    $(".rejectionTable").DataTable();
    loadIchecks();
    timeTaken();
    sessionTime();

    $(document).keydown(function (e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            // $('#create_task').modal('hide');
            $("#edit_task").modal("hide");
            $("#delete_task").modal("hide");
            $("#destroy_task").modal("hide");
            $("#show-project-credentials").modal("hide");
            $("#show-project-files").modal("hide");
            $("#show-branches").modal("hide");
        }
    });

    $("body").on("click", ".checklist-link", function (e) {
        e.preventDefault();
        var $this = $(this);
        openLoader();
        $.ajax({
            type: "POST",
            url: "/update-task-checklist",
            data: {
                id: $this.data("id"),
                status: $this.data("status"),
                type: $this.data("type"),
            },
            success: function (response) {
                closeLoader();
                var button = $this.find("i");
                var label = $this.next("span");
                button
                    .toggleClass("fa-check-square")
                    .toggleClass("fa-square-o");
                label.toggleClass("todo-completed");
                toastr.success(response.message, "Updated");
                $this.attr("data-status", response.status);
            },
        });
    });

    $(document)
        .on("show.bs.modal", ".modal", function (event) {
            var zIndex = 100000 + 10 * $(".modal:visible").length;
            $(this).css("z-index", zIndex);
            setTimeout(function () {
                $(".modal-backdrop")
                    .not(".modal-stack")
                    .first()
                    .css("z-index", zIndex - 1)
                    .addClass("modal-stack");
            }, 0);
        })
        .on("hidden.bs.modal", ".modal", function (event) {
            $(".modal:visible").length && $("body").addClass("modal-open");
        });

    $("#create_sub_task").on("shown.bs.modal", function () {
        $(".subtask_summernote").summernote({
            tooltip: false,
            dialogsInBody: true,
            dialogsFade: false,
            callbacks: {
                onImageUpload: function (files, editor) {
                    for (let i = 0; i < files.length; i++) {
                        sendFile(files[i], $(this));
                    }
                },
            },
        });
    });

    $(document).on("inserted.bs.tooltip", function (event) {
        var zIndex = 100000 + 10 * $(".modal:visible").length;
        var tooltipId = $(event.target).attr("aria-describedby");
        $("#" + tooltipId).css("z-index", zIndex);
    });
    $(document).on("inserted.bs.popover", function (event) {
        var zIndex = 100000 + 10 * $(".modal:visible").length;
        var popoverId = $(event.target).attr("aria-describedby");
        $("#" + popoverId).css("z-index", zIndex);
    });

    $(document).on("click", ".reply-submit", function () {
        $("body").removeClass("modal-open");
        $("body").css("padding-right", "");
    });

    $(document).on("click", ".edit-submit", function () {
        $("body").removeClass("modal-open");
        $("body").css("padding-right", "");
    });

    updateSession();

    $("body").on("focus", "#data_1 .input-group.date", function () {
        $(this).datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: "dd/mm/yyyy",
            endDate: "today",
        });
    });

    $("body").on(
        "keydown",
        "input[id*='add-session-box-time'], input[id*='edit-session-box-time']",
        function (event) {
            if (event.shiftKey == true && event.keyCode == 186) {
            } else if (event.shiftKey == true) {
                event.preventDefault();
            }

            if (
                (event.keyCode >= 48 && event.keyCode <= 57) ||
                (event.keyCode >= 96 && event.keyCode <= 105) ||
                event.keyCode == 8 ||
                event.keyCode == 9 ||
                event.keyCode == 37 ||
                event.keyCode == 39 ||
                event.keyCode == 46 ||
                event.keyCode == 190 ||
                event.keyCode == 110 ||
                event.keyCode == 186
            ) {
            } else {
                event.preventDefault();
            }

            if ($(this).val().indexOf(".") !== -1 && event.keyCode == 190) {
                event.preventDefault();
            }

            if ($(this).val().indexOf(":") !== -1 && event.keyCode == 186) {
                event.preventDefault();
            }
        }
    );

    $(".js-range-slider").ionRangeSlider({
        onFinish: function (data) {
            var value = data.fromNumber;
            toasterOption();

            $.ajax({
                type: "POST",
                url: "/update-progress",
                data: {
                    taskId: $("#task-id").attr("data-id"),
                    progress: value,
                },
                success: function (response) {
                    $("#completed").html(value);
                    toastr.success(response.message, "Updated");
                },
            });
        },
    });

    $(document).on("click", "#accept_button", function () {
        var row_id = $(this).attr("data-row-id");
        $.ajax({
            method: "POST",
            url: "/check-if-session-is-stopped",
            data: {
                taskId: $("#task-id").attr("data-id"),
            },
            success: function (response) {
                if (response.flag == true) {
                    checkWhetherExceedsTimeWithReason(row_id);
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Please stop the task before accepting !",
                    });
                }
            },
        });
    });

    $(document).on("change", "#userSession, #userSessionType", function () {
        loadData();
    });

    $(document).on("click", ".applyBtn , .cancelBtn", function () {
        loadData();
    });

    function loadData(page = 1) {
        openLoader();
        $.ajax({
            method: "POST",
            url: "/get-user-session",
            data: {
                userId: $("#userSession").val(),
                type: $("#userSessionType").val(),
                taskId: $("#task-id").attr("data-id"),
                daterange: $("#daterange").val(),
                page: page,
            },
            success: function (response) {
                closeLoader();
                $(".task-session").html(response.data);
                $(".chosen-select").chosen();
                loadInputs();
            },
        });
    }

    // Handle pagination click event
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        var page = $(this).attr("href").split("page=")[1];
        loadData(page);
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });

    $(document).on("click", "#reject-button", function () {
        var rejectId = $(this).data("id");
        $.ajax({
            method: "POST",
            url: "/check-if-session-is-stopped",
            data: {
                taskId: $("#task-id").attr("data-id"),
            },
            success: function (responseData) {
                if (responseData.flag == true) {
                    $("#reject_task_modal #reject_id").val(rejectId);
                    $.ajax({
                        method: "POST",
                        url: "/check-whether-exceeds-time-with-reason",
                        data: {
                            id: rejectId,
                        },
                        success: function (response) {
                            if (response.flag == true) {
                                $("#task_rejection_form").append(
                                    '<div class="row"><div class="col-md-12"><div class="form-group"><label>Reason entered by the employee </label><textarea rows="4" class="form-control summernote" placeholder="" name="exceed_reason" id="comments">' +
                                        response.exceed_reason +
                                        "</textarea></div></div></div>"
                                );
                                $(".summernote").summernote();
                                $("#reject_task_modal").modal("show");
                            } else {
                                $("#reject_task_modal").modal("show");
                            }
                        },
                    });
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Please stop the task before rejecting !",
                    });
                }
            },
        });
    });

    $(document).on("click", "#qa-reject", function () {
        var rejectId = $(this).data("id");
        $("#reject_qa_task_modal #reject_id").val(rejectId);
        $("#reject_qa_task_modal").modal("show");
    });


    $(document).on("click", ".delete-task", function () {
        var deleteTaskId = $(this).data("id");
        var deleteTaskType = $(this).data("type");
        $("#delete_task #delete_task_id").val(deleteTaskId);
        $("#delete_task #delete_task_type").val(deleteTaskType);
    });

    $(document).on("click", ".admin_approve", function () {
        var taskId = $(this).data("id");
        $("#admin_approve_modal #approve_task_id").val(taskId);
    });

    /** Task Rejection - create modal */
    $(document).on("click", "#reject_task_modal .continue-btn", function (e) {
        e.preventDefault();
        openLoader();
        $.ajax({
            type: "POST",
            url: $("#task_rejection_form").attr("action"),
            data: $("#task_rejection_form").serialize(),
            success: function (response) {
                $("#reject_task_modal").modal("hide");
                closeLoader();
                toastr.success(response.message, "Rejected");
                removeOverlay();
                reloadPage();
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_" + field).html(error);
                    });
                }
            },
        });
    });

     $(document).on("click", "#reject_qa_task_modal .continue-reject", function (e) {
        e.preventDefault();
        openLoader();
        $(".field-error").html("");
        $.ajax({
            type: "POST",
            url: $("#task_qa_rejection_form").attr("action"),
            data: $("#task_qa_rejection_form").serialize(),
            success: function (response) {
                $("#reject_qa_task_modal").modal("hide");
                closeLoader();
                toastr.success(response.message, "Rejected");
                removeOverlay();
                reloadPage('#rejection-list');
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {     
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_qa_" + field).html(error);
                    });
                }
            },
        });
    });

    $(document).on("click", ".delete-doc", function (e) {
        e.preventDefault();
        var $this = $(this);
        var deleteUrl = $(this).attr("href");
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $.ajax({
            type: "GET",
            url: deleteUrl,
            data: {},
            success: function (response) {
                closeLoader();
                $this.parents(".doc-repeat").remove();
            },
            error: function (error) {},
        });
    });

    /**
     * Delete task form - continue button action
     */
    $(document).on("click", "#delete_task .continue-btn", function () {
        toasterOption();
        var deleteTaskId = $("#delete_task #delete_task_id").val();
        openLoader();
        var deleteTaskType = $("#delete_task #delete_task_type").val();
        $("#delete_task").modal("hide");
        $.ajax({
            type: "POST",
            url: "/delete-task-ajax",
            data: {
                taskId: deleteTaskId,
                projectId: $("#project_id").attr("data-id"),
            },
            success: function (response) {
                closeLoader();
                toastr.success(response.message, "Archived");
                if (deleteTaskType == "parent") {
                    window.location.href = "/tasks";
                } else {
                    $(".overlay").remove();
                    // reloadPage();
                    setTimeout(reloadCurrentPage, 1000);
                    loadSubTask();
                }
            },
            error: function (error) {
                closeLoader();
            },
        });
    });

    $(document).on("click", "#admin_approve_modal .continue-btn", function () {
        toasterOption();
        var taskId = $("#admin_approve_modal #approve_task_id").val();
        openLoader();
        $("#admin_approve_modal").modal("hide");
        $.ajax({
            type: "POST",
            url: "/admin-task-approve",
            data: {
                taskId: taskId,
            },
            success: function (response) {
                closeLoader();
                reloadPage();
            },
            error: function (error) {
                closeLoader();
            },
        });
    });

    $(document).on("click", ".create-session", function (e) {
        e.preventDefault();
        createSession();
    });

    $(document).on("click", ".edit-task-session", function () {
        var sessionId = $(this).data("id");
        var editUrl = "/task-session/" + sessionId + "/edit";
        openLoader();
        $.ajax({
            type: "GET",
            url: editUrl,
            data: {},
            success: function (data) {
                $("#edit_task_session").html(data);
                inputsLoader();
                closeLoader();
                $("#edit_task_session").modal("show");
            },
        });
    });

    $(document).on("click", ".edit-session", function (e) {
        e.preventDefault();
        editSession();
    });

    $(document).on("#add-session shown.bs.modal", function () {
        $("#add-session-box-time").trigger("focus");
    });
    /**
     * Removing validation errors and reset form on model window close
     */
    $("#add-session").on("hidden.bs.modal", function () {
        $(this).find(".text-danger").html("");
        $("#create_session_form").trigger("reset");
    });

    $(document).on("keyup", "#add-session-box-time", function (event) {
        if (event.keyCode === 13) {
            createSession();
        }
    });

    /**
     * Adding client id to hidden text field in delete model
     */
    $(document).on("click", "#delete-session", function () {
        var deleteSessionId = $(this).data("id");
        $("#delete_task_session #delete_session_id").val(deleteSessionId);
    });

    /**
     * Delete model continue button action
     */
    $(document).on("click", "#delete_task_session .continue-btn", function () {
        var deleteSessionId = $(
            "#delete_task_session #delete_session_id"
        ).val();
        openLoader();
        $.ajax({
            method: "DELETE",
            url: "../task-session/" + deleteSessionId,
            data: {},
            success: function (response) {
                closeLoader();
                $("#delete_task_session").modal("hide");
                toastr.success(response.message, "Deleted");
                reloadPage("#sessions");
            },
        });
    });

    /**
     * Task edit form loading when clicking edit
     */
    $(document).on("click", ".edit-task", function (e) {
        e.preventDefault();
        openLoader();
        var taskId = $(this).data("id");
        var editUrl = "../tasks/" + taskId + "/edit";
        var reloadVar = $(this).data("parent");
        $.ajax({
            type: "GET",
            url: editUrl,
            data: {},
            success: function (data) {
                $("#edit_task").html(data);
                inputsLoader();
                // typeAhead();
                loadIchecks();
                $(".summernote").summernote();
                closeLoader();
                $("#edit_task").modal("show");
                $(".update-task").attr("data-reload", reloadVar);
            },
        });
    });

    $(document).on("keyup", "#edit-session-box-time", function (event) {
        if (event.keyCode === 13) {
            editSession();
        }
    });

    $(document).on("click", "#edit-session-box-time", function () {
        $("#edit-session-box-time").val("");
    });

    /** to update on enter key */
    $(document).on("keyup", "#edit_task_title", function (event) {
        if (event.keyCode === 13) {
            $(".edit-task").click();
        }
    });

    $(document).on("keyup", "#edit_estimated_time", function (event) {
        if (event.keyCode === 13) {
            $(".edit-task").click();
        }
    });

    $(document).on("keyup", "#edit_url", function (event) {
        if (event.keyCode === 13) {
            $(".edit-task").click();
        }
    });

    $(document).on("keyup", "#percent_complete", function (event) {
        if (event.keyCode === 13) {
            $(".edit-task").click();
        }
    });

    $(function () {
        $("#timepicker").datetimepicker({
            format: "HH:mm",
        });
    });

    if ($("#percent_complete").val() == "100") {
        $("#start").prop("disabled", true);
    }
    /**
     * Edit task form - submit button action
     */
    $(document).on("click", ".update-task", function (e) {
        toasterOption();
        $(".field-error").html("");
        e.preventDefault();
        openLoader();
        var data = new FormData($("#edit_task_form")[0]);
        $.ajax({
            type: "POST",
            url: $("#edit_task_form").attr("action"),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                closeLoader();
                $("#edit_task").modal("hide");
                toastr.success(response.message, "Updated");
                // reloadPage();
                loadSubTask();
                loadAssignees();
                if ($(".update-task").attr("data-reload") == "true") {
                    setInterval(function () {
                        location.reload();
                    }, 500);
                }
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_" + field).html(error);
                    });
                }
            },
        });
    });
});

function reloadPage(activeTab) {
    if (typeof interval != "undefined") {
        clearInterval(interval);
    }
    $.ajax({
        type: "POST",
        url: "/update-task-detail",
        data: {
            task_id: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            $(".main").html(response.data);
            $(".js-range-slider").ionRangeSlider({
                onFinish: function (data) {
                    var value = data.fromNumber;
                    toasterOption();

                    $.ajax({
                        type: "POST",
                        url: "/update-progress",
                        data: {
                            taskId: $("#task-id").attr("data-id"),
                            progress: value,
                        },
                        success: function (response) {
                            $("#completed").html(value);
                            toastr.success(response.message, "Updated");
                        },
                    });
                },
            });
            inputsLoader();
            updateSession();
            loadIchecks();
            timeTaken();
            sessionTime();
            checkSession();
            $(".rejectionTable").DataTable();
            if (activeTab) {
                $('a[href="' + activeTab + '"]').tab("show");
            }
        },
    });
}

function changeTaskSessionButton(flag) {
    if (flag == 0) {
        if ($("#task-id-timer").val() == $("#stop-task").data("task-id")) {
            $("#stop-task").attr("id", "start");
            $("#start").find("timer-button").text("START");
        }
    } else {
        if ($("#task-id-timer").val() == $("#start").data("task-id")) {
            $("#start").attr("id", "stop-task");
            $("#stop-task").find("timer-button").text("STOP");
        }
    }
}

function typeAhead() {
    $.get(
        "get-typhead-data-project",
        function (response) {
            var name = [],
                id = [];
            for (var i = response.data.length - 1; i >= 0; i--) {
                name.push(response.data[i]["project_name"]);
                id.push(response.data[i]["project_id"]);
            }
            $(".typeahead_name").typeahead({
                source: name,
            });
            $(".typeahead_id").typeahead({
                source: id,
            });
        },
        "json"
    );
}
function inputsLoader() {
    $(".chosen-select").chosen({
        width: "100%",
    });
    $(".datetimepicker").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
    });
    $(".summernote").summernote({
        tooltip: false,
        dialogsInBody: true,
        dialogsFade: false,
        callbacks: {
            onImageUpload: function (files, editor) {
                for (let i = 0; i < files.length; i++) {
                    sendFile(files[i], $(this));
                }
            },
        },
    });
    $("#data_1 .input-group.date").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd/mm/yyyy",
        endDate: "today",
    });
    $(".clockpicker").clockpicker();
    $(function () {
        $("#daterange").daterangepicker({
            opens: "left",
            locale: {
                format: "MMM DD, YYYY",
            },
            autoUpdateInput: false,
        });

        $("#daterange").on("apply.daterangepicker", function (ev, picker) {
            $(this).val(
                picker.startDate.format("MMM DD, YYYY") +
                    " - " +
                    picker.endDate.format("MMM DD, YYYY")
            );
        });

        $("#daterange").on("cancel.daterangepicker", function (ev, picker) {
            $(this).val("");
        });
    });
}

function finishState() {
    var flag = checkWhetherExceedsTime();
}

function changeStatusFinish() {
    if ($("#finish_task_status").val()) {
        var status = $("#finish_task_status").val();
    } else {
        var status = $("#task_status").val();
    }
    $.ajax({
        method: "POST",
        url: "/change-status-finish",
        data: {
            task_id: $("#task-id").attr("data-id"),
            status: status,
        },
        success: function (response) {
            reloadPage();
            toastr.success(response.message, "Updated");
            finish_state = false;
        },
    });
}

function getSessionList() {
    $.ajax({
        type: "POST",
        url: "/get-tasks-session",
        data: {
            taskId: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            $(".main").html(response.data);
            inputsLoader();
        },
    });
}

function getDocuments() {
    $.ajax({
        type: "POST",
        url: "/get-documents",
        data: {
            task_id: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            $(".documents-div").html(response.data);
        },
    });
}

function checkExceedTime() {
    $.ajax({
        type: "POST",
        url: "/check-exceed-time",
        data: {
            task_id: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            if (response.flag == true) {
                Swal.fire({
                    icon: "warning",
                    title: "You have passed the estimate. Have you talked to your manager?",
                });
            }
        },
    });
}

function checkExistingSession() {
    $.ajax({
        type: "POST",
        url: "/check-existing-session",
        data: {},
        success: function (response) {
            if (response.flag) {
                $("#start").prop("disabled", true);
                Swal.fire({
                    icon: "warning",
                    title:
                        "There exists a session which is not properly stopped. Please update the session manualy.<br><br>Task Title: " +
                        response.title +
                        "<br>Date: " +
                        response.date,
                    footer:
                        '<a href="/tasks/' +
                        response.task_id +
                        '">Go To Task: ' +
                        response.title +
                        "</a>",
                });
            }
        },
    });
}

function createSession() {
    checkExistingSession();
    $(".field-error").html("");
    openLoader();
    $.ajax({
        type: "POST",
        url: $("#create_session_form").attr("action"),
        data: $("#create_session_form").serialize(),
        success: function (response) {
            closeLoader();
            if (response.success) {
                $("#add-session").modal("hide");
                reloadPage("#sessions");
                toastr.success(response.message, "Added");
            } else {
                toastr.error(response.message, "Already Added");
            }
        },
        error: function (error) {
            closeLoader();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function (field, error) {
                    $("#label_" + field).html(error);
                });
            }
        },
    });
}

function editSession() {
    $(".field-error").html("");
    openLoader();
    $.ajax({
        type: "PATCH",
        url: $("#edit_session_form").attr("action"),
        data: $("#edit_session_form").serialize(),
        success: function (response) {
            closeLoader();
            $("#edit_task_session").modal("hide");
            reloadPage("#sessions");

            checkExistingSession();
            toastr.success(response.message, "Updated");
        },
        error: function (error) {
            closeLoader();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function (field, error) {
                    $("#label_edit_" + field).html(error);
                });
            }
        },
    });
}

function sendFile(file, editor, welEditable) {
    var data = new FormData();
    data.append("file", file);
    var url = "/content-image-upload";
    openLoader();
    $.ajax({
        data: data,
        type: "POST",
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function (url) {
            closeLoader();
            editor.summernote("editor.insertImage", url);
        },
    });
}

function loadIchecks() {
    $(".i-checks").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
    });

    $(".check-all").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
    });

    $(".check-all").on("ifChecked", function (event) {
        $(this).parents("tr").find(".i-checks").iCheck("check");
        triggeredByChild = false;
    });

    $(".check-all").on("ifUnchecked", function (event) {
        if (!triggeredByChild) {
            $(this).parents("tr").find(".i-checks").iCheck("uncheck");
        }
        triggeredByChild = false;
    });
    // Removed the checked state from "All" if any checkbox is unchecked
    $(".i-checks").on("ifUnchecked", function (event) {
        triggeredByChild = true;
        $(this).parents("tr").find(".check-all").iCheck("uncheck");
    });

    $(".i-checks").on("ifChecked", function (event) {
        if (
            $(this).parents("tr").find(".i-checks").filter(":checked").length ==
            $(this).parents("tr").find(".i-checks").length
        ) {
            $(this).parents("tr").find(".check-all").iCheck("check");
        }
    });

    $(".check-all").each(function () {
        if (
            $(this).parents("tr").find(".i-checks").filter(":checked").length ==
            $(this).parents("tr").find(".i-checks").length
        ) {
            $(this).iCheck("check");
        }
    });
}

function unloadIchecks() {
    $(".i-checks").iCheck("uncheck");
    $(".check-all").iCheck("uncheck");
}

function timeTaken() {
    $(".time-taken").each(function () {
        var count = $(this).data("count");
        var diff = $(this).data("total");
        var timer = $(this).children("strong");
        var totaltimer = $("#total_time");
        if (count > 0) {
            $.each($(this).data("starts").split(","), function (index, value) {
                diff = diff + (new Date() - new Date(value)) / 1000 / 60 / 60;
            });

            var seconds_left = diff * 60 * 60;
            var hours = 0;

            var countdownrefesh = setInterval(function () {
                // Add one to seconds
                seconds_left = seconds_left + count;

                hours_left = hours * 3600 + seconds_left;

                hours = parseInt(hours_left / 3600);
                seconds_left = seconds_left % 3600;

                minutes = parseInt(seconds_left / 60);

                minutesPercent = parseInt((minutes / 60) * 100);

                t = hours + "." + minutesPercent + " hrs ";
                timer.html(t);

                t = hours + "h " + minutes + "m";
                totaltimer.html(t);
            }, 1000);
        }
    });
}

function sessionTime() {
    $(".time").each(function () {
        var diff = (new Date() - new Date($(this).data("start"))) / 1000 / 60;

        var diff = diff + $(this).data("total");

        var seconds_left = diff * 60;
        var hours = 0;

        var timer = $(this).children(".timer");

        var countdownrefesh = setInterval(function () {
            // Add one to seconds
            seconds_left = seconds_left + 1;

            hours_left = hours * 3600 + seconds_left;

            hours = parseInt(hours_left / 3600);
            seconds_left = seconds_left % 3600;

            minutes = parseInt(seconds_left / 60);

            t = hours + "h " + minutes + "m";
            timer.html(t);
        }, 1000);
    });
}

function loadSubTask() {
    $.ajax({
        type: "POST",
        url: "/get-sub-task-list",
        data: {
            task_id: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            $(".sub-task-div").html(response.data);
            $(".dataTable").DataTable();
            loadTaskStatus();
        },
        error: function (error) {},
    });
}

function loadAssignees() {
    $.ajax({
        type: "POST",
        url: "/get-assigness-list",
        data: {
            task_id: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            $(".assignees").html(response.data);
        },
        error: function (error) {},
    });
}

function loadTaskStatus() {
    $.ajax({
        type: "POST",
        url: "/get-task-status",
        data: {
            task_id: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            $(".status-dropdown").html(response.data);
            $(".chosen-select").chosen({
                width: "100%",
            });
        },
        error: function (error) {},
    });
}

function checkWhetherExceedsTime() {
    $.ajax({
        type: "POST",
        url: "/check-whether-exceeds-time",
        data: {
            task_id: $("#task-id").attr("data-id"),
        },
        success: function (response) {
            if (response.flag == true) {
                $(".modal-backdrop").remove();
                $("#exceed_time").modal({
                    backdrop: "static",
                    keyboard: false,
                });
                return false;
            } else {
                changeStatusFinish();
            }
        },
        error: function (error) {},
    });
}

$(document).on("click", "#exceed_time .continue-btn", function () {
    $.ajax({
        type: "POST",
        url: "/add-time-exceed-reason",
        data: {
            task_id: $("#task-id").attr("data-id"),
            reason: $("#exceed_time .exceed-reason").val(),
        },
        success: function (response) {
            $("#exceed_time").modal("hide");
            changeStatusFinish();
        },
        error: function (error) {
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function (field, error) {
                    $("#label_exceed_" + field).html(error);
                });
            }
        },
    });
});

function acceptContinue(row_id) {
    if ($(".dev-pending").length > 0 || $(".rev-pending").length > 0) {
        Swal.fire({
            icon: "error",
            title: "Something went wrong!",
            text: "Checklists not completed.",
        });
    } else if (
        $("#approver_status").attr("data-total") >
        $("#approver_status").attr("data-approved")
    ) {
        Swal.fire({
            icon: "error",
            title: "Something went wrong!",
            text: "Admins not approved the task yet.",
        });
    } else {
        $.ajax({
            method: "POST",
            url: "/accept-completion",
            data: {
                id: row_id,
                status: "Done",
            },
            success: function (response) {
                toastr.success(response.message, "Accepted");
                reloadPage();
            },
        });
    }
}

function checkWhetherExceedsTimeWithReason(row_id) {
    $.ajax({
        method: "POST",
        url: "/check-whether-exceeds-time-with-reason",
        data: {
            id: row_id,
        },
        success: function (response) {
            if (response.flag == true) {
                Swal.fire({
                    title: "Exceeds Estimated Time!",
                    text: "Reason: ",
                    html: response.exceed_reason,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Accept!",
                    cancelButtonText: "No, Reject!",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.value) {
                        acceptContinue(row_id);
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        $("#reject_task_modal #reject_id").val(row_id);
                        $("#task_rejection_form").append(
                            '<div class="row"><div class="col-md-12"><div class="form-group"><label>Reason entered by the employee </label><textarea rows="4" class="form-control summernote" placeholder="" name="exceed_reason" id="comments">' +
                                response.exceed_reason +
                                "</textarea></div></div></div>"
                        );
                        $(".summernote").summernote();
                        $("#reject_task_modal").modal("show");
                    }
                });
            } else {
                acceptContinue(row_id);
            }
        },
    });
}

$(document).ready(function () {
    $(document).on("click", ".create-tag", function (e) {
        toasterOption();
        e.preventDefault();
        $(".field-error").html("");
        openLoader();
        var data = $("#add_tag_form").serialize();
        $.ajax({
            url: $("#add_tag_form").attr("action"),
            data: data,
            type: "POST",
            success: function (response) {
                $("#add_tag").modal("hide");
                closeLoader();
                toastr.success(response.message, "Created");
                if (($("#edit_task").data("bs.modal") || {}).isShown) {
                    $("#edit_task_form #tag")
                        .append(
                            '<option selected value="' +
                                response.tag.slug +
                                '">' +
                                response.tag.title +
                                "</option>"
                        )
                        .trigger("chosen:updated");
                }
                if (($("#create_sub_task").data("bs.modal") || {}).isShown) {
                    $("#add_sub_task_form #tag")
                        .append(
                            '<option selected value="' +
                                response.tag.slug +
                                '">' +
                                response.tag.title +
                                "</option>"
                        )
                        .trigger("chosen:updated");
                }
                if ($("#add_task_form #tag").length) {
                    if (($("#create_task").data("bs.modal") || {}).isShown) {
                        $("#add_task_form #tag")
                            .append(
                                '<option selected value="' +
                                    response.tag.slug +
                                    '">' +
                                    response.tag.title +
                                    "</option>"
                            )
                            .trigger("chosen:updated");
                    } else {
                        $("#add_task_form #tag")
                            .append(
                                '<option value="' +
                                    response.tag.slug +
                                    '">' +
                                    response.tag.title +
                                    "</option>"
                            )
                            .trigger("chosen:updated");
                    }
                }
            },
            error: function (error) {
                closeLoader();
                if (error.status == 422) {
                    if (error.responseJSON.errors) {
                        $.each(
                            error.responseJSON.errors,
                            function (field, error) {
                                $("#add_tag_form #label_" + field).html(error);
                            }
                        );
                    }
                }
            },
        });
    });

    $("#add_tag").on("hidden.bs.modal", function () {
        $(this).find(".text-danger").html("");
        $("#add_tag_form").trigger("reset");
    });
});

var timerRunning = $.cookie("timerRunning");
// var currentUser = "<?php echo Auth::user()->id ?>";
var currentUser = $(".userIdCookie").val();
var intervel;
var timerStopped;
var active = 1;
/* Timer Start */
$(document).on("click", "#start", function () {
    if (typeof $.cookie("timerRunning") == "undefined") {
        setTimer(0);
        setCookies();
        addSession();
        $("#start").find("#timer-button").text("STOP");
        if ($("#start").length) {
            $("#start").parent().parent().find(".pauseDiv").remove();
            $("#start")
                .parent("div")
                .removeClass("col-md-8")
                .addClass("col-md-4");
            $("#start")
                .parent("div")
                .after(
                    '<div class="col-md-4 pauseDiv"><button class="btn w-100" id="pause" style="width: 100%"><span>PAUSE</span></button></div>'
                );
        }
    } else {
        // removeCookies();
        // unsetTimer();
        $("#stop_session").modal("show");
    }
});

$(document).on("click", "#pause", function (e) {
    e.preventDefault();
    openLoader();
    var obj = $(this);
    var td = obj.closest("td");
    if ($.cookie("timerRunning") == "true") {
        $.ajax({
            type: "POST",
            url: "/pause-session",
            data: {
                "task-id": $.cookie("running_task"),
                "current-task": $("#task-id").data("id"),
            },
            success: function (response) {
                unsetTimer();
                reloadPage();
                changeTaskSessionButton(0);
                removePauseIcon();
                removePauseButton();
                if (finish_state) {
                    finishState();
                }
                toastr.success("Current Running Session", "Paused");
                closeLoader();
                reloadCurrentPage();
            },
            error: function (error) {
                closeLoader();
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_" + field).html(error);
                    });
                }
            },
        });
        window.clearInterval(interval);
        totalSeconds = 0;
    } else {
        closeLoader();
    }
});

function removePauseButton() {
    $.ajax({
        type: "GET",
        url: "/check-task-session",
        data: {
            "task-id": $.cookie("running_task"),
        },
        success: function (response) {
            if (response.flag == true) {
                if (response.status == "pause") {
                    removePauseIcon();
                }
            }
        },
    });
}

function removePauseIcon() {
    $("#pause-task").removeClass("hidden");
    $("#pause-task").addClass("hidden");
}

$(document).on("click", "#resume-task", function (e) {
    e.preventDefault();
    openLoader();
    if (typeof $.cookie("timerRunning") == "undefined") {
        getCurrentTaskTimer();
        changeTaskSessionButton(0);
        $("#resume-task").parent("div").addClass("pauseDiv");
        $("#resume-task")
            .find("#resume-button")
            .attr("id", "pause-button")
            .text("PAUSE");
        $("#resume-task").attr("id", "pause");
    } else {
        closeLoader();
    }
    closeLoader();
});

function getCurrentTaskTimer() {
    $.ajax({
        type: "GET",
        url: "/check-task-session",
        data: {
            "task-id": $("#task-id").data("id"),
        },
        success: function (response) {
            if (response.flag == true) {
                if (response.status == "pause") {
                    setTimer(parseInt(response.sec));
                }
                setCookies();
                addSession("resume");
            }
            closeLoader();
        },
    });
}

$(document).on("click", "#stop-task", function () {
    $.cookie("running_task", $(this).data("task-id"), {
        expires: 1,
        path: "/",
    });
    if (typeof $.cookie("running_task") != "undefined") {
        $("#stop_session").modal("show");
    }
});

$("#stop_session").on("hidden.bs.modal", function () {
    updateSession();
    $(".overlay").remove();
});

/* Stop Session */
$(document).on("click", "#stop_session .continue-btn", function (e) {
    openLoader();
    removeCookies();
    unsetTimer();
    $.ajax({
        type: "POST",
        url: "/stop-session",
        data: {
            "task-id": $("#task-id-timer").val(),
            comment: $("#stop_session_comment").val(),
            session_type: $("#stop_session_type").val(),
            status: $("#finish_task_status").val(),
        },
        success: function (response) {
            removeCookies();
            unsetTimer();
            $("#stop_session").modal("hide");

            $("#stop_session_comment").val("");
            toastr.success(response.message, "Updated");
            reloadPage();
            loadCurrentSession();
            if (finish_state) {
                $(document).find(".exceed-reason").summernote("destroy");
                $(document).find(".exceed-reason").summernote();
                finishState();
            }
            $(".modal-backdrop").remove();
            closeLoader();
            reloadCurrentPage();
        },
        error: function (error) {
            $(".overlay").remove();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function (field, error) {
                    $("#label_" + field).html(error);
                });
            }
        },
    });
});
//function development complete

$(document).on("click", "#development_complete", function () {
    if ($(".checklist-link").length != $(".todo-completed").length) {
        Swal.fire({
            icon: "error",
            title: "Something went wrong!",
            text: "Update all checklists before finishing task.",
        });
    } else {
        if ($.cookie("timerRunning") == "true") {
            finish_state = true;
            $("#stop_session").modal("show");
        } else {
            finishState();
        }
    }
});

function loadCurrentSession() {
    $.ajax({
        type: "GET",
        url: "/check-task-session",
        data: {
            "task-id": $.cookie("running_task"),
        },
        success: function (response) {
            if (response.flag == false) {
                $("#pause-task").addClass("hidden");
            }
        },
    });
}
//function addSession ajax
function addSession(type) {
    $.ajax({
        type: "GET",
        url: "/add-task-session",
        data: {
            "task-id": $("#task-id-timer").val(),
            status_task: type,
            status: $("#start_task_status").val(),
        },
        success: function (response) {
            if (response.success) {
                $("#task_status")
                    .val(response.status)
                    .trigger("chosen:updated");
                $("#pause-task").removeClass("hidden");
            }
        },
    });
}
//function updateSession ajax
function updateSession() {
    if (active == 1) {
        $("#start").prop("disabled", false);
        if (currentUser != $.cookie("taskStartedBy")) {
            removeCookies();
        }

        checkSession();
        active = 0;
    }
}
function checkSession() {
    $.ajax({
        type: "GET",
        url: "/check-session",
        data: { "task-id": $("#task-id").attr("data-id") },
        success: function (response) {
            if (response.flag == true) {
                if (response.id != $("#task-id").attr("data-id")) {
                    $("#start").prop("disabled", true);
                } else {
                    totalSeconds = parseInt(response.sec);
                    setCookies();
                    setTimer(totalSeconds);
                    document.getElementById("timer-button").innerHTML = "STOP";
                }
            } else {
                if (response.flag == false) {
                    if (typeof response.old !== "undefined") {
                        $("#start").prop("disabled", true);
                        Swal.fire({
                            icon: "warning",
                            title:
                                "There exists a session which is not properly stopped. Please update the session manualy.<br><br>Task Title: " +
                                response.task +
                                "<br>Date: " +
                                response.date,
                            footer:
                                '<a href="/tasks/' +
                                response.old +
                                '">Go To Task: ' +
                                response.task +
                                "</a>",
                        });
                    } else {
                        if (response.assigned == "false") {
                            $(".timer-control").hide();
                        } else {
                            $(".timer-control").show();
                        }
                        removeCookies();
                        $("#start").prop("disabled", false);
                    }
                }
            }
        },
    });
}
function setCookies() {
    $.cookie("timerRunning", "true", { expires: 1, path: "/" });
    $.cookie("taskStartedBy", currentUser, { expires: 1, path: "/" });
    $.cookie("running_task", $("#task-id-timer").val(), {
        expires: 1,
        path: "/",
    });
}
function removeCookies() {
    $.removeCookie("timerRunning", { path: "/" });
    $.removeCookie("running_task", { path: "/" });
    $.removeCookie("taskStartedBy", { path: "/" });
}

function setTimer(totalSeconds) {
    interval = setInterval(() => {
        ++totalSeconds;
        document.getElementById("seconds").innerHTML = pad(
            parseInt(totalSeconds % 60)
        );
        document.getElementById("minutes").innerHTML = pad(
            parseInt((totalSeconds / 60) % 60)
        );
        document.getElementById("hours").innerHTML = pad(
            parseInt(totalSeconds / 60 / 60)
        );
    }, 1000);
}
function unsetTimer() {
    var isTimerExist = document.getElementById("seconds");
    if (isTimerExist) {
        active = 1;
        totalSeconds = 0;
        if (typeof interval != "undefined") window.clearInterval(interval);

        document.getElementById("seconds").innerHTML = "00";
        document.getElementById("minutes").innerHTML = "00";
        document.getElementById("hours").innerHTML = "00";
        if (document.getElementById("#start")) {
            document.getElementById("timer-button").innerHTML = "START";
        }
    }
}
function pad(val) {
    var valString = val + "";
    if (valString.length < 2) {
        return "0" + valString;
    } else {
        return valString;
    }
}

window.addEventListener("focus", updateSession);
window.addEventListener("blur", unsetTimer);

$(document).ready(function () {
    $(document).on("click", ".view-comments", function (e) {
        e.preventDefault();
        var href = $(this).attr("data-href");
        openLoader();
        reload(href);
    });

    $(document).on("click", "#cmnt_submit", function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        var mentions = $(".comment-mentions");
        $.ajax({
            type: "POST",
            url: $("#save_comments").attr("action"),
            data: $("#save_comments").serialize(),
            success: function (response) {
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_" + field).html(error);
                    });
                }
            },
        });
    });

    $(document).on("click", ".delete-submit", function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        $.ajax({
            type: "POST",
            url: $(this).parents("form").attr("action"),
            data: $(this).parents("form").serialize(),
            success: function (response) {
                $("#comment-delete-modal").modal("hide");
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_" + field).html(error);
                    });
                }
            },
        });
    });

    $(document).on("click", ".reply-submit", function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        var $this = $(this);
        $.ajax({
            type: "POST",
            url: $(this).parents("form").attr("action"),
            data: $(this).parents("form").serialize(),
            success: function (response) {
                $("#comment-reply-modal").modal("hide");
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $this
                            .parents("form")
                            .find("#label_" + field)
                            .html(error);
                    });
                }
            },
        });
    });

    $(document).on("click", ".edit-submit", function (e) {
        e.preventDefault();
        openLoader();
        var href = $("#load_href").val();
        var $this = $(this);
        $.ajax({
            type: "POST",
            url: $(this).parents("form").attr("action"),
            data: $(this).parents("form").serialize(),
            success: function (response) {
                $("#comment-edit-modal").modal("hide");
                reload(href);
            },
            error: function (error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $this
                            .parents("form")
                            .find("#label_" + field)
                            .html(error);
                    });
                }
            },
        });
    });

    $(document).on("show.bs.modal", "#comment-edit-modal", function (e) {
        var formAction = $(e.relatedTarget).data("action");
        var commentHtml = $(e.relatedTarget).data("comment");

        $(e.target).find("form").attr("action", formAction);
        var summerNoteElem = $(e.target).find(".summernote");
        summerNoteElem.summernote({
            height: 200, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false, // set focus to editable area after initializing summernote
            code: "",
            hint: {
                mentions: mentions,
                match: /\B@(\w*)$/,
                search: function (keyword, callback) {
                    callback(
                        $.grep(this.mentions, function (item) {
                            keyword = keyword.toLowerCase();
                            return (
                                item.name.toLowerCase().indexOf(keyword) == 0
                            );
                        })
                    );
                },
                template: function (item) {
                    return item.name;
                },
                content: function (item) {
                    var mentionedUsers = $("#comment-edit-mentions").val();
                    mentionedUsers = mentionedUsers.replace(/^,|,$/g, "");
                    $("#comment-edit-mentions").val(
                        mentionedUsers + "," + item.id
                    );
                    return $(
                        '<span><span style="background: #ddd;padding: 5px;text-align: center;border-radius: 10px;">@' +
                            item.name +
                            "</span>&nbsp; &nbsp;</span>"
                    )[0];
                },
            },
        });
        summerNoteElem.summernote("code", commentHtml);
    });

    $(document).on("show.bs.modal", "#comment-reply-modal", function (e) {
        var formAction = $(e.relatedTarget).data("action");
        $(e.target).find("form").attr("action", formAction);
        var summerNoteElem = $(e.target).find(".summernote");
        summerNoteElem.summernote("destroy");
        summerNoteElem.summernote({
            height: 200, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false, // set focus to editable area after initializing summernote
            code: "",
            hint: {
                mentions: mentions,
                match: /\B@(\w*)$/,
                search: function (keyword, callback) {
                    callback(
                        $.grep(this.mentions, function (item) {
                            keyword = keyword.toLowerCase();
                            return (
                                item.name.toLowerCase().indexOf(keyword) == 0
                            );
                        })
                    );
                },
                template: function (item) {
                    return item.name;
                },
                content: function (item) {
                    var mentionedUsers = $("#comment-reply-mentions").val();
                    mentionedUsers = mentionedUsers.replace(/^,|,$/g, "");
                    $("#comment-reply-mentions").val(
                        mentionedUsers + "," + item.id
                    );
                    return $(
                        '<span><span style="background: #ddd;padding: 5px;text-align: center;border-radius: 10px;">@' +
                            item.name +
                            "</span>&nbsp; &nbsp;</span>"
                    )[0];
                },
            },
        });
        summerNoteElem.summernote("code", "");
    });

    $(document).on("show.bs.modal", "#comment-delete-modal", function (e) {
        var formAction = $(e.relatedTarget).data("action");
        $(e.target).find("form").attr("action", formAction);
        var commentHtml = $(e.relatedTarget).data("comment");

        $(e.target).find("#comment-body").html(commentHtml);
    });
});

function reload(href) {
    $.ajax({
        type: "GET",
        url: href,
        data: {},
        success: function (data) {
            $("#commentsWrapper").html(data.data);
            $("#load_href").val(href);
            inputsLoader();
            closeLoader();
        },
    });
}

jQuery(document).ready(function () {
    $(".dataTable").DataTable();

    $(document).keydown(function (e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $("#create_sub_task").modal("hide");
        }
    });

    /** to update on enter key */
    $(document).on("keyup", "#subtask_title_id", function (event) {
        if (event.keyCode === 13) {
            $(".create-sub_task").click();
        }
    });

    $(document).on("keyup", "#subtask-estimated_time", function (event) {
        if (event.keyCode === 13) {
            $(".create-sub_task").click();
        }
    });

    $(document).on("keyup", "#subtask_url", function (event) {
        if (event.keyCode === 13) {
            $(".create-sub_task").click();
        }
    });

    $(".create-sub-modal").click(function (e) {
        $(".datetimepicker").datepicker("setDate", new Date());
    });

    $(document).on("click", ".create-sub-task", function (e) {
        e.preventDefault();
        $(".field-error").html("");
        var parentTask = $("#task_parent").val();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var data = new FormData($("#add_sub_task_form")[0]);
        $.ajax({
            url: "/create-sub-task",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            type: "POST",
            success: function (response) {
                removeOverlay();
                $("#create_sub_task").modal("hide");
                $(".modal-backdrop").remove();

                $("#task_parent").val(parentTask).trigger("chosen:updated");
                toastr.success(response.message, "Created");
                //$('.list').html(response.data);
                // reloadPage();
                setTimeout(reloadCurrentPage, 1000);
                loadSubTask();
            },
            error: function (error) {
                removeOverlay();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_" + field).html(error);
                    });
                }
            },
        });
    });

    /**
     * Removing validation errors and reset form on model window close
     */
    $("#create_sub_task").on("hidden.bs.modal", function () {
        $(this).find(".text-danger").html("");
        $("#add_sub_task_form").trigger("reset");
        $("#add_sub_task_form .summernote").summernote("reset");
        $(".chosen-select")
            .not("#task_parent")
            .val("")
            .trigger("chosen:updated");
    });

    $(document).on("click", ".delete_sub_task_onclick", function () {
        var deleteTaskId = $(this).data("id");
        $("#delete_sub_task #delete_sub_task_id").val(deleteTaskId);
    });
});
inputsLoader();

function removeOverlay() {
    $("body .overlay").each(function () {
        $(this).remove();
    });
}

function inputsLoader() {
    $(".chosen-select").chosen({
        width: "100%",
    });

    $(".datetimepicker").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
    });
    $(".summernote").summernote({
        height: 200, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: false, // set focus to editable area after initializing summernote
        hint: {
            mentions: mentions,
            match: /\B@(\w*)$/,
            search: function (keyword, callback) {
                callback(
                    $.grep(this.mentions, function (item) {
                        keyword = keyword.toLowerCase();
                        return item.name.toLowerCase().indexOf(keyword) == 0;
                    })
                );
            },
            template: function (item) {
                return item.name;
            },
            content: function (item) {
                var mentionedUsers = $("#comment_mentions").val();
                mentionedUsers = mentionedUsers.replace(/^,|,$/g, "");
                $("#comment_mentions").val(mentionedUsers + "," + item.id);
                return $(
                    '<span><span style="background: #ddd;padding: 5px;text-align: center;border-radius: 10px;">@' +
                        item.name +
                        "</span>&nbsp; &nbsp;</span>"
                )[0];
            },
        },
    });
    $(function () {
        $("#daterange").daterangepicker({
            opens: "left",
            locale: {
                format: "MMM DD, YYYY",
            },
            autoUpdateInput: false,
        });

        $("#daterange").on("apply.daterangepicker", function (ev, picker) {
            $(this).val(
                picker.startDate.format("MMM DD, YYYY") +
                    " - " +
                    picker.endDate.format("MMM DD, YYYY")
            );
        });

        $("#daterange").on("cancel.daterangepicker", function (ev, picker) {
            $(this).val("");
        });
    });
}

function reloadCurrentPage() {
    window.location.reload();
}

$(document).on("click", "#export-session-csv", function (e) {
    e.preventDefault();
    openLoader();
    $.ajax({
        type: "POST",
        url: "/export-session",
        data: {
            userSession: $("#userSession").val(),
            userSessionType: $("#userSessionType").val(),
            taskId: $("#task-id").attr("data-id"),
            daterange: $("#daterange").val(),
        },
        xhrFields: {
            responseType: "blob",
        },
        success: function (response) {
            var filename = "Tasksession.xlsx";
            var blob = new Blob([response], {
                type: "application/octet-stream",
            });
            var url = window.URL.createObjectURL(blob);
            var link = document.createElement("a");
            link.href = url;
            link.download = filename;
            link.click();
            closeLoader();
        },
        error: function (xhr, status, error) {
            closeLoader();
        },
    });
});

$(document).on("click", ".destroy-task", function () {
    var deleteTaskId = $(this).data("id");
    var deleteTaskType = $(this).data("type");
    $("#destroy_task #destroy_task_id").val(deleteTaskId);
    $("#destroy_task #destroy_task_type").val(deleteTaskType);
});

/**
 * Destroy task form - continue button action
 */
$(document).on("click", "#destroy_task .continue-btn", function () {
    toasterOption();
    var deleteTaskId = $("#destroy_task #destroy_task_id").val();
    openLoader();
    var deleteTaskType = $("#destroy_task #destroy_task_type").val();
    $("#destroy_task").modal("hide");
    $.ajax({
        type: "POST",
        url: "/destroy-task-ajax",
        data: {
            taskId: deleteTaskId,
            projectId: $("#project_id").attr("data-id"),
        },
        success: function (response) {
            closeLoader();
            if (response.status === "success") {
                toastr.success(response.message, "Deleted");
                setTimeout(function () {
                    window.location.href = "/tasks";
                }, 1000);
            } else {
                toastr.error(response.message, "Failed");
            }
        },
        error: function (error) {
            closeLoader();
        },
    });
});

$(document).on("click", ".save-branch", function (e) {
    e.preventDefault();
    $(".field-error").html("");
    $("body").append(
        '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
    );
    var data = new FormData($("#add_branch_form")[0]);
    $.ajax({
        url: "/add-branch",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        type: "POST",
        success: function (response) {
            removeOverlay();
            $(".input-value").val("");
            showBranchNames(response.data);
            showBranchList(response.data);
        },
        error: function (error) {
            removeOverlay();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function (field, error) {
                    $("#label_" + field).html(error);
                });
            }
        },
    });
});

$(document).on("click", ".delete-branch", function (e) {
    var branchId = $(this).attr("data-id");
    var taskId = $("#task_id").val();
    Swal.fire({
        title: "Are you sure you want to remove this branch from this task ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Remove!",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "/delete-branch",
                type: "POST",
                data: {
                    id: branchId,
                    task_id: taskId,
                },
                success: function (response) {
                    removeOverlay();
                    showBranchNames(response.data);
                    showBranchList(response.data);
                },
                error: function (error) {
                    removeOverlay();
                    if (error.responseJSON.errors) {
                        $.each(
                            error.responseJSON.errors,
                            function (field, error) {
                                $("#label_" + field).html(error);
                            }
                        );
                    }
                },
            });
        }
    });
});

$(document).on("click", ".branch-close", function (e) {
    $(".field-error").empty();
});

function showBranchList(branches) {
    const branchListBody = $(".branch-list");
    branchListBody.empty();

    if (branches.length > 0) {
        branches.forEach((branch) => {
            const row = `<tr>
                        <td colspan="2">${branch.name}</td>
                        <td colspan="8"><a href="${branch.url}" target="_blank">${branch.url}</a></td>
                        <td colspan="1"><a class="dropdown-item delete-branch" href="#" data-id="${branch.id}" title="Delete"><i class='ri-delete-bin-line m-r-5'></i></a></td>
                        </tr>`;
            branchListBody.append(row);
        });
    } else {
        const noBranchRow = `<tr>
                                <td colspan="10" align="center">No Branches Found</td>
                            </tr>`;
        branchListBody.append(noBranchRow);
    }
}

function showBranchNames(branches) {
    const branchListBody = $(".branch-name-list");
    branchListBody.empty();
    if (branches.length > 0) {
        branches.forEach((branch) => {
            const row = `<a href="${branch.url}" target="_blank" data-toggle="tooltip" data-placement="right" title="${branch.name}"><strong>${branch.name}</strong></a><br>`;
            branchListBody.append(row);
        });
    } else {
        const noBranchRow = "<strong>No Branches Added</strong>";
        branchListBody.append(noBranchRow);
    }
}


$(document).on('click', '.delete-task-rejection', function() {
    var deleteRejectionId = $(this).data('id');
    Swal.fire({
        icon: "warning",
        title: "Are you sure you want to delete this rejection ?",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            openLoader();
            $.ajax({
                type: "DELETE",
                url: "/task-reject-delete/" + deleteRejectionId,
                data: {},
                success: function (response) {
                    closeLoader();
                    if(response.status) {
                        toastr.success(
                            response.message,
                            "Deleted task rejection successfully !"
                        );
                    } else {
                        toastr.error(
                            response.message,
                            "Couldn't delete task rejection !"
                        );
                    }
                    reloadPage("#rejection-list");
                },
                error: function (error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        toastr.error(
                            response.message,
                            "Couldn't delete task rejection !"
                        );
                    }
                },
            });
        }
    });
});
    