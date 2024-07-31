<div id="create_salary_hike" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title text-center">Add New Salary Hike</h4>
            </div>
            <div id="employees-data" data-employees='{{ json_encode($employees); }}'></div>

            <div class="modal-body">
                <form action="{{route('salary-hike.store')}}" id="add_salary_hike_form" method="POST" autocomplete="off">
                    @csrf
                    
                    <div class="form-group">
                        <label>Select Employee <span class="required-label">*</span></label>
                        <select class="form-control" name="employee" id="employee">
                            <option value="" disabled selected>Select an Employee</option>

                            @foreach($employeesWithoutHike as $employee)
                                <option value="{{ $employee->id }}">{{ $employee['first_name'] }} {{ $employee['last_name'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger text-left field-error" id="label_employee"></div>
                    </div>

                    <div class="form-group">
                        <label>Current Salary <span class="required-label">*</span></label>
                        <input class="form-control" type="number" step="0.01" name="previous_salary" id="previous_salary" readonly>
                        <div class="text-danger text-left field-error" id="label_previous_salary"></div>
                    </div>

                    <div class="form-group">
                        <label>Hike <span class="required-label">*</span></label>
                        <input class="form-control" type="number" step="0.01" name="hike" id="hike">
                        <div class="text-danger text-left field-error" id="label_hike"></div>
                    </div>

                    <div class="form-group">
                        <label>Updated Salary <span class="required-label">*</span></label>
                        <input class="form-control" type="number" step="0.01" name="updated_salary" id="updated_salary" readonly>
                        <div class="text-danger text-left field-error" id="label_updated_salary"></div>
                    </div>

                    <div class="form-group">
                        <label>Date <span class="required-label">*</span></label>
                        <input class="form-control" type="date" name="date" id="date" value="<?php echo date('Y-m-01'); ?>" readonly>
                        <div class="text-danger text-left field-error" id="label_date"></div>
                    </div>

                    <div class="form-group">
                        <label>Notes <span class="required-label">*</span></label>
                        <textarea class="form-control" name="notes" id="notes" style="height: 150px;"></textarea>
                        <div class="text-danger text-left field-error" id="label_notes"></div>
                    </div>
                    
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn create_salary_hike">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
