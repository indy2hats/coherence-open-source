function inputsLoader() {
    $(".chosen-select").chosen({
        width: "100%",
    });
}

$(document).on("click", ".save-attribute", function (e) {
    e.preventDefault();
    $(".field-error").html("");
    $("body").append(
        '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
    );
    var data = new FormData($("#add_attributes_form")[0]);
    $.ajax({
        url: $("#add_attributes_form").attr("action"),
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        type: "POST",

        success: function (response) {
            removeOverlay();
            $(".input-value").val("");
            showAttributeList(response.data);
            showAttributeInMainList(response.data);
            const newRow = createNewAttributeRow();
            $(".new-attribute-value-list").html("");
            $(".new-attribute-value-list").append(newRow);
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

function showAttributeList(attribute) {
    const attributeListBody = $(".attribute-list");
    // attributeListBody.empty();
    $(".attribute-list .default").remove();
    if (attribute) {
        const row = `<tr>
                        <td colspan="2">${attribute.name}</td>
                        <td colspan="8">${attribute.values}</a></td>
                        
                        </tr>`;
        attributeListBody.append(row);
    } else {
        const noAttributeRow = `<tr>
                                <td colspan="10" align="center">No Attributes Found</td>
                            </tr>`;
        attributeListBody.append(noAttributeRow);
    }
}

function showAttributeInMainList(attribute) {
    const attributeListBody = $(".attribute-full-list");
    $(".attribute-full-list .default").remove();

    const row = `<tr>
                    <td>${attribute.name}</td>
                    <td>${attribute.values}</a></td>
                    <td><a class="dropdown-item edit-attribute" href="#" data-id="${attribute.id}" title="Edit"><i class='ri-pencil-line m-r-5'></i></a>
                    <a class="dropdown-item delete-attribute" href="#" data-id="${attribute.id}" title="Delete"><i class='ri-delete-bin-line m-r-5'></i></a></td>
                </tr>`;
    attributeListBody.append(row);
}

function removeOverlay() {
    $("body .overlay").each(function () {
        $(this).remove();
    });
}

$(document).on("click", ".edit-attribute", function (e) {
    e.preventDefault();
    $("body").append(
        '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
    );
    var attributeId = $(this).data("id");
    var editUrl = "/attributes/" + attributeId + "/edit";

    $.ajax({
        type: "GET",
        url: editUrl,
        data: {},
        success: function (data) {
            $("#edit_attribute").html(data);
            $(".overlay").remove();
            $("#edit_attribute").modal("show");
        },
    });
});

$(document).ready(function () {
    $(document).on("click", ".edit-name-attribute", function (e) {
        $("#label_edit_name").html("");
        e.preventDefault();
        var $row = $(this).closest(".row");
        var $editable = $row.find(".editable");
        var attributeId = $editable.data("attribute-id");
        // Create an input field
        var $inputField = $("<input>", {
            type: "text",
            class: "form-control", // Add Bootstrap form-control class or adjust as needed
        });

        // Replace the text with the input field
        $editable.empty().append($inputField);

        // Change the button to "Save"
        $(this)
            .find("i")
            .removeClass("ri-pencil-line")
            .addClass("ri-save-line");

        // Add click event for the "Save" button
        $(this)
            .off("click")
            .on("click", function () {
                updateAttribute(
                    attributeId,
                    $inputField.val(),
                    this,
                    $editable
                );
            });
    });
});

function updateAttribute(attributeId, updatedValue, self, objEditable) {
    $.ajax({
        url: "/attributes/" + attributeId,
        data: {
            name: updatedValue,
            type: "name",
        },
        type: "PUT",
        success: function (response) {
            objEditable.text(updatedValue);
            // objEditable.attr("data-attribute-name", updatedValue);

            // Change the button back to "Edit"
            $(self)
                .find("i")
                .removeClass("ri-save-line")
                .addClass("ri-pencil-line");

            // Reattach the click event for "Edit"
            $(self)
                .off("click")
                .on("click", function (e) {
                    e.preventDefault();
                    // Handle edit logic here
                });
            var updatedRow = "#attribute-name-" + attributeId;
            $(updatedRow).text(updatedValue);
        },
        error: function (error) {
            removeOverlay();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function (field, error) {
                    $("#label_edit_" + field).html(error);
                });
            }
        },
    });
}

$(document)
    .off("click", ".add-attribute-value")
    .on("click", ".add-attribute-value", function (e) {
        e.preventDefault();
        var attributeId = $(this).data("id");
        const newRow = `
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <input type="text" class="form-control mb-2" name="attribute_value" id="attribute_value" placeholder="New Attribute Value">
        </div>
        <div class="col-sm-3 col-md-3">
            <a class="save-attribute-value" href="#"  data-attribute-id="${attributeId}" title="Save"><i class="ri-save-line m-r-5"></a></i>
        </div>
    </div> <div class="row text-danger text-left field-error" id="label_value"></div>`;

        $(".attribute-value-list").append(newRow);
        $(this).remove();

        $(".attribute-value-list").find("input").focus();
    });

$(document)
    .off("click", ".save-attribute-value")
    .on("click", ".save-attribute-value", function (e) {
        e.stopPropagation();
        e.preventDefault();
        var id = $(this).data("attribute-id");
        var newValue = $(this)
            .closest(".row")
            .find("input[name='attribute_value']")
            .val();
        $.ajax({
            url: "/attributes/" + id,
            data: {
                value: newValue,
                type: "value",
            },
            type: "PUT",
            success: function (response) {
                showAttributeValuesList(response.data);
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

function showAttributeValuesList(data) {
    var attributeValues = data.attributeValues;
    var attributeId = data.attributeId;
    const attributeValueListBody = $(".attribute-value-list");
    attributeValueListBody.empty();
    let row = "";
    let attributeValueString = "";
    if (attributeValues) {
        attributeValues.forEach((attribute_value, index) => {
            attributeValueString += attribute_value.value + ", ";

            row += `
                <div class="row" id="row-value-${attribute_value.id}"> 
                    <div class="col-sm-6 col-md-6 editable" data-attribute-id="${attribute_value.id}" data-attribute-value="${attribute_value.value}">
                        ${attribute_value.value}
                    </div>
                    <div class="col-sm-3 col-md-3">`;
            if (attributeValues.length > 1) {
                row += `<a class="dropdown-item delete-attribute-value" href="#" data-toggle="modal"
                            data-target="#delete_attribute_value" data-id="${attribute_value.id}" data-tooltip="tooltip"
                            data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>`;
            }
            if (index + 1 === attributeValues.length) {
                row += `
                        <a href="#" class="add-attribute-value" data-target="#add_attribute_value" data-id="${attribute_value.attribute_id}"
                            data-tooltip="tooltip" title="Add values" data-toggle="modal"> 
                            <i class="ri-add-line m-r-5"></i>
                        </a>`;
            }

            row += `
                    </div>
                </div>`;
        });
        attributeValueListBody.append(row);
        var updatedRow = "#attribute-values-" + attributeId;
        attributeValueString = attributeValueString.slice(0, -2);
        $(updatedRow).text(attributeValueString);
    } else {
        const noRow = `<tr>
                                <td colspan="10" align="center">No Values Found</td>
                            </tr>`;
        attributeValueListBody.append(noRow);
    }
}

$(document).on("click", ".delete-attribute", function (e) {
    var attributeId = $(this).data("id");
    var $currentRow = $(this).closest("tr");
    Swal.fire({
        icon: "warning",
        title: "Are you sure you want to delete this attribute ?",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            openLoader();
            $.ajax({
                type: "DELETE",
                url: "/attributes/" + attributeId,
                data: {},
                success: function (response) {
                    closeLoader();
                    toastr.success(
                        response.message,
                        "Deleted attributes successfully"
                    );
                    $currentRow.remove();
                    //showAttributeValuesList(response.data);
                },
                error: function (error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        toastr.error(
                            response.message,
                            "Couldn't delete attribute !"
                        );
                    }
                },
            });
        }
    });
});

$(document).on("click", ".delete-attribute-value", function (e) {
    var attributeValueId = $(this).data("id");
    var attributeId = $("#attribute-id").val();
    var currentRow = "#row-value-" + attributeValueId;

    $.ajax({
        type: "DELETE",
        url: "/delete-attribute-value/" + attributeValueId,

        success: function (response) {
            closeLoader();
            toastr.success(
                response.message,
                "Deleted attribute values successfully"
            );
            $(currentRow).remove();
            showAttributeValuesList(response.data);
        },
        error: function (error) {
            closeLoader();
            if (error.responseJSON.errors) {
                toastr.error(response.message, "Couldn't delete attribute !");
            }
        },
    });
});

$(document).on("click", ".delete-attribute-value-withSwal", function (e) {
    var attributeValueId = $(this).data("id");
    var attributeId = $("#attribute-id").val();
    Swal.fire({
        icon: "warning",
        title: "Are you sure you want to delete this value ?",
        showCancelButton: true,
        confirmButtonText: "Yes, ashak delete it!",
        customClass: "custom-swal",
    }).then((result) => {
        if (result.isConfirmed) {
            openLoader();
            $.ajax({
                type: "DELETE",
                url: "/delete-attribute-value/" + attributeValueId,
                data: {
                    attributeId: attributeId,
                },
                success: function (response) {
                    closeLoader();
                    toastr.success(
                        response.message,
                        "Deleted attribute values successfully"
                    );
                },
                error: function (error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        toastr.error(
                            response.message,
                            "Couldn't delete attribute !"
                        );
                    }
                },
            });
        }
    });
});

$(document)
    .off("click", ".create-attribute-value")
    .on("click", ".create-attribute-value", function (e) {
        e.preventDefault();
        const newRow = createNewAttributeRow();
        $(".new-attribute-value-list").append(newRow);
        $(this).remove();
        $(".new-attribute-value-list").find("input").focus();
    });

function createNewAttributeRow() {
    const newRow = `
    <div class="row" style="margin-bottom: 5px;">
        <div class="col-sm-10">
            <div class="input-group" style="width: 100%;">
                <input type="text" class="form-control  input-value" type="text" name="values[]" id="values" placeholder="Enter values">
                <div class="text-danger text-left field-error" id="label_values"></div>
            </div>
        </div>
        <div class="col-sm-2"> 
            <a class="dropdown-item delete-new-attribute-value" href="#" data-toggle="modal"
                data-target="#delete_new-attribute_value" data-tooltip="tooltip"
                data-placement="top" title="Delete"><i class="ri-delete-bin-line m-r-5"></i></a>   
            <a href="#" class="create-attribute-value" data-target="#create_attribute_value"
                    data-tooltip="tooltip" data-toggle="modal"> 
                    <i class="ri-add-line m-r-5"></i>
                </a>
            </div>
    </div>`;
    return newRow;
}

$(document).on("click", ".delete-new-attribute-value", function (e) {
    const $rowToDelete = $(this).closest(".row");

    if ($(".new-attribute-value-list .row").length > 1) {
        $rowToDelete.remove();

        const $lastRow = $(".new-attribute-value-list .row").last();
        $lastRow.find(".attribute-actions").html(`
            <a href="#" class="delete-new-attribute-value" data-toggle="modal"
                data-target="#delete_new-attribute_value" data-tooltip="tooltip"
                data-placement="top" title="Delete">
                <i class="ri-delete-bin-line m-r-5"></i>
            </a>
            <a href="#" class="create-attribute-value" data-target="#create_attribute_value"
                data-tooltip="tooltip" data-toggle="modal"> 
                <i class="ri-add-line m-r-5"></i>
            </a>
        `);
    }
});

// Initial setup: Add delete and create icons to the first row
$(".new-attribute-value-list .row").first().find(".attribute-actions").html(`
    <a href="#" class="delete-new-attribute-value" data-toggle="modal"
        data-target="#delete_new-attribute_value" data-tooltip="tooltip"
        data-placement="top" title="Delete">
        <i class="ri-delete-bin-line m-r-5"></i>
    </a>
    <a href="#" class="create-attribute-value" data-target="#create_attribute_value"
        data-tooltip="tooltip" data-toggle="modal"> 
        <i class="ri-add-line m-r-5"></i>
    </a>
`);
