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
            }

            $('#formContainer').html(formHtml);
        });
    });
</script>


@endpush
