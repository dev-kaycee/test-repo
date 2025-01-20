@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="notify">
                                <form method="POST" action="{{ route('tenant.projects.update', $project) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <!-- Generic Fields -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="project_name">Project Name</label>
                                                <input type="text" class="form-control form-control-sm input"
                                                       placeholder="Project Name" id="project_name" name="project_name"
                                                       value="{{ old('project_name', $project->project_name) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="project_type_id">Project Type</label>
                                                <select name="project_type_id" id="project_type" class="form-control" required>
                                                    <option value="">Select Project Type</option>
                                                    @foreach($projectTypes as $projectType)
                                                        <option value="{{ $projectType->id }}" {{ $project->project_type_id == $projectType->id ? 'selected' : '' }}>
                                                            {{ $projectType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="startDate">Start Date</label>
                                                <input type="text" class="form-control form-control-sm input start_date"
                                                       id="startDate" placeholder="Project Start Date" name="startDate"
                                                       value="{{ old('startDate', $project->startDate) }}" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="endDate">End Date</label>
                                                <input type="text" class="form-control form-control-sm input end_date"
                                                       id="endDate" placeholder="Project End Date" name="endDate"
                                                       value="{{ old('endDate', $project->endDate) }}" required autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" placeholder="Milestone Description"
                                                          name="description" required rows="3">{{ old('description', $project->description) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="location_id">Site/Location</label>
                                                <select name="location_id" id="location_id" class="form-control" required>
                                                    <option value="">Select Location</option>
                                                    @foreach($locations as $location)
                                                        <option value="{{ $location->id }}" {{ $project->location_id == $location->id ? 'selected' : '' }}>
                                                            {{ $location->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="budget">Budget (R)</label>
                                                <input type="number" class="form-control form-control-sm input"
                                                       placeholder="Budget" id="budget" name="budget" step="0.01"
                                                       value="{{ old('budget', $project->budget) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Status</label>
                                            <div class="form-group">
                                                <select class="form-control form-control-sm input" required name="status">
                                                    <option value="1" {{ $project->status == 1 ? 'selected' : '' }}>Planned</option>
                                                    <option value="2" {{ $project->status == 2 ? 'selected' : '' }}>Ongoing</option>
                                                    <option value="3" {{ $project->status == 3 ? 'selected' : '' }}>Complete</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quote_id">Quote</label>
                                                <select name="quote_id" id="quote_id" class="form-control">
                                                    <option value="">Select Quote</option>
                                                    @foreach($quotes as $quote)
                                                        <option value="{{ $quote->id }}"
                                                                data-url="{{ route('tenant.quotes.show', $quote->id) }}"
                                                                {{ $project->quote_id == $quote->id ? 'selected' : '' }}>
                                                            {{ $quote->quote_number }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="team_leader">Team Leader</label>
                                                <select name="team_leader_user_id" required id="team_leader_user_id" class="form-control">
                                                    <option value="">Select Team Leader</option>
                                                    @foreach($teamLeaders as $teamLeader)
                                                        <option value="{{ $teamLeader->id }}" {{ $project->team_leader_user_id == $teamLeader->id ? 'selected' : '' }}>
                                                            {{ $teamLeader->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="assets">Assets</label>
                                                <select name="assets[]" id="assets" class="form-control select2-multiple" multiple="multiple">
                                                    @foreach($assets as $asset)
                                                        <option value="{{ $asset->id }}" {{ in_array($asset->id, $project->assets->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $asset->name }} ({{ $asset->serial_number }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vegetation Management Fields -->
                                    <div id="vegetationManagementFields" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Target Hectares</label>
                                                    <input type="number" class="form-control form-control-sm input"
                                                           placeholder="Target Hectares" id="target_hectares"
                                                           name="target_hectares" step="0.01"
                                                           value="{{ old('target_hectares', $project->target_hectares) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Actual Hectares Completed</label>
                                                    <input type="number" placeholder="Actual Hectares Completed"
                                                           class="form-control form-control-sm input"
                                                           id="actual_hectares"
                                                           name="actual_hectares"
                                                           value="{{ old('actual_hectares', $project->actual_hectares) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Planned Person days</label>
                                                    <input type="text" placeholder="Planned Person days"
                                                           class="form-control form-control-sm input" id="planned_days"
                                                           name="planned_days" readonly
                                                           value="{{ old('planned_days', $project->planned_days) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Target Hectares Per Day</label>
                                                    <input type="number" class="form-control form-control-sm input"
                                                           placeholder="Target hectares per day" id="hectares_per_day"
                                                           name="hectares_per_day" readonly
                                                           value="{{ old('hectares_per_day', $project->hectares_per_day) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Actual Budget</label>
                                                    <input type="number" class="form-control form-control-sm input"
                                                           placeholder="Actual Budget" name="actual_budget" step="0.01"
                                                           value="{{ old('actual_budget', $project->actual_budget) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>SMME</label>
                                                <div class="form-group">
                                                    <select name="smme_id" id="smme_id" class="form-control">
                                                        <option value="">Select SMME</option>
                                                        @foreach($smmes as $smme)
                                                            <option value="{{ $smme->id }}" {{ $project->smme_id == $smme->id ? 'selected' : '' }}>
                                                                {{ $smme->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Vehicle KMS Target</label>
                                                    <input type="text" placeholder="Vehicle KMS Target"
                                                           class="form-control form-control-sm input"
                                                           name="vehicle_kms_target"
                                                           value="{{ old('vehicle_kms_target', $project->vehicle_kms_target) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Actual Vehicle KMS</label>
                                                    <input type="text" placeholder="Actual Vehicle KM"
                                                           class="form-control form-control-sm input"
                                                           name="actual_vehicle_kms"
                                                           value="{{ old('actual_vehicle_kms', $project->actual_vehicle_kms) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="checkbox" id="quote_check" name="quote_check" value="1" {{ $project->quote_check ? 'checked' : '' }}>
                                                    <label for="quote_check">Quote</label>
                                                    <a href="#" id="view_quote_link" target="_blank" style="display: none; margin-left: 10px;">
                                                        <i class="fa fa-eye" aria-hidden="false"></i>
                                                    </a>
                                                    <br>
                                                    <input type="checkbox" id="inspection_check" name="inspection_check" value="1" {{ $project->inspection_check ? 'checked' : '' }}>
                                                    <label for="inspection_check">Inspection</label><br>
                                                    <input type="checkbox" id="labour_report_check" name="labour_report_check" value="1" {{ $project->labour_report_check ? 'checked' : '' }}>
                                                    <label for="labour_report_check">Labour Report</label><br>
                                                    <input type="checkbox" id="safety_talk_check" name="safety_talk_check" value="1" {{ $project->safety_talk_check ? 'checked' : '' }}>
                                                    <label for="safety_talk_check">Safety Talk</label><br>
                                                    <input type="checkbox" id="herbicide_check" name="herbicide_check" value="1" {{ $project->herbicide_check ? 'checked' : '' }}>
                                                    <label for="herbicide_check">Herbicide Usage</label><br>
                                                    <input type="checkbox" id="invoice_check" name="invoice_check" value="1" {{ $project->invoice_check ? 'checked' : '' }}>
                                                    <label for="invoice_check">Invoice</label><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Training Fields -->
                                    <div id="trainingFields" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Number Of Students</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-sm input"
                                                           placeholder="Number Of Students" id="number_of_students"
                                                           name="number_of_students"
                                                           value="{{ old('number_of_students', $project->number_of_students) }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="progress ">
                                                        <div id="training-progress" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">0%</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="checkbox" id="facilitation_check" name="facilitation_check" value="1" {{ $project->facilitation_check ? 'checked' : '' }}>
                                                    <label for="facilitation_check">Facilitation</label><br>
                                                    <input type="checkbox" id="assessment_check" name="assessment_check" value="1" {{ $project->assessment_check ? 'checked' : '' }}>
                                                    <label for="assessment_check">Assessment</label><br>
                                                    <input type="checkbox" id="moderation_check" name="moderation_check" value="1" {{ $project->moderation_check ? 'checked' : '' }}>
                                                    <label for="moderation_check">Moderation</label><br>
                                                    <input type="checkbox" id="database_admin_check" name="database_admin_check" value="1" {{ $project->database_admin_check ? 'checked' : '' }}>
                                                    <label for="database_admin_check">Database Administration</label><br>
                                                    <input type="checkbox" id="certification_check" name="certification_check" value="1" {{ $project->certification_check ? 'checked' : '' }}>
                                                    <label for="certification_check">Certification</label><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        @include('partials.save-button')
                                        @include('partials.icon_button', ['href' => route('tenant.projects.index'), 'type' => 'danger', 'icon' => 'fa-arrow-left', 'slot' => 'Back'])
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script>
        $(function () {
            // Initialize datepickers
            $(".start_date, .end_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            function toggleRequiredFields() {
                var projectType = $('#project_type').val();

                // Remove required from all potentially hidden fields
                $('#vegetationManagementFields input, #vegetationManagementFields select, #vegetationManagementFields textarea').prop('required', false);
                $('#trainingFields input, #trainingFields select, #trainingFields textarea').prop('required', false);

                if (projectType === '1') {
                    $('#vegetationManagementFields').show();
                    $('#trainingFields').hide();
                    $('#vegetationManagementFields input:not([type="checkbox"]), #vegetationManagementFields select, #vegetationManagementFields textarea').prop('required', true);
                } else if (projectType === '2') {
                    $('#trainingFields').show();
                    $('#vegetationManagementFields').hide();
                    $('#trainingFields input:not([type="checkbox"]), #trainingFields select, #trainingFields textarea').prop('required', true);
                } else {
                    $('#vegetationManagementFields, #trainingFields').hide();
                }

                $('input[type="checkbox"]').prop('required', false);
            }

            toggleRequiredFields();
            $('#project_type').change(toggleRequiredFields);

            function calculatePlannedDays() {
                var startDate = $(".start_date").datepicker("getDate");
                var endDate = $(".end_date").datepicker("getDate");

                if (startDate && endDate) {
                    var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
                    var dayDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                    $("#planned_days").val(dayDiff + 1);
                } else {
                    $("#planned_days").val('');
                }
                calculateHectaresPerDay();
            }

            function calculateHectaresPerDay() {
                var targetHectares = parseFloat($("#target_hectares").val());
                var plannedDays = parseFloat($("#planned_days").val());

                if (!isNaN(targetHectares) && !isNaN(plannedDays) && plannedDays > 0) {
                    var hectaresPerDay = targetHectares / plannedDays;
                    $("#hectares_per_day").val(hectaresPerDay.toFixed(2));
                } else {
                    $("#hectares_per_day").val('');
                }
            }

            $(".start_date, .end_date").on('change', function () {
                calculatePlannedDays();
            });

            $("#target_hectares").on('input', function () {
                calculateHectaresPerDay();
            });

            function updateQuoteLink() {
                const selectedOption = $('#quote_id option:selected');
                const isSelected = selectedOption.val() !== "";
                $('#quote_check').prop('checked', isSelected);

                if (isSelected) {
                    const url = selectedOption.data('url');
                    $('#view_quote_link').attr('href', url).show();
                } else {
                    $('#view_quote_link').hide();
                }
            }

            $('#quote_id').change(updateQuoteLink);

            $('#quote_check').change(function() {
                if ($(this).is(':checked')) {
                    updateQuoteLink();
                } else {
                    $('#view_quote_link').hide();
                }
            });

            // Initial update
            updateQuoteLink();

            const trainingCheckboxes = $('#trainingFields input[type="checkbox"]');
            trainingCheckboxes.on('change', function() {
                const totalCheckboxes = trainingCheckboxes.length;
                const checkedCount = trainingCheckboxes.filter(':checked').length;
                const progressPercent = Math.round((checkedCount / totalCheckboxes) * 100);

                $('#training-progress')
                    .css('width', progressPercent + '%')
                    .attr('aria-valuenow', progressPercent)
                    .text(progressPercent + '%');
            });

            calculatePlannedDays();
            calculateHectaresPerDay();

            $('form').on('submit', function(e) {
                console.log('Form submitted');
            });

            $('#assets').select2({
                placeholder: "Select assets",
                allowClear: true,
                width: '100%'
            });

            // Trigger initial calculations and updates
            toggleRequiredFields();
            updateQuoteLink();
            calculatePlannedDays();
            trainingCheckboxes.trigger('change');
        });
    </script>
@endsection