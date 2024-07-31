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
            console.log("Global hidden.bs.modal fire");
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
            $("#create_asset_type").modal("hide");
            $("#edit_asset_type").modal("hide");
            $("#delete_asset_type").modal("hide");
        }
    });

    /**
     * Create asset type - submit button action
     */
    $(".create-asset-type").click(function (e) {
        //document.getElementById('create_asset').reset();
        toasterOption();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $(".field-error").html("");
        e.preventDefault();

        $.ajax({
            method: "POST",
            url: $("#add_asset_type_form").attr("action"),
            data: $("#add_asset_type_form").serialize(),
            success: function (response) {
                $(".overlay").remove();
                $("#create_asset_type").modal("hide");
                toastr.success(response.message, "Saved");
                setTimeout(window.location.reload(), 2000);
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#add_asset_type_form #label_" + field).html(error);
                    });
                }
            },
        });
    });

    /**
     * Asset Type edit form loading when clicking edit
     */
    $(document).on("click", ".edit-asset-type", function (e) {
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var assetTypeId = $(this).data("id");
        var editUrl = "/asset-types/" + assetTypeId + "/edit";

        $.ajax({
            type: "GET",
            url: editUrl,
            data: {},
            success: function (data) {
                $("#edit_asset_type").html(data);
                inputsLoader();
                loadIchecks();
                $(".overlay").remove();
                $("#edit_asset_type").modal("show");
            },
        });
    });

    /**
     * Edit asset type form - submit button action
     */
    $(document).on("click", ".update-asset-type", function (e) {
        toasterOption();
        $(".field-error").html("");
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $("#edit_asset_type_form").attr("action"),
            data: $("#edit_asset_type_form").serialize(),
            success: function (response) {
                $(".overlay").remove();
                toastr.success(response.message, "Updated");
                $("#edit_asset_type").modal("hide");
                $(".main").html(response.data);
                $(".chosen-select").val("").trigger("chosen:updated");
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#edit_asset_type_form #label_" + field).html(error);
                    });
                }
            },
        });
    });

    $(document).on("click", ".delete-asset-type", function () {
        var deleteAssetTypeId = $(this).data("id");
        $("#delete_asset_type #delete_asset_type_id").val(deleteAssetTypeId);
    });

    /**
     * Delete asset - button click action
     */
    $(document).on("click", "#delete_asset_type .continue-btn", function () {
        var deleteAssetTypeId = $(
            "#delete_asset_type #delete_asset_type_id"
        ).val();
        deleteUrl = "/asset-types/" + deleteAssetTypeId;
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $("#delete_asset_type").modal("hide");
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
                    setTimeout(loadAssetTypes, 2000);
                } else {
                    toastr.error(response.message, "Failed");
                }
            },
            error: function (error) {},
        });
    });

    function loadAssetTypes() {
        window.location = "/asset-types";
    }
});

function loadIchecks() {
    $(".i-checks").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
    });
}

function inputsLoader() {
    $(".chosen-select").chosen({
        width: "100%",
    });
}

$(document).on("change", ".asset-type", function () {
    var assetTypeId = $(this).val();
    $(".asset-type-attributes").html(
        '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
    );
    getAssetTypeAttributes(assetTypeId);
});

function getAssetTypeAttributes(id) {
    $.ajax({
        type: "get",
        url: "/asset-type-attributes",
        data: {
            assetTypeId: id,
        },
        success: function (response) {
            $(".asset-type-attributes").html(response.data);
            inputsLoader();
        },
    });
}
