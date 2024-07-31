$(document).ready(function () {
    inputsLoader();

    $(document).keydown(function (e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $("#add_client").modal("hide");
            $("#edit_client").modal("hide");
            $("#delete_client").modal("hide");
        }
    });

    /**
     * Create client form - submit buttom action
     */
    $(document).on("click", ".create-client", function (e) {
        $(".field-error").html("");
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var data = new FormData($("#add_employee_form")[0]);
        $.ajax({
            type: "POST",
            url: $("#add_client_form").attr("action"),
            data: new FormData($("#add_client_form")[0]),
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".overlay").remove();
                $("#add_client").modal("hide");
                toastr.success(response.message, "Saved");
                getClientList();
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

    /**
     * Removing validation errors and reset form on model window close
     */
    $("#add_client").on("hidden.bs.modal", function () {
        $(this).find(".text-danger").html("");
        $("#add_client_form").trigger("reset");
    });

    /**
     * Loading edit client form with data to edit modal
     */
    $(document).on("click", ".edit-client", function () {
        var clientId = $(this).data("id");
        editUrl = "/clients/" + clientId + "/edit";
        openLoader();
        $.ajax({
            method: "GET",
            url: editUrl,
            data: {},
            success: function (response) {
                $(".overlay").remove();
                $("#edit_client").html(response);
                $("#edit_client").modal("show");
                //countryList();
                //currencyList();
                inputsLoader();
            },
        });
    });

    /**
     * Loading view client form with data to view modal
     */

    $(document).on("click", ".view-client", function () {
        var clientId = $(this).data("id");
        showUrl = "/clients/" + clientId;
        $("#view_client").modal("show");
        $.ajax({
            method: "GET",
            url: showUrl,
            data: {},
            success: function (response) {
                $("#view_client").html(response);
                $("#view_client").modal("show");
                inputsLoader();
                getTypheadDataClient();
            },
        });
    });

    /** to update on enter key */
    $(document).on("keyup", "#edit_client_form", function (event) {
        if (event.keyCode === 13) {
            $(".edit_client").click();
        }
    });

    /**
     * Update client form - submit button action
     */
    $(document).on("click", ".update-client", function (e) {
        $(".field-error").html("");
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var data = new FormData($("#edit_client_form")[0]);
        $.ajax({
            type: "POST",
            url: $("#edit_client_form").attr("action"),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(".overlay").remove();
                $("#edit_client").modal("hide");
                toastr.success(response.message, "Updated");
                getClientList();
            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $("#label_edit_" + field).html(error);
                    });
                }
            },
        });
    });

    /**
     * Adding client id to hidden text field in delete model
     */
    $(document).on("click", ".delete-client", function () {
        var deleteClientId = $(this).data("id");
        $("#delete_client #delete_client_id").val(deleteClientId);
    });

    /**
     * Delete model continue button action
     */
    $(document).on("click", "#delete_client .continue-btn", function () {
        var deleteClientId = $("#delete_client #delete_client_id").val();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $("#delete_client").modal("hide");
        $.ajax({
            method: "DELETE",
            url: "/clients/" + deleteClientId,
            data: {},
            success: function (response) {
                $(".overlay").remove();
                toastr.success(response.message, "Deleted");
                getClientList();
            },
            error: function (error) {
                $(".overlay").remove();
                toastr.warning(
                    "Delete the Projects before you delete company",
                    "Warning"
                );
            },
        });
    });

    /**
     * Search button action
     */
    var timer;

    jQuery(document).on("change", ".search-client", function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            if (
                $(".search-client").val().length < 4 &&
                $(".search-client").val().length > 0
            )
                return;
            getClientList();
        }, 300);
    });

    jQuery(document).on("click", ".search-button", function () {
        getClientList();
    });

    jQuery(document).on(
        "change",
        "#country, #account-manager-id",
        function (e) {
            getClientList();
        }
    );

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
});

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
    getClientList();
}

/**
 * Function to display client grid
 */
