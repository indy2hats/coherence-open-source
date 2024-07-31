    $(document).ready(function () {
                const employeesData = $("#employees-data").data("employees");

        const employeeSelect = $("#employee");
        const currentAmountInput = $("#previous_salary");
        const hikeInput = $("#hike");
        const updatedAmountInput = $("#updated_salary");

        employeeSelect.on("change", function () {
            const selectedEmployee = employeesData.find(employee => employee.id === parseInt(employeeSelect.val()));
            if (selectedEmployee) {
                currentAmountInput.val(selectedEmployee.monthly_salary.toFixed(2));
                updateUpdatedAmount();
            }
        });

        hikeInput.on("input", function () {
            updateUpdatedAmount();
        });

        function updateUpdatedAmount() {
            const selectedEmployee = employeesData.find(employee => employee.id === parseInt(employeeSelect.val()));
            
            if (selectedEmployee) {
                const updatedSalary = (selectedEmployee.monthly_salary + parseFloat(hikeInput.val())).toFixed(2);
                updatedAmountInput.val(updatedSalary);
            }
        }

          /**
         * Create project - submit button action
         */
          $('.create_salary_hike').click(function(e) {
            toasterOption();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('.field-error').html('');
            e.preventDefault();
            var data = new FormData($('#add_salary_hike_form')[0]);

            $.ajax({
                method: 'POST',
                url: $('#add_salary_hike_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $(".overlay").remove();
                    $("#create_salary_hike").modal('hide');
                    toastr.success(response.message, 'Saved');
                    setTimeout(loadSalaryHikes, 2000);
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            if (field.startsWith("files")) {
                                $('#label_files').html('The file must be a PDF, JPG, PNG, or JPEG & size must not exceed 5MB');
                            }
                            else{
                                $('#label_' + field).html(error);
                            }
                        });
                    }
                }
            });
        });

        function loadSalaryHikes(){
            window.location="/salary-hike"; 
        }

        $('.chosen-select').chosen({
            width: "100%"
        });

        $(document).on('change', '#search_employee, #search_year', function(e) {
            searchList(e);
        });

        function searchList(e) {
            e.preventDefault();
            $('#search-salary-hike').submit();
        }


  $('.salary-hike-datepicker').datepicker({
    startView: 1,
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    endDate: "+1y", 
  }); 
    });
