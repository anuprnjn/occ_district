@extends('public_layouts.app')


@section('content')

<section class="content-section "  >
    <div class="radio-container" id="main-content">
        <label>
            <input type="radio" name="courtType" value="highCourt" checked> High Court
        </label>
        <label>
            <input type="radio" name="courtType" value="districtCourt"> District Court
        </label>
    </div>

    <!-- Dropdown for High Court -->
    <div id="highCourtDropdown" class="dropdown w-[100%] sm:w-[50%] p-[10px] sm:-ml-2">
        <label for="highCourtSelect" class="mb-2">Select an option:</label>
        <select id="highCourtSelect" class="p-[10px]">
            <option value="applyJudgement" selected>Apply for Orders and Judgement Copy</option>
            <option value="applyOrders">Apply for Orders Copy</option>
        </select>
    </div>

    <!-- Dropdown for District Court -->
    <div id="districtCourtDropdown" class=" dark_form dropdown w-[100%] p-[10px] bg-slate-100/70 rounded-md" style="display: none;">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- First Select Box -->
            <div>
                <label for="selectDist" class="mb-2 block">Select District:<span>*</span></label>
                <select id="selectDist" class="w-full p-[10px] border border-gray-300 rounded">
                    <option value="applyOthers" selected>Select District</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district['dist_code'] }}">{{ $district['dist_name'] }}</option>
                    @endforeach
                </select>
            </div>
    
            <!-- Second Select Box -->
            <div>
                <label for="selectEsta" class="mb-2 block">Select Establishment:<span>*</span></label>
                <select id="selectEsta" class="w-full p-[10px] border border-gray-300 rounded">
                    <option value="applyOthers" selected>Select Establishment</option>
                </select>
            </div>
        </div>
    
        <!-- Third Select Box -->
        <div class="mt-4">
            <label for="districtCourtSelect" class="mb-2 block">Select an option:</label>
            <select id="districtCourtSelect" class="sm:w-[49.4%] w-[100%] p-[10px] border border-gray-300 rounded">
                <option value="applyOthers" selected>Apply for Others Copy</option>
            </select>
        </div>
    </div>
    
    

    <!-- Form Container -->
    <div id="formContainer"></div>
    <!-- Display submitted data -->
@if (isset($formData))
<div class="submitted-data">
    <h3>Form Data Submitted:</h3>
    <ul>
        <li><strong>Name:</strong> {{ $formData['name'] }}</li>
        <li><strong>Mobile No:</strong> {{ $formData['mobile'] }}</li>
        <li><strong>Email:</strong> {{ $formData['email'] }}</li>
        <li><strong>Case Type:</strong> {{ $formData['case_type'] }}</li>
        <!-- Display Filing No if available -->
        <li><strong>Filing No:</strong> {{ $formData['filing_no'] ?? 'N/A' }}</li>
        <!-- Display Case No if available -->
        <li><strong>Case No:</strong> {{ $formData['case_no'] ?? 'N/A' }}</li>
        <li><strong>Request Mode:</strong> {{ $formData['request_mode'] }}</li>
        <li><strong>Required Document:</strong> {{ $formData['required_document'] }}</li>
        <li><strong>Apply By:</strong> {{ $formData['apply_by'] }}</li>
        <!-- If advocate registration is provided, show it -->
        @if (isset($formData['advocate_registration']) && $formData['apply_by'] === 'advocate')
            <li><strong>Advocate Registration No:</strong> {{ $formData['advocate_registration'] }}</li>
        @endif
        <li><strong>District Code:</strong> {{ $formData['district_code'] }}</li>
        <li><strong>District Name:</strong> {{ $formData['district_name'] }}</li>
    </ul>
</div>
@endif