function getClientList() {
    var clientCompany = $(".search-client").val();
    var country = $("#country").val();
    var accountManagerId = $("#account-manager-id").val();
    var dateRange = $("#daterange").val();

    $.ajax({
        method: "POST",
        url: "/get-client-grid",
        data: {
            company: clientCompany,
            country: country,
            accountManagerId: accountManagerId,
            dateRange: dateRange,
        },
        success: function (response) {
            $(".grid").html(response.data);
            inputsLoader();
            $(".search-client").focus().val("").val(clientCompany);
            $("#country").focus().val("").val(country);
            $("#account-manager-id").focus().val("").val(accountManagerId);
            $("#daterange").val(dateRange);
            loadDateRangeFilter();
        },
    });
}

$(document).on("click", ".filter-reset", function (e) {
    e.preventDefault();
    openLoader();
    $(".search-client").val("");
    $("#country").val("").trigger("chosen:updated");
    $("#account-manager-id").val("").trigger("chosen:updated");
    $("#daterange").val("");
    loadDateRangeFilter();
    getClientList();
    closeLoader();
});

function view(id) {
    $("body").append(
        '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
    );
    $.ajax({
        method: "POST",
        url: "/get-single-client",
        data: {
            companyId: id,
        },
        success: function (response) {
            $(".viewDetails").html(response.data);
            $(".overlay").remove();
            $("html, body").animate({ scrollTop: 0 }, "slow");
            typeAhead();
        },
    });
}

function typeAhead() {
    $.get(
        "get-typhead-data-client",
        function (response) {
            var name = [];
            for (var i = response.data.length - 1; i >= 0; i--) {
                name.push(response.data[i]["company_name"]);
            }
            $(".typeahead_name").typeahead({
                source: name,
            });
        },
        "json"
    );
}

function inputsLoader() {
    typeAhead();
    countryList();
    currencyList();
    $(".datetimepicker").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "yyyy-mm-dd",
        autoclose: true,
    });
    $(".chosen-select").chosen({
        width: "100%",
    });
}

function currencyList() {
    $(".typeahead_4").typeahead({
        source: [
            "AED",
            "AFN",
            "ALL",
            "AMD",
            "ANG",
            "AOA",
            "ARS",
            "AUD",
            "AWG",
            "AZN",
            "BAM",
            "BBD",
            "BDT",
            "BGN",
            "BHD",
            "BIF",
            "BMD",
            "BND",
            "BOB",
            "BRL",
            "BSD",
            "BTC",
            "BTN",
            "BWP",
            "BYN",
            "BZD",
            "CAD",
            "CDF",
            "CHF",
            "CLF",
            "CLP",
            "CNH",
            "CNY",
            "COP",
            "CRC",
            "CUC",
            "CUP",
            "CVE",
            "CZK",
            "DJF",
            "DKK",
            "DOP",
            "DZD",
            "EGP",
            "ERN",
            "ETB",
            "EUR",
            "FJD",
            "FKP",
            "GBP",
            "GEL",
            "GGP",
            "GHS",
            "GIP",
            "GMD",
            "GNF",
            "GTQ",
            "GYD",
            "HKD",
            "HNL",
            "HRK",
            "HTG",
            "HUF",
            "IDR",
            "ILS",
            "IMP",
            "INR",
            "IQD",
            "IRR",
            "ISK",
            "JEP",
            "JMD",
            "JOD",
            "JPY",
            "KES",
            "KGS",
            "KHR",
            "KMF",
            "KPW",
            "KRW",
            "KWD",
            "KYD",
            "KZT",
            "LAK",
            "LBP",
            "LKR",
            "LRD",
            "LSL",
            "LYD",
            "MAD",
            "MDL",
            "MGA",
            "MKD",
            "MMK",
            "MNT",
            "MOP",
            "MRO",
            "MRU",
            "MUR",
            "MVR",
            "MWK",
            "MXN",
            "MYR",
            "MZN",
            "NAD",
            "NGN",
            "NIO",
            "NOK",
            "NPR",
            "NZD",
            "OMR",
            "PAB",
            "PEN",
            "PGK",
            "PHP",
            "PKR",
            "PLN",
            "PYG",
            "QAR",
            "RON",
            "RSD",
            "RUB",
            "RWF",
            "SAR",
            "SBD",
            "SCR",
            "SDG",
            "SEK",
            "SGD",
            "SHP",
            "SLL",
            "SOS",
            "SRD",
            "SSP",
            "STD",
            "STN",
            "SVC",
            "SYP",
            "SZL",
            "THB",
            "TJS",
            "TMT",
            "TND",
            "TOP",
            "TRY",
            "TTD",
            "TWD",
            "TZS",
            "UAH",
            "UGX",
            "USD",
            "UYU",
            "UZS",
            "VEF",
            "VND",
            "VUV",
            "WST",
            "XAF",
            "XAG",
            "XAU",
            "XCD",
            "XDR",
            "XOF",
            "XPD",
            "XPF",
            "XPT",
            "YER",
            "ZAR",
            "ZMW",
            "ZWL",
        ],
    });
}

