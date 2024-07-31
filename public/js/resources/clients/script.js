

	$(document).ready(function() {

		inputsLoader();

		$(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#add_client').modal('hide');
                $('#edit_client').modal('hide');
                $('#delete_client').modal('hide');

            }
        });

		/** 
		 * Create client form - submit buttom action
		 */
		$(document).on('click', '.create-client', function(e) {
			$('.field-error').html('');
			e.preventDefault();
			$("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
			var data = new FormData($('#add_employee_form')[0]);
			$.ajax({
				type: 'POST',
				url: $('#add_client_form').attr('action'),
				data: new FormData($('#add_client_form')[0]),
				contentType: false,
				cache: false,
				processData: false,
				success: function(response) {
					$(".overlay").remove();
					$('#add_client').modal('hide');
					toastr.success(response.message, 'Saved');
					getClientList('');
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
		$('#add_client').on('hidden.bs.modal', function() {
			$(this).find('.text-danger').html('');
			$('#add_client_form').trigger('reset');
		});

		/** 
		 * Loading edit client form with data to edit modal
		 */
		$(document).on('click', '.edit-client', function() {
			var clientId = $(this).data('id');
			editUrl = '/clients/' + clientId + '/edit';
			openLoader();
			$.ajax({
				method: 'GET',
				url: editUrl,
				data: {},
				success: function(response) {
					$(".overlay").remove();
					$('#edit_client').html(response);
					$('#edit_client').modal('show');
					//countryList();
					//currencyList();
					inputsLoader();
				}
			});
		});


		/** 
		 * Loading view client form with data to view modal
		 */

		$(document).on('click', '.view-client', function() {

			var clientId = $(this).data('id');
			showUrl = '/clients/' + clientId;
			$('#view_client').modal('show');
			$.ajax({
				method: 'GET',
				url: showUrl,
				data: {},
				success: function(response) {
					$('#view_client').html(response);
					$('#view_client').modal('show');
					inputsLoader();
					getTypheadDataClient();
				}
			});
		});

		/** to update on enter key */
		$(document).on('keyup','#edit_client_form', function (event) {
        if (event.keyCode === 13) {
          $('.edit_client').click();
      }
	});

		/**
		 * Update client form - submit button action
		 */
		$(document).on('click', '.update-client', function(e) {
			$('.field-error').html('');
			e.preventDefault();
			$("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
			var data = new FormData($('#edit_client_form')[0]);
			$.ajax({
				type: 'POST',
				url: $('#edit_client_form').attr('action'),
				data: data,
				contentType: false,
				cache: false,
				processData: false,
				success: function(response) {
					$(".overlay").remove();
					$('#edit_client').modal('hide');
					toastr.success(response.message, 'Updated');
					getClientList('');
				},
				error: function(error) {
					$(".overlay").remove();
					if (error.responseJSON.errors) {
						$.each(error.responseJSON.errors, function(field, error) {
							$('#label_edit_' + field).html(error);
						});
					}
				}
			});
		});

		/**
		 * Adding client id to hidden text field in delete model 
		 */
		$(document).on('click', '.delete-client', function() {
			var deleteClientId = $(this).data('id');
			$('#delete_client #delete_client_id').val(deleteClientId);
		});

		/**
		 * Delete model continue button action
		 */
		$(document).on('click', '#delete_client .continue-btn', function() {
			var deleteClientId = $('#delete_client #delete_client_id').val();
			$("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
			$('#delete_client').modal('hide');
			$.ajax({
				method: 'DELETE',
				url: '/clients/' + deleteClientId,
				data: {},
				success: function(response) {
					$(".overlay").remove();
					toastr.success(response.message, 'Deleted');
					getClientList('');
				},
				error: function(error) {
					$(".overlay").remove();
					toastr.warning("Delete the Projects before you delete company", 'Warning');
				}
			});
		});

		/**
		 * Search button action
		 */
		var timer;

        jQuery(document).on('change', '.search-client', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                if ($('.search-client').val().length < 4 && $('.search-client').val().length >0) return;
                getClientList($('.search-client').val());
            },300);
        });

		jQuery(document).on("click", ".search-button", function() {

			var clientCompany = $('.search-client').val();

			getClientList(clientCompany);
		});

		
	});

		/**
		 * Function to display client grid
		 */
		function getClientList(company) {
			$.ajax({
				method: 'POST',
				url: '/get-client-grid',
				data: {
					'company': company
				},
				success: function(response) {
					$('.grid').html(response.data);
					inputsLoader();
					$('.search-client').focus().val("").val(company);
				}
			});
		}
	function view(id){
		$("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
		$.ajax({
				method: 'POST',
				url: '/get-single-client',
				data: {
					'companyId': id
				},
				success: function(response) {
					$('.viewDetails').html(response.data);
					$(".overlay").remove();
                	$("html, body").animate({ scrollTop: 0 }, "slow");
					typeAhead();
				}
			});
	}

	function typeAhead() {
		$.get('get-typhead-data-client',
			function(response) {
				var name = [];
				for (var i = response.data.length - 1; i >= 0; i--) {
					name.push(response.data[i]['company_name'])
				}
				$(".typeahead_name").typeahead({
					source: name
				});
			}, 'json');
	}

	function inputsLoader() {
		
		typeAhead();
		countryList();
		currencyList();
		$('.datetimepicker').datepicker({
			todayBtn: "linked",
			keyboardNavigation: false,
			forceParse: false,
			format: "yyyy-mm-dd",
			autoclose: true
		});
		$('.chosen-select').chosen({
			width: "100%"
		});
	}

	function currencyList() {
		$('.typeahead_4').typeahead({
			source: ["AED",
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
			"ZWL"
			]
		});
	}

		function countryList() {
			//countries
			$('.typeahead_3').typeahead({
				source: ["Afghanistan",
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
					"Åland Islands"





				]
			});
		}
