$(document).ready(function () {
    inputsLoader();

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

    $(document).keydown(function (e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $("#create_asset").modal("hide");
            $("#edit_asset").modal("hide");
            $("#delete_asset").modal("hide");
        }
    });

    /**
     * Create project - submit button action
     */
    $(".create-asset").click(function (e) {
        toasterOption();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $(".field-error").html("");
        e.preventDefault();
        var data = new FormData($("#add_asset_form")[0]);

        $.ajax({
            method: "POST",
            url: $("#add_asset_form").attr("action"),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".overlay").remove();
                $("#create_asset").modal("hide");
                toastr.success(response.message, "Saved");
                setTimeout(loadAssets, 2000);
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        if (field.startsWith("files")) {
                            $("#label_files").html(
                                "The file must be a PDF, JPG, PNG, or JPEG & size must not exceed 5MB"
                            );
                        } else {
                            $("#label_" + field).html(error);
                        }
                    });
                }
            },
        });
    });

    /**
     * Removing validation errors and reset form on model window close
     */
    $("#create_project").on("hidden.bs.modal", function () {
        $(this).find(".text-danger").html("");
        $("#add_asset_form").trigger("reset");
        $(".chosen-select").val("").trigger("chosen:updated");
    });

    /**
     * Asset edit form loading when clicking edit
     */
    $(document).on("click", ".edit-asset", function (e) {
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var assetId = $(this).data("id");
        var editUrl = "/assets/" + assetId + "/edit";

        $.ajax({
            type: "GET",
            url: editUrl,
            data: {},
            success: function (data) {
                $("#edit_asset").html(data);
                inputsLoader();
                loadIchecks();
                $(".overlay").remove();
                $("#edit_asset").modal("show");
            },
        });
    });

    /**
     * Edit asset form - submit button action
     */
    $(document).on("click", ".update-asset", function (e) {
        var page = $("#edit_asset").attr("data-action");
        toasterOption();
        $(".field-error").html("");
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        e.preventDefault();
        var currentPage = assets_table.page();
        var data = new FormData($("#edit_asset_form")[0]);
        $.ajax({
            type: "POST",
            url: $("#edit_asset_form").attr("action"),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".overlay").remove();
                toastr.success(response.message, "Updated");
                $("#edit_asset").modal("hide");
                assets_table.draw('page');
                assets_table.page(currentPage).draw(false);
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        if (field.startsWith("files")) {
                            $("#edit_asset_form #label_files").html(
                                "The file must be a PDF, JPG, PNG, or JPEG & size must not exceed 5MB"
                            );
                        } else {
                            $("#edit_asset_form #label_" + field).html(error);
                        }
                    });
                }
            },
        });
    });

    $(document).on("click", ".delete-asset", function (e) {
        var deleteAssetId = $(this).data("id");
        $("#delete_asset #delete_asset_id").val(deleteAssetId);
    });

    /**
     * Delete asset - button click action
     */
    $(document).on("click", "#delete_asset .continue-btn", function (e) {
        var deleteAssetId = $("#delete_asset #delete_asset_id").val();
        deleteUrl = "/assets/" + deleteAssetId;
        var currentPage = assets_table.page();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $("#delete_asset").modal("hide");
        $.ajax({
            type: "DELETE",
            url: deleteUrl,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $(".overlay").remove();
                if (response.status === "success") {
                    toastr.success(response.message, "Deleted");
                    assets_table.draw('page');
                    assets_table.page(currentPage).draw(false);
                } else {
                    toastr.error(response.message, "Failed");
                }
            },
            error: function (error) {},
        });
    });

    $(document).on("click", ".assign-asset", function () {
        var assignAssetId = $(this).data("id");
        $("#assign_asset #assign_asset_id").val(assignAssetId);
    });

    /**
     * Assign asset form - submit button action
     */
    $(document).on("submit", "#assign_asset_form", function (e) {
        e.preventDefault();
        toasterOption();
        $(".field-error").html("");
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $.ajax({
            type: "POST",
            url: $("#assign_asset_form").attr("action"),
            data: $("#assign_asset_form").serialize(),
            success: function (response) {
                $(".overlay").remove();
                $("#assign_asset").modal("hide");
                toastr.success(response.message, "Updated");
                setTimeout(loadAssets, 1000);
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#assign_asset_form #label_" + field).html(error);
                    });
                }
            },
        });
    });

    $(document).on("click", ".return-asset", function () {
        var returnAssetId = $(this).data("id");
        $("#return_asset #return_asset_id").val(returnAssetId);
    });

    /**
     * Return asset - button click action
     */
    $(document).on("click", "#return_asset .continue-btn", function () {
        var returnAssetId = $("#return_asset #return_asset_id").val();
        returnUrl = "/return-asset/" + returnAssetId;
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $("#return_asset").modal("hide");
        $.ajax({
            type: "POST",
            url: returnUrl,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $(".overlay").remove();
                if (response.status === "success") {
                    toastr.success(response.message, "Returned");
                    setTimeout(loadEmployeeAssets, 2000);
                } else {
                    toastr.error(response.message, "Failed");
                }
            },
            error: function (error) {},
        });
    });

    $(document).on("click", ".ticket-raise-asset", function () {
        var AssetId = $(this).data("id");
        $("#ticket_raise_asset #ticket_raise_asset_id").val(AssetId);
    });

    /**
     * Ticket raise  asset form - submit button action
     */
    $(document).on("click", ".ticket_raise_asset", function (e) {
        var page = $("#ticket_raise_asset").attr("data-action");
        toasterOption();
        $(".field-error").html("");
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $("#ticket_raise_asset_form").attr("action"),
            data: $("#ticket_raise_asset_form").serialize(),
            success: function (response) {
                $(".overlay").remove();
                $("#ticket_raise_asset").modal("hide");
                toastr.success(response.message, "Updated");
                setTimeout(window.location.reload(), 2000);
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#ticket_raise_asset_form #label_" + field).html(
                            error
                        );
                    });
                }
            },
        });
    });

    /**
     * Asset edit form loading when clicking edit
     */
    $(document).on("click", ".ticket-raise-edit-asset", function (e) {
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var assetId = $(this).data("id");
        var editUrl = "/ticket-issue-update/" + assetId;

        $.ajax({
            type: "GET",
            url: editUrl,
            data: {},
            success: function (data) {
                $("#ticket_raise_edit_asset").html(data);
                inputsLoader();
                loadIchecks();
                $(".overlay").remove();
                $("#ticket_raise_edit_asset").modal("show");
            },
        });
    });

    /**
     * Edit asset form - submit button action
     */
    $(document).on("click", ".ticket_raise_edit_asset", function (e) {
        var page = $("#ticket_raise_edit_asset").attr("data-action");
        toasterOption();
        $(".field-error").html("");
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        e.preventDefault();
        var summernoteContent = $("#reason").summernote("code");
        summernoteContent = String(summernoteContent).replace(
            /<p><br><\/p>/g,
            ""
        );
        $.ajax({
            type: "POST",
            url: $("#ticket_raise_asset_edit_form").attr("action"),
            data:
                $("#ticket_raise_asset_edit_form").serialize() +
                "&reason=" +
                summernoteContent,
            success: function (response) {
                $(".overlay").remove();
                $("#ticket_raise_edit_asset").modal("hide");
                toastr.success(response.message, "Updated");
                setTimeout(window.location.reload(), 2000);
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#ticket_raise_asset_edit_form #label_" + field).html(
                            error
                        );
                    });
                }
            },
        });
    });

    /**
     * Asset edit form loading when clicking edit
     */
    $(document).on("click", ".ticket-status-edit", function (e) {
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var ticketId = $(this).data("id");
        var editUrl = "/ticket-status-edit/" + ticketId;

        $.ajax({
            type: "GET",
            url: editUrl,
            data: {},
            success: function (data) {
                $("#ticket_status_edit").html(data);
                inputsLoader();
                loadIchecks();
                $(".overlay").remove();
                $("#ticket_status_edit").modal("show");
            },
        });
    });

    /**
     * Edit asset form - submit button action
     */
    $(document).on("click", ".ticket_status_update", function (e) {
        var page = $("#ticket_status_update").attr("data-action");
        toasterOption();
        $(".field-error").html("");
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $("#ticket_status_update_form").attr("action"),
            data: $("#ticket_status_update_form").serialize(),
            success: function (response) {
                $(".overlay").remove();
                $("#ticket_status_edit").modal("hide");
                toastr.success(response.message, "Updated");
                setTimeout(window.location.reload(), 1000);
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#ticket_status_update_form #label_" + field).html(
                            error
                        );
                    });
                }
            },
        });
    });

    $(document).on("click", ".delete-asset-doc", function () {
        var deleteDocId = $(this).data("id");
        $("#delete_asset_document #delete_asset_doc_id").val(deleteDocId);
    });

    /**
     * Delete asset - button click action
     */
    $(document).on(
        "click",
        "#delete_asset_document .continue-btn",
        function () {
            var $this = $(this);
            var delete_asset_doc = $(
                "#delete_asset_document #delete_asset_doc_id"
            ).val();
            deleteUrl = "/assets/delete-document/" + delete_asset_doc;
            $("body").append(
                '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
            );
            $("#delete_asset_doc").modal("hide");
            $.ajax({
                type: "DELETE",
                url: deleteUrl,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    $(".overlay").remove();
                    if (response.status === "success") {
                        toastr.success(response.message, "Deleted");
                        $("#delete_asset_document").modal("hide");
                        $(
                            "#edit_asset_modal .doc-repeat" + delete_asset_doc
                        ).remove();
                    } else {
                        toastr.error(response.message, "Failed");
                    }
                },
                error: function (error) {},
            });
        }
    );

    function loadAssets() {
        window.location = "/assets";
    }

    function loadEmployeeAssets() {
        window.location = "/employee-asset-list";
    }

    function loadEmployeeTicketRaisedAssets() {
        window.location = "/employee-ticket-raised-assets";
    }

    function loadTicketRaisedAssets() {
        window.location = "/ticket-raised-assets";
    }

    $(document).on("change", ".asset-filter", function (e) {
        searchList(e);
    });

    function searchList(e) {
        e.preventDefault();
        $("#search-asset").submit();
    }

    $(document).on("change", "#search_ticket_employee", function (e) {
        searchTicketList(e);
    });
    $("#search_ticket_status").change(function (e) {
        searchTicketList(e);
    });
    $("#search_resolving_status").change(function (e) {
        searchTicketList(e);
    });
    $(document).on("change", "#search_asset_employee", function (e) {
        searchTicketList(e);
    });

    function searchTicketList(e) {
        e.preventDefault();
        $("#ticket-search-asset").submit();
    }

    $(document).on("click", ".filter-reset", function (e) {
        e.preventDefault();
        openLoader();
        $(".asset-filter").val("").trigger("chosen:updated");
        $("#daterange").val("");
        searchList(e);
        closeLoader();
    });

    $(document).on("change", "#employee_search_ticket_status", function (e) {
        searchEmployeeTicketList(e);
    });

    function searchEmployeeTicketList(e) {
        e.preventDefault();
        $("#employee-ticket-search-asset").submit();
    }

    // datatable

    $.fn.serializeObject = function () {
        var obj = {};
        $.each(this.serializeArray(), function (i, o) {
            var n = o.name,
                v = o.value;

            obj[n] =
                obj[n] === undefined
                    ? v
                    : $.isArray(obj[n])
                    ? obj[n].concat(v)
                    : [obj[n], v];
        });
        return obj;
    };

    var assets_table = $(".assetsTable").DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        ordering: true,
        pageLength: 25,
        ajax: {
            url: "asset-search",
            data: function (result) {
                result.filter = $("#search-asset").serializeObject();
            },
        },
        columns: [
            { data: "assetName", orderable: false },
            { data: "assetType", orderable: false },
            { data: "employeeName", orderable: false },
            { data: "assetSerialNo", orderable: true },
            { data: "assetDOP", orderable: true },
            { data: "assetValue", orderable: true },
            { data: "assetDepreciationValue", orderable: true },
            { data: "assetStatus", orderable: true },
            { data: "action", orderable: false },
        ],
        initComplete: function (settings, json) {
            showDepreciatedAssetTotalValue(json.totalAssetDepreciatedValue);
            updateFilteredTotalValue(json.filteredAssetValue);
            calculatePageTotalValue();
        },
        drawCallback: function (settings, json) {
            var api = this.api();
            var json = api.ajax.json();
            showDepreciatedAssetTotalValue(json.totalAssetDepreciatedValue);
            updateFilteredTotalValue(json.filteredAssetValue);
            calculatePageTotalValue();
        },
    });

    function showDepreciatedAssetTotalValue(totalAssetDepreciatedValue) {
        $(".assetsTable .total_asset_value").text(
            formatCurrency(totalAssetDepreciatedValue)
        );
    }

    function updateFilteredTotalValue(filteredTotal) {
        $(".assetsTable .total_filtered_value").text(
            formatCurrency(filteredTotal)
        );
    }

    // Function to calculate total value of the displaying rows
    function calculatePageTotalValue() {
        let total = 0;
        let depreciatedTotal = 0;
        assets_table.rows().every(function () {
            const data = this.data();
            const assetValue = parseFloat(data.assetValue.replace(/,/g, ""));
            if (!isNaN(assetValue)) {
                total += assetValue;
            }
            const depreciatedAssetValue = parseFloat(
                data.assetDepreciationValue.replace(/,/g, "")
            );
            if (!isNaN(depreciatedAssetValue)) {
                depreciatedTotal += depreciatedAssetValue;
            }
        });
        $(".assetsTable .value").text(formatCurrency(total));
        $(".assetsTable .depreciated_value").text(
            formatCurrency(depreciatedTotal)
        );
    }

    // Function to format currency
    function formatCurrency(amount) {
        return "INR " + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
    }

    $("#search-asset").submit(function (e) {
        e.preventDefault();
        assets_table.draw();
    });
});