</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#selectDist').on('change', function () {
            const distCode = $(this).val();

            if (distCode === 'applyOthers') {
                $('#selectEsta').html('<option value="applyOthers" selected>Select Establishment</option>');
                return;
            }

            $.ajax({
                url: "{{ route('get-establishments') }}",
                method: 'POST',
                data: {
                    dist_code: distCode,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    const establishments = data;
                    let options = '<option value="applyOthers" selected>Select Establishment</option>';

                    establishments.forEach(function (establishment) {
                        options += `<option value="${establishment.est_code}">${establishment.estname}</option>`;
                    });

                    $('#selectEsta').html(options);
                },
                error: function () {
                    alert('Unable to fetch establishments. Please try again later.');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#home').addClass('active');

        // Handle radio button change
        $('input[name="courtType"]').change(function () {
            const selectedCourt = $('input[name="courtType"]:checked').val();

            if (selectedCourt === 'highCourt') {
            $('#highCourtDropdown').show();
            $('#districtCourtDropdown').hide();
            $('#formContainer').html(''); // Clear forms
            $('#highCourtSelect').trigger('change'); // Trigger change event for default option
        } else {
            $('#highCourtDropdown').hide();
            $('#districtCourtDropdown').show();
            $('#formContainer').html(''); // Clear forms
            $('#districtCourtSelect').trigger('change'); // Trigger change event for default option

            // Reset #selectDist dropdown to default value
            $('#selectDist').val('applyOthers').trigger('change');
        }

        });

        // Initially display the form for "Apply for Orders and Judgement Copy"
        $('#formContainer').html(` 
            <form id="applyJudgementForm" class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10">
                <h3 class="font-semibold mb-4">Apply for Orders and Judgement Copy</h3>
                <div class="form-group">
                    <label>
                        <input type="radio" name="search-type" value="case-no" checked>
                        Case No
                    </label>
                    <label>
                        <input type="radio" name="search-type" value="filing-no">
                        Filing No
                    </label>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="case-type">Case Type:</label>
                        <select id="case-type" name="case-type" class="p-[10px]">
                            <option value="">Please Select</option>
                            <!-- Add options as needed -->
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="case-no">Case No:</label>
                        <input type="text" id="case-no" name="case-no" placeholder="Enter Case No">
                    </div>
                    <div class="form-field">
                        <label for="case-year">Case Year:</label>
                        <input type="text" id="case-year" name="case-year" placeholder="Enter Case Year">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <input type="text" id="captcha" name="captcha" placeholder="Enter Captcha Code Here">
                    </div>
                    <div class="form-field captcha">
                        <img src="captcha-placeholder.png" alt="Captcha">
                        <button type="button" class="refresh-captcha">
                            <img src="refresh-icon.png" alt="Refresh">
                        </button>
                    </div>
                    <div class="form-field">
                        <button type="submit" class="btn btn-search">Search</button>
                    </div>
                </div>
            </form>
        `);

        // Handle dropdown selection
        $('#highCourtSelect, #districtCourtSelect').change(function () {
            const selectedOption = $(this).val();
            let formHtml = '';

            if (selectedOption === 'applyJudgement') {
                formHtml = `
                    <form id="applyJudgementForm" class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10">
                <h3 class="font-semibold mb-4">Apply for Orders and Judgement Copy</h3>
                <div class="form-group">
                    <label>
                        <input type="radio" name="search-type" value="case-no" checked>
                        Case No
                    </label>
                    <label>
                        <input type="radio" name="search-type" value="filing-no">
                        Filing No
                    </label>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="case-type">Case Type:</label>
                        <select id="case-type" name="case-type" class="p-[10px]">
                            <option value="">Please Select</option>
                            <!-- Add options as needed -->
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="case-no">Case No:</label>
                        <input type="text" id="case-no" name="case-no" placeholder="Enter Case No">
                    </div>
                    <div class="form-field">
                        <label for="case-year">Case Year:</label>
                        <input type="text" id="case-year" name="case-year" placeholder="Enter Case Year">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <input type="text" id="captcha" name="captcha" placeholder="Enter Captcha Code Here">
                    </div>
                    <div class="form-field captcha">
                        <img src="captcha-placeholder.png" alt="Captcha">
                        <button type="button" class="refresh-captcha">
                            <img src="refresh-icon.png" alt="Refresh">
                        </button>
                    </div>
                    <div class="form-field">
                        <button type="submit" class="btn btn-search">Search</button>
                    </div>
                </div>
            </form>`;
            } else if (selectedOption === 'applyOrders') {
                formHtml = `
                    <form id="applyOrdersForm" class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10">
                        <h3 class="font-semibold mb-4">Apply for Orders Copy</h3>
                        <div class="form-row">
                        <div class="form-field">
                            <label for="name">Name: <span>*</span></label>
                            <input type="text" id="name" name="name" placeholder="ENTER YOUR NAME" required>
                        </div>
                        <div class="form-field">
                            <label for="mobile">Mobile No: <span>*</span></label>
                            <input type="text" id="mobile" name="mobile" placeholder="Enter Your Mobile No" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="email">Email: <span>*</span></label>
                            <input type="email" id="email" name="email" placeholder="Enter Your Email" required>
                        </div>
                        <div class="form-field">
                            <label for="confirm-email">Confirm Email: <span>*</span></label>
                            <input type="email" id="confirm-email" name="confirm_email" placeholder="Enter Your Confirm Email" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="case-type">Case Type: <span>*</span></label>
                            <select id="case-type" name="case_type" required class="p-[10px]">
                                <option value="">Please Select</option>
                                <!-- Add more options here -->
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="case-no">Case No: <span>*</span></label>
                            <input type="text" id="case-no" name="case_no" placeholder="Enter Case No" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="case-year">Case Year: <span>*</span></label>
                            <input type="text" id="case-year" name="case_year" placeholder="Enter Case Year" required>
                        </div>
                        <div class="form-field">
                            <label for="request-mode">Request Mode: <span>*</span></label>
                            <div>
                                <input type="radio" id="urgent" name="request_mode" value="urgent" required>
                                <label for="urgent">Urgent</label>
                                <input type="radio" id="ordinary" name="request_mode" value="ordinary" required>
                                <label for="ordinary">Ordinary</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="required-document">Required Document: <span>*</span></label>
                            <textarea id="required-document" name="required_document" placeholder="Enter Document Details" rows="3" required></textarea>
                        </div>
                        <div class="form-field">
                            <label for="apply-by">Apply By: <span>*</span></label>
                            <select id="apply-by" name="apply_by" required class="p-[10px]">
                                <option value="">--Select--</option>
                                <!-- Add more options here -->
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field captcha">
                            <label for="captcha">Enter Captcha Code Here: </label>
                            <input type="text" id="captcha" name="captcha" required>
                            <img src="path-to-captcha.jpg" alt="Captcha">
                            <button type="button" class="refresh-captcha">
                                <img src="refresh-icon-path.jpg" alt="Refresh">
                            </button>
                        </div>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn-submit">Submit</button>
                    </div>
                    </form>`;
            } else if (selectedOption === 'applyOthers') {
                formHtml = `
            <form id="applyOrdersForm" class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10">
    <h3 class="font-semibold mb-4">Apply for Orders Copy</h3>
    
    <div class="form-row">
        <div class="form-field">
            <label for="name">Name: <span>*</span></label>
            <input type="text" id="name" name="name" placeholder="ENTER YOUR NAME" required>
        </div>
        <div class="form-field">
            <label for="mobile">Mobile No: <span>*</span></label>
            <input type="text" id="mobile" name="mobile" placeholder="Enter Your Mobile No" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label for="email">Email: <span>*</span></label>
            <input type="email" id="email" name="email" placeholder="Enter Your Email" required>
        </div>
        <div class="form-field">
            <label for="confirm-email">Confirm Email: <span>*</span></label>
            <input type="email" id="confirm-email" name="confirm_email" placeholder="Enter Your Confirm Email" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <div class="sm:mt-4 mt-0">
                <input type="radio" id="case-number" name="case_or_filing" value="case" checked onchange="toggleCaseFilingFields()">
                <label for="case-number">Case Number</label>

                <input class="sm:ml-4 ml-0" type="radio" id="filing-number" name="case_or_filing" value="filing" onchange="toggleCaseFilingFields()">
                <label for="filing-number">Filing Number</label>
            </div>
        </div>

        <div class="form-field">
            <label for="case-type">Case Type: <span>*</span></label>
            <select id="case-type" name="case_type" required class="p-[10px]">
                <option value="">Please Select</option>
                @foreach ($caseTypes as $caseType)
                    <option value="{{ $caseType['case_type'] }}">{{ $caseType['type_name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Case fields (initially displayed if Case is selected) -->
    <div id="case-fields">
        <div class="form-row">
            <div class="form-field">
                <label for="case-no">Case No: <span>*</span></label>
                <input type="text" id="case-no" name="case_no" placeholder="Enter Case No" required>
            </div>
            <div class="form-field">
                <label for="case-year">Case Year: <span>*</span></label>
                <input type="text" id="case-year" name="case_year" placeholder="Enter Case Year" required>
            </div>
        </div>
    </div>

    <!-- Filing fields (initially hidden if Case is selected) -->
    <div id="filing-fields" style="display:none;">
        <div class="form-row">
            <div class="form-field">
                <label for="filing-no">Filing No: <span>*</span></label>
                <input type="text" id="filing-no" name="filing_no" placeholder="Enter Filing No" required>
            </div>
            <div class="form-field">
                <label for="filing-year">Filing Year: <span>*</span></label>
                <input type="text" id="filing-year" name="filing_year" placeholder="Enter Filing Year" required>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label for="request-mode">Request Mode: <span>*</span></label>
            <div>
                <input type="radio" id="urgent" name="request_mode" value="urgent" required>
                <label for="urgent">Urgent</label>
                <input type="radio" id="ordinary" name="request_mode" value="ordinary" required>
                <label for="ordinary">Ordinary</label>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label for="required-document">Required Document: <span>*</span></label>
            <textarea id="required-document" name="required_document" placeholder="Enter Document Details" rows="3" required></textarea>
        </div>

        <div class="form-field">
            <label for="apply-by">Apply By: <span>*</span></label>
            <select id="apply-by" name="apply_by" required class="p-[10px]" onchange="toggleAdvocateRegistration()">
                <option value="">--Select--</option>
                <option value="petitioner">Petitioner</option>
                <option value="respondent">Respondent</option>
                <option value="advocate">Advocate</option>
                <option value="others">Others</option>
            </select>
        </div>
    </div>

    <!-- Advocate Registration Field (hidden initially) -->
    <div class="form-row" style="display: flex; justify-content: space-between;width: 100%;">
        <div id="advocate-registration-field" style="display:none; width:50%">
            <div class="form-field">
                <label for="advocate_registration">Enter Advocate registration no: </label>
                <input type="text" id="advocate_registration" name="advocate_registration">
            </div>
        </div>

        <div class="form-field">
            <label for="captcha">Enter Captcha Code Here: </label>
            <input type="text" id="captcha" name="captcha" required style="width: 100%">
            <img src="path-to-captcha.jpg" alt="Captcha">
            <button type="button" class="refresh-captcha">
                <img src="refresh-icon-path.jpg" alt="Refresh">
            </button>
        </div>
    </div>

    <div class="form-row">
        <button type="submit" class="btn-submit">Submit</button>
    </div>
</form>

                `;
            }

            $('#formContainer').html(formHtml);
        });
    });
</script>
<script>
    // Show/Hide Filing and Case Number fields
    function toggleCaseFilingFields() {
        const filingFields = document.getElementById('filing-fields');
        const caseFields = document.getElementById('case-fields');

        const filingRadio = document.getElementById('filing-number');
        const caseRadio = document.getElementById('case-number');

        // If Filing Number is selected, show Filing fields and hide Case fields
        if (filingRadio.checked) {
            filingFields.style.display = 'block';
            caseFields.style.display = 'none';

            // Change label text dynamically for filing number
            document.querySelector('label[for="filing-no"]').textContent = "Enter Filing No:";
            document.querySelector('label[for="filing-year"]').textContent = "Enter Filing Year:";

        } else if (caseRadio.checked) {
            filingFields.style.display = 'none';
            caseFields.style.display = 'block';

            // Change label text dynamically for case number
            document.querySelector('label[for="case-no"]').textContent = "Enter Case No:";
            document.querySelector('label[for="case-year"]').textContent = "Enter Case Year:";
        }
    }

    // Toggle Advocate Registration field visibility
    function toggleAdvocateRegistration() {
        const applyBy = document.getElementById('apply-by').value;
        const advocateField = document.getElementById('advocate-registration-field');
        if (applyBy === 'advocate') {
            advocateField.style.display = 'block';
        } else {
            advocateField.style.display = 'none';
        }
    }

    // Initialize the form
    window.onload = function () {
        // Default display: Case No
        document.getElementById('case-number').checked = true;
        toggleCaseFilingFields();
        toggleAdvocateRegistration();
    };
</script>
<script>
    function toggleCaseFilingFields() {
        var caseOrFiling = document.querySelector('input[name="case_or_filing"]:checked').value;
        if (caseOrFiling === 'filing') {
            document.getElementById('case-fields').style.display = 'none';
            document.getElementById('filing-fields').style.display = 'block';
        } else {
            document.getElementById('filing-fields').style.display = 'none';
            document.getElementById('case-fields').style.display = 'block';
        }
    }

    function toggleAdvocateRegistration() {
        var applyBy = document.getElementById('apply-by').value;
        var advocateField = document.getElementById('advocate-registration-field');
        if (applyBy === 'advocate') {
            advocateField.style.display = 'block';
        } else {
            advocateField.style.display = 'none';
        }
    }
</script>
<script>
    function toggleCaseFilingFields() {
        var caseRadio = document.getElementById("case-number");
        var filingRadio = document.getElementById("filing-number");

        var caseFields = document.getElementById("case-fields");
        var filingFields = document.getElementById("filing-fields");

        var caseNo = document.getElementById("case-no");
        var caseYear = document.getElementById("case-year");
        var filingNo = document.getElementById("filing-no");
        var filingYear = document.getElementById("filing-year");

        if (caseRadio.checked) {
            caseFields.style.display = "block";
            filingFields.style.display = "none";

            caseNo.setAttribute('required', 'required');
            caseYear.setAttribute('required', 'required');
            
            filingNo.removeAttribute('required');
            filingYear.removeAttribute('required');
        } else if (filingRadio.checked) {
            filingFields.style.display = "block";
            caseFields.style.display = "none";

            filingNo.setAttribute('required', 'required');
            filingYear.setAttribute('required', 'required');
            
            caseNo.removeAttribute('required');
            caseYear.removeAttribute('required');
        }
    }

    // Initial toggle
    document.addEventListener('DOMContentLoaded', function() {
        toggleCaseFilingFields();
    });
</script>
@endpush