function countryList() {
    //countries
    $(".typeahead_3").typeahead({
        source: [
            "Afghanistan",
            "Albania",
            "Algeria",
            "American Samoa",
            "Andorra",
            "Angola",
            "Anguilla",
            "Antarctica",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Aruba",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas (the)",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bermuda",
            "Bhutan",
            "Bolivia (Plurinational State of)",
            "Bonaire, Sint Eustatius and Saba",
            "Bosnia and Herzegovina",
            "Botswana",
            "Bouvet Island",
            "Brazil",
            "British Indian Ocean Territory (the)",
            "Brunei Darussalam",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cabo Verde",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cayman Islands (the)",
            "Central African Republic (the)",
            "Chad",
            "Chile",
            "China",
            "Christmas Island",
            "Cocos (Keeling) Islands (the)",
            "Colombia",
            "Comoros (the)",
            "Congo (the Democratic Republic of the)",
            "Congo (the)",
            "Cook Islands (the)",
            "Costa Rica",
            "Croatia",
            "Cuba",
            "Curaçao",
            "Cyprus",
            "Czechia",
            "Côte d'Ivoire",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic (the)",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Eswatini",
            "Ethiopia",
            "Falkland Islands (the) [Malvinas]",
            "Faroe Islands (the)",
            "Fiji",
            "Finland",
            "France",
            "French Guiana",
            "French Polynesia",
            "French Southern Territories (the)",
            "Gabon",
            "Gambia (the)",
            "Georgia",
            "Germany",
            "Ghana",
            "Gibraltar",
            "Greece",
            "Greenland",
            "Grenada",
            "Guadeloupe",
            "Guam",
            "Guatemala",
            "Guernsey",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Heard Island and McDonald Islands",
            "Holy See (the)",
            "Honduras",
            "Hong Kong",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran (Islamic Republic of)",
            "Iraq",
            "Ireland",
            "Isle of Man",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jersey",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea (the Democratic People's Republic of)",
            "Korea (the Republic of)",
            "Kuwait",
            "Kyrgyzstan",
            "Lao People's Democratic Republic (the)",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macao",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands (the)",
            "Martinique",
            "Mauritania",
            "Mauritius",
            "Mayotte",
            "Mexico",
            "Micronesia (Federated States of)",
            "Moldova (the Republic of)",
            "Monaco",
            "Mongolia",
            "Montenegro",
            "Montserrat",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands (the)",
            "New Caledonia",
            "New Zealand",
            "Nicaragua",
            "Niger (the)",
            "Nigeria",
            "Niue",
            "Norfolk Island",
            "Northern Mariana Islands (the)",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Palestine, State of",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines (the)",
            "Pitcairn",
            "Poland",
            "Portugal",
            "Puerto Rico",
            "Qatar",
            "Republic of North Macedonia",
            "Romania",
            "Russian Federation (the)",
            "Rwanda",
            "Réunion",
            "Saint Barthélemy",
            "Saint Helena, Ascension and Tristan da Cunha",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Martin (French part)",
            "Saint Pierre and Miquelon",
            "Saint Vincent and the Grenadines",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Sint Maarten (Dutch part)",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "South Georgia and the South Sandwich Islands",
            "South Sudan",
            "Spain",
            "Sri Lanka",
            "Sudan (the)",
            "Suriname",
            "Svalbard and Jan Mayen",
            "Sweden",
            "Switzerland",
            "Syrian Arab Republic",
            "Taiwan (Province of China)",
            "Tajikistan",
            "Tanzania, United Republic of",
            "Thailand",
            "Timor-Leste",
            "Togo",
            "Tokelau",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Turks and Caicos Islands (the)",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates (the)",
            "United Kingdom of Great Britain and Northern Ireland (the)",
            "United States Minor Outlying Islands (the)",
            "United States of America (the)",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Venezuela (Bolivarian Republic of)",
            "Viet Nam",
            "Virgin Islands (British)",
            "Virgin Islands (U.S.)",
            "Wallis and Futuna",
            "Western Sahara",
            "Yemen",
            "Zambia",
            "Zimbabwe",
            "Åland Islands",
        ],
    });
}