function loadIchecks() {
    $(".i-checks").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
    });
}

function inputsLoader() {
    $(".datetimepicker").not("#warranty_id, #purchased_date").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
    });

    $(".chosen-select").chosen({
        width: "100%",
    });
    $(".files").dataTable();

    $(".summernote").summernote({
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

    $(".i-checks").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
    });

    loadDateRangeFilter();

    $('input[name="daterange"]').on(
        "apply.daterangepicker",
        function (ev, picker) {
            $(this).val(
                picker.startDate.format("MM/DD/YYYY") +
                    " - " +
                    picker.endDate.format("MM/DD/YYYY")
            );
        }
    );

    $('input[name="daterange"]').on(
        "cancel.daterangepicker",
        function (ev, picker) {
            $(this).val("");
            picker.setStartDate(moment());
            picker.setEndDate(moment());
        }
    );
}

function loadDateRangeFilter() {
    $("#daterange").daterangepicker(
        {
            opens: "left",
            locale: {
                format: "MMM DD, YYYY",
            },
            ranges: {
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
                "Last 3 Months": [
                    moment().subtract(3, "months").startOf("month"),
                    moment().endOf("month"),
                ],
                "Last 6 Months": [
                    moment().subtract(6, "months").startOf("month"),
                    moment().endOf("month"),
                ],
            },
            locale: {
                cancelLabel: "Clear",
            },
            autoUpdateInput: false, // Disable automatic input update
        },
        cb
    );
}

function cb(start, end) {
    $("#daterange").val(
        start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
    );
    $("#search-asset").submit();
}

function getAssetDocuments() {
    $.ajax({
        type: "POST",
        url: "/get-asset-documents",
        data: {
            asset_id: $("#asset-id").attr("data-id"),
        },
        success: function (response) {
            $(".documents-assets-div").html(response.data);
        },
    });
}

$(document).ready(function () {
    $("#purchased_date")
        .datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            endDate: "+0d",
            autoclose: true,
        })
        .on("changeDate", function (selected) {
            var purchased = new Date(selected.date);
            var warranty = new Date(
                $("#warranty_id").datepicker("getDate") || ""
            );

            $("#purchased_date").datepicker("setEndDate", new Date());

            if (purchased > warranty) {
                $("#purchased_date").val("");
                toastr.warning(
                    "The purchase date must precede the warranty date",
                    "Warning"
                );
            } else {
                purchased = moment(purchased).format("DD/MM/YYYY");
                $("#warranty_id").datepicker("setStartDate", purchased);
            }
        });

    $("#warranty_id")
        .datepicker({
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true,
        })
        .on("changeDate", function (selected) {
            var warranty = new Date(selected.date);
            var purchased = new Date(
                $("#purchased_date").datepicker("getDate") || ""
            );
            if (purchased > warranty) {
                $("#warranty_id").datepicker("setDate", null).val("");
                purchased = moment(purchased).format("DD/MM/YYYY");
                $("#warranty_id").datepicker("setStartDate", purchased);
            }
        });
});

$(document).on("change", ".asset-status", function () {
    var assetStatus = $(this).val();
    toggleUserList(assetStatus);
});

function toggleUserList(status) {
    var assignUserDiv = $(".assign-user");

    if (status === "allocated") {
        assignUserDiv.show(); // Display the div
    } else {
        assignUserDiv.hide(); // Hide the div
    }
}

$(document).on("click", "#export-assets", function (e) {
    e.preventDefault();
    openLoader();
    var formData = $("#search-asset").serializeObject();
    $.ajax({
        type: "POST",
        url: "/export-excel-assets",
        data: formData,
        xhrFields: {
            responseType: "blob",
        },
        success: function (response) {
            var filename = "Assets.xlsx";
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

$("body").on("click", "#filter-section", function () {
    $(".filter-area").toggle();
});