(function (root, factory) {
    "use strict";
    if (typeof module !== "undefined" && module.exports) {
        module.exports = factory(require("jquery"));
    } else if (typeof define === "function" && define.amd) {
        define(["jquery"], function ($) {
            return factory($);
        });
    } else {
        factory(root.jQuery);
    }
})(this, function ($) {
    "use strict";
    var Typeahead = function (element, options) {
        this.$element = $(element);
        this.options = $.extend({}, $.fn.typeahead.defaults, options);
        this.matcher = this.options.matcher || this.matcher;
        this.sorter = this.options.sorter || this.sorter;
        this.select = this.options.select || this.select;
        this.autoSelect =
            typeof this.options.autoSelect == "boolean"
                ? this.options.autoSelect
                : true;
        this.highlighter = this.options.highlighter || this.highlighter;
        this.render = this.options.render || this.render;
        this.updater = this.options.updater || this.updater;
        this.displayText = this.options.displayText || this.displayText;
        this.source = this.options.source;
        this.delay = this.options.delay;
        this.$menu = $(this.options.menu);
        this.$appendTo = this.options.appendTo
            ? $(this.options.appendTo)
            : null;
        this.shown = false;
        this.listen();
        this.showHintOnFocus =
            typeof this.options.showHintOnFocus == "boolean"
                ? this.options.showHintOnFocus
                : false;
        this.afterSelect = this.options.afterSelect;
        this.addItem = false;
    };
    Typeahead.prototype = {
        constructor: Typeahead,
        select: function () {
            var val = this.$menu.find(".active").data("value");
            this.$element.data("active", val);
            if (this.autoSelect || val) {
                var newVal = this.updater(val);
                if (!newVal) {
                    newVal = "";
                }
                this.$element.val(this.displayText(newVal) || newVal).change();
                this.afterSelect(newVal);
            }
            return this.hide();
        },
        updater: function (item) {
            return item;
        },
        setSource: function (source) {
            this.source = source;
        },
        show: function () {
            var pos = $.extend({}, this.$element.position(), {
                    height: this.$element[0].offsetHeight,
                }),
                scrollHeight;
            scrollHeight =
                typeof this.options.scrollHeight == "function"
                    ? this.options.scrollHeight.call()
                    : this.options.scrollHeight;
            var element;
            if (this.shown) {
                element = this.$menu;
            } else if (this.$appendTo) {
                element = this.$menu.appendTo(this.$appendTo);
            } else {
                element = this.$menu.insertAfter(this.$element);
            }
            element
                .css({
                    top: pos.top + pos.height + scrollHeight,
                    left: pos.left,
                })
                .show();
            this.shown = true;
            return this;
        },
        hide: function () {
            this.$menu.hide();
            this.shown = false;
            return this;
        },
        lookup: function (query) {
            var items;
            if (typeof query != "undefined" && query !== null) {
                this.query = query;
            } else {
                this.query = this.$element.val() || "";
            }
            if (
                this.query.length < this.options.minLength &&
                !this.options.showHintOnFocus
            ) {
                return this.shown ? this.hide() : this;
            }
            var worker = $.proxy(function () {
                if ($.isFunction(this.source))
                    this.source(this.query, $.proxy(this.process, this));
                else if (this.source) {
                    this.process(this.source);
                }
            }, this);
            clearTimeout(this.lookupWorker);
            this.lookupWorker = setTimeout(worker, this.delay);
        },
        process: function (items) {
            var that = this;
            items = $.grep(items, function (item) {
                return that.matcher(item);
            });
            items = this.sorter(items);
            if (!items.length && !this.options.addItem) {
                return this.shown ? this.hide() : this;
            }
            if (items.length > 0) {
                this.$element.data("active", items[0]);
            } else {
                this.$element.data("active", null);
            }
            if (this.options.addItem) {
                items.push(this.options.addItem);
            }
            if (this.options.items == "all") {
                return this.render(items).show();
            } else {
                return this.render(items.slice(0, this.options.items)).show();
            }
        },
        matcher: function (item) {
            var it = this.displayText(item);
            return ~it.toLowerCase().indexOf(this.query.toLowerCase());
        },
        sorter: function (items) {
            var beginswith = [],
                caseSensitive = [],
                caseInsensitive = [],
                item;
            while ((item = items.shift())) {
                var it = this.displayText(item);
                if (!it.toLowerCase().indexOf(this.query.toLowerCase()))
                    beginswith.push(item);
                else if (~it.indexOf(this.query)) caseSensitive.push(item);
                else caseInsensitive.push(item);
            }
            return beginswith.concat(caseSensitive, caseInsensitive);
        },
        highlighter: function (item) {
            var html = $("<div></div>");
            var query = this.query;
            var i = item.toLowerCase().indexOf(query.toLowerCase());
            var len, leftPart, middlePart, rightPart, strong;
            len = query.length;
            if (len === 0) {
                return html.text(item).html();
            }
            while (i > -1) {
                leftPart = item.substr(0, i);
                middlePart = item.substr(i, len);
                rightPart = item.substr(i + len);
                strong = $("<strong></strong>").text(middlePart);
                html.append(document.createTextNode(leftPart)).append(strong);
                item = rightPart;
                i = item.toLowerCase().indexOf(query.toLowerCase());
            }
            return html.append(document.createTextNode(item)).html();
        },
        render: function (items) {
            var that = this;
            var self = this;
            var activeFound = false;
            var data = [];
            var _category = that.options.separator;
            $.each(items, function (key, value) {
                if (key > 0 && value[_category] !== items[key - 1][_category]) {
                    data.push({ __type: "divider" });
                }
                if (
                    value[_category] &&
                    (key === 0 ||
                        value[_category] !== items[key - 1][_category])
                ) {
                    data.push({ __type: "category", name: value[_category] });
                }
                data.push(value);
            });
            items = $(data).map(function (i, item) {
                if ((item.__type || false) == "category") {
                    return $(that.options.headerHtml).text(item.name)[0];
                }
                if ((item.__type || false) == "divider") {
                    return $(that.options.headerDivider)[0];
                }
                var text = self.displayText(item);
                i = $(that.options.item).data("value", item);
                i.find("a").html(that.highlighter(text, item));
                if (text == self.$element.val()) {
                    i.addClass("active");
                    self.$element.data("active", item);
                    activeFound = true;
                }
                return i[0];
            });
            if (this.autoSelect && !activeFound) {
                items
                    .filter(":not(.dropdown-header)")
                    .first()
                    .addClass("active");
                this.$element.data("active", items.first().data("value"));
            }
            this.$menu.html(items);
            return this;
        },
        displayText: function (item) {
            return (
                (typeof item !== "undefined" &&
                    typeof item.name != "undefined" &&
                    item.name) ||
                item
            );
        },
        next: function (event) {
            var active = this.$menu.find(".active").removeClass("active"),
                next = active.next();
            if (!next.length) {
                next = $(this.$menu.find("li")[0]);
            }
            next.addClass("active");
        },
        prev: function (event) {
            var active = this.$menu.find(".active").removeClass("active"),
                prev = active.prev();
            if (!prev.length) {
                prev = this.$menu.find("li").last();
            }
            prev.addClass("active");
        },
        listen: function () {
            this.$element
                .on("focus", $.proxy(this.focus, this))
                .on("blur", $.proxy(this.blur, this))
                .on("keypress", $.proxy(this.keypress, this))
                .on("input", $.proxy(this.input, this))
                .on("keyup", $.proxy(this.keyup, this));
            if (this.eventSupported("keydown")) {
                this.$element.on("keydown", $.proxy(this.keydown, this));
            }
            this.$menu
                .on("click", $.proxy(this.click, this))
                .on("mouseenter", "li", $.proxy(this.mouseenter, this))
                .on("mouseleave", "li", $.proxy(this.mouseleave, this))
                .on("mousedown", $.proxy(this.mousedown, this));
        },
        mousedown: function (e) {
            this.mouseddown = true;
            e.stopPropagation();
            e.preventDefault();
        },
        destroy: function () {
            this.$element.data("typeahead", null);
            this.$element.data("active", null);
            this.$element
                .off("focus")
                .off("blur")
                .off("keypress")
                .off("input")
                .off("keyup");
            if (this.eventSupported("keydown")) {
                this.$element.off("keydown");
            }
            this.$menu.remove();
        },
        eventSupported: function (eventName) {
            var isSupported = eventName in this.$element;
            if (!isSupported) {
                this.$element.setAttribute(eventName, "return;");
                isSupported = typeof this.$element[eventName] === "function";
            }
            return isSupported;
        },
        move: function (e) {
            if (!this.shown) return;
            switch (e.keyCode) {
                case 9:
                case 13:
                case 27:
                    e.preventDefault();
                    break;
                case 38:
                    if (e.shiftKey) return;
                    e.preventDefault();
                    this.prev();
                    break;
                case 40:
                    if (e.shiftKey) return;
                    e.preventDefault();
                    this.next();
                    break;
            }
        },
        keydown: function (e) {
            this.suppressKeyPressRepeat = ~$.inArray(
                e.keyCode,
                [40, 38, 9, 13, 27]
            );
            if (!this.shown && e.keyCode == 40) {
                this.lookup();
            } else {
                this.move(e);
            }
        },
        keypress: function (e) {
            if (this.suppressKeyPressRepeat) return;
            this.move(e);
        },
        input: function (e) {
            this.lookup();
            e.preventDefault();
        },
        keyup: function (e) {
            switch (e.keyCode) {
                case 40:
                case 38:
                case 16:
                case 17:
                case 18:
                    break;
                case 9:
                case 13:
                    if (!this.shown) return;
                    this.select();
                    break;
                case 27:
                    if (!this.shown) return;
                    this.hide();
                    break;
            }
            e.preventDefault();
        },
        focus: function (e) {
            if (!this.focused) {
                this.focused = true;
                if (this.options.showHintOnFocus) {
                    this.lookup();
                }
            }
        },
        blur: function (e) {
            this.focused = false;
            if (!this.mousedover && this.shown) {
                if (this.mouseddown && e.originalEvent) {
                    this.mouseddown = false;
                } else {
                    this.hide();
                }
            }
        },
        click: function (e) {
            e.preventDefault();
            this.select();
            this.$element.focus();
            this.hide();
        },
        mouseenter: function (e) {
            this.mousedover = true;
            this.$menu.find(".active").removeClass("active");
            $(e.currentTarget).addClass("active");
        },
        mouseleave: function (e) {
            this.mousedover = false;
        },
    };
    var old = $.fn.typeahead;
    $.fn.typeahead = function (option) {
        var arg = arguments;
        if (typeof option == "string" && option == "getActive") {
            return this.data("active");
        }
        return this.each(function () {
            var $this = $(this),
                data = $this.data("typeahead"),
                options = typeof option == "object" && option;
            if (!data)
                $this.data("typeahead", (data = new Typeahead(this, options)));
            if (typeof option == "string" && data[option]) {
                if (arg.length > 1) {
                    data[option].apply(
                        data,
                        Array.prototype.slice.call(arg, 1)
                    );
                } else {
                    data[option]();
                }
            }
        });
    };
    $.fn.typeahead.defaults = {
        source: [],
        items: 8,
        menu: '<ul class="typeahead dropdown-menu" role="listbox"></ul>',
        item: '<li><a class="dropdown-item" href="#" role="option"></a></li>',
        minLength: 1,
        scrollHeight: 0,
        autoSelect: true,
        afterSelect: $.noop,
        addItem: false,
        delay: 0,
        separator: "category",
        headerHtml: '<li class="dropdown-header"></li>',
        headerDivider: '<li class="divider" role="separator"></li>',
    };
    $.fn.typeahead.Constructor = Typeahead;
    $.fn.typeahead.noConflict = function () {
        $.fn.typeahead = old;
        return this;
    };
    $(document).on(
        "focus.typeahead.data-api",
        '[data-provide="typeahead"]',
        function (e) {
            var $this = $(this);
            if ($this.data("typeahead")) return;
            $this.typeahead($this.data());
        }
    );
});
