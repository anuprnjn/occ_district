@extends('public_layouts.form')

@section('content')
<section class="w-full">

    <div id="highCourtDropdown" class="dropdown w-[100%] sm:w-[50%] p-[10px] sm:-ml-2">
        <label for="highCourtSelect" class="mb-2">Select an option:</label>
        <select id="highCourtSelect" class="p-[10px]" onchange="myfun()">
            <option value="applyJudgement" selected>Apply for Orders and Judgement Copy</option>
            <option value="applyOrders">Apply for Others Copy</option>
        </select>
    </div>

    <!-- Form for Apply for Orders and Judgement Copy -->
    <div class="orderJudgement" id="orderJudgementForm" style="display:block;">
        <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10">
            <h3 class="font-semibold text-lg mb-4">Apply for Orders And Judgement Copy :</h3>
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
            <label for="case-type">Case Type: <span>*</span></label>
            <div class="relative w-full dark_select">
                <!-- Custom Dropdown -->
                <div id="caseTypeDropdownForOrderJudgement" class="w-full p-[10px] border rounded ">
                    <div id="caseTypeToggleForOrderJudgementForm" class="cursor-pointer" onclick="toggleCaseTypeDropdownForOrderJudgement()">Please Select Case Type</div>
                    <div id="caseTypeMenuForOrderJudgementForm" class="hidden absolute top-full left-0 w-full max-h-60 border border-gray-300 dark_select overflow-y-auto rounded shadow-lg z-10">
                        <!-- Search Box -->
                        <div class="p-2">
                            <input type="text" id="caseTypeSearchInputForOrderJudgementForm" class="w-full p-[10px] border border-gray-300 rounded" placeholder="Search Case Type..." onkeyup="filterCaseTypeOptionsForOrderJudgementForm()">
                        </div>
                        <!-- Options -->
                        <ul id="caseTypeOptionsForOrderJudgementForm" class="list-none p-0 m-0">
                            <li data-value="" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectCaseTypeOptionForOrderJudgementForm(this); getCaseType(this)">Please Select Case Type</li>
                            @if (!empty($caseTypes) && is_array($caseTypes))
                                @foreach ($caseTypes as $caseType)
                                    <li data-value="{{ $caseType['case_type'] }}" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectCaseTypeOptionForOrderJudgementForm(this); getCaseType(this)">
                                        {{ $caseType['type_name'] }} ( {{ $caseType['full_form'] }} )
                                    </li>
                                @endforeach
                            @else
                                <li data-value="" class="p-2 cursor-not-allowed text-gray-500">No Case Types Available</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
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
            <label for="captcha">Evaluate the Expression<span>*</span></label>
            <div class="flex justify-center items-center gap-1">
                <img id="captchaImageOrderJudgement" src="{{ captcha_src() }}" alt="Captcha">
                <input class="text-lg" type="text" id="captcha-hc-orderJudgement" name="captcha" required placeholder="Enter the expression">
                <button type="button" class="refresh-btn-orderJudgement rounded-full hover:shadow-md" onclick="refreshCaptchaForOrderJudgement()" title="Refresh Captcha">
                    <img class="w-[52px]" src="{{ asset('passets/images/icons/refresh.png')}}" alt="Refresh">
                </button>
            </div>
        </div>
                <div class="form-field mt-10">
                    <button type="submit" class="btn btn-search">Search</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Form for Apply for Others Copy -->
    <div class="otherform" id="otherForm" style="display:none;">
        <form id="applyOrdersFormHC" class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10">
            <h3 class="font-semibold text-lg mb-4">Apply for Others Copy :</h3>
            @csrf
            <div class="form-row">
                <div class="form-field">
                    <label for="name">Name: <span>*</span></label>
                    <input type="text" id="name" name="name" placeholder="ENTER YOUR NAME" required>
                </div>
        <div class="form-field">
            <div class="flex items-start justify-start gap-2">
                <label for="mobile" id="mobileLabel">Mobile No: <span>*</span></label>
            <span
            id="otpTimer"
            class="text-md text-rose-600 "
            ></span>
            </div>
            <div class="flex items-center justify-center gap-2">
            <input
                type="text"
                id="mobileInput"
                name="mobile"
                placeholder="Enter Your Mobile No"
                class="p-[10px] border border-gray-300 rounded"
                required
                maxlength="10"
                onkeydown="return isNumber(event)"
            >
            <button
            type="button"
            id="otpButton"
            value = "HC"
            onclick="sendOtp(value)"
            class="bg-[#4B3E2F] sm:w-[200px] w-[150px] sm:p-[10px] p-[8px] rounded-md text-white hover:bg-[#D09A3F]"
            >
                Send OTP
            </button>
            <div id="application_n_details" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[99999] hidden">
                <div class="dark_form bg-white p-6 rounded-lg shadow-lg w-[90%] sm:w-[500px] md:w-[600px] lg:w-[800px] relative">
                    <button class="absolute top-2 right-2 text-gray-500 hover:text-black" onclick="closeModal()">✖</button>
                    <h4 id="modalText" class="text-lg text-center mb-4"></h4>
                    <table id="trackedDataDC" class="w-[100%]"></table>
                </div>
            </div>
            <button id="view_recent_button" class="hidden bg-sky-600 hover:bg-sky-700 rounded-md text-white sm:w-[250px] w-[200px] p-[10px]" onclick="view_recent_app()">View Transactions</button>
                 
            </div>
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
            <label for="request-mode">Select the method: <span>*</span></label>
            <div class="mt-2">
                <input type="radio" id="case_no" name="select_mode" value="C" required checked onchange="updateFields()">
                <label for="case_no">Case No</label>
                <input type="radio" id="filling_no" name="select_mode" value="F" required onchange="updateFields()" class="ml-4">
                <label for="filling_no">Filling No</label>
            </div>
        </div>
        <div class="form-field">
            <label for="case-type">Case Type: <span>*</span></label>
            <div class="relative w-full dark_select">
                <!-- Custom Dropdown -->
                <div id="caseTypeDropdown" class="w-full p-[10px] border rounded ">
                    <div id="caseTypeToggle" class="cursor-pointer" onclick="toggleCaseTypeDropdown()">Please Select Case Type</div>
                    <div id="caseTypeMenu" class="hidden absolute top-full left-0 w-full max-h-60 border border-gray-300 dark_select overflow-y-auto rounded shadow-lg z-10">
                        <!-- Search Box -->
                        <div class="p-2">
                            <input type="text" id="caseTypeSearchInput" class="w-full p-[10px] border border-gray-300 rounded" placeholder="Search Case Type..." onkeyup="filterCaseTypeOptions()">
                        </div>
                        <!-- Options -->
                        <ul id="caseTypeOptions" class="list-none p-0 m-0">
                            <li data-value="" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectCaseTypeOption(this); getCaseType(this)">Please Select Case Type</li>
                            @if (!empty($caseTypes) && is_array($caseTypes))
                                @foreach ($caseTypes as $caseType)
                                    <li data-value="{{ $caseType['case_type'] }}" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectCaseTypeOption(this); getCaseType(this)">
                                        {{ $caseType['type_name'] }} ( {{ $caseType['full_form'] }} )
                                    </li>
                                @endforeach
                            @else
                                <li data-value="" class="p-2 cursor-not-allowed text-gray-500">No Case Types Available</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <div class="form-row">
        <div class="form-field">
            <label for="case-no-hc" id="field1-label">Case No: <span class="red">*</span></label>
            <input type="text" id="case-no-hc" name="case_no" placeholder="Enter Case No" required>
        </div>
        <div class="form-field">
            <label for="case-year-hc" id="field2-label">Case Year: <span class="red">*</span></label>
            <input type="text" id="case-year-hc" name="case_year" placeholder="Enter Case Year" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-field">
            <label for="request-mode">Request Mode: <span>*</span></label>
            <div class="mt-2">
                <input type="radio" id="urgent" name="request_mode" value="urgent" required>
                <label for="urgent">Urgent</label>
                <input type="radio" id="ordinary" name="request_mode" value="ordinary" required class="ml-6">
                <label for="ordinary">Ordinary</label>
            </div>
        </div>
        <div class="form-field">
            <label for="required-document">Required Document: <span>*</span></label>
            <textarea id="required-document" name="required_document" placeholder="Enter Document Details" rows="3" required></textarea>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field mt-1">
            <label for="apply-by">Applied By: <span>*</span></label>
            <select id="apply-by" name="apply_by" required class="p-[12px]" onchange="toggleAdvocateField()">
                <option value="">--Select--</option>
                <option value="petitioner">Petitioner</option>
                <option value="respondent">Respondent</option>
                <option value="advocate">Advocate</option>
                <option value="others">Others</option>
            </select>
        </div>
        <div class="form-field mt-2" style="display: none;">
            <label for="case-year">Advocate Registration No <span>*</span></label>
            <input type="text" id="adv_res" name="adv_res" placeholder="Enter Advocate registration no" style="margin-top: 10px;">
        </div>
    </div>
            <div class="form-row mt-4">
        <div class="form-field">
            <label for="captcha">Evaluate the Expression<span>*</span></label>
            <div class="flex justify-center items-center gap-1">
                <img id="captchaImage" src="{{ captcha_src() }}" alt="Captcha">
                <input class="text-lg" type="text" id="captcha-hc" name="captcha" required placeholder="Enter the expression">
                <button type="button" class="refresh-btn rounded-full hover:shadow-md" onclick="refreshCaptcha()" title="Refresh Captcha">
                    <img class="w-[52px]" src="{{ asset('passets/images/icons/refresh.png')}}" alt="Refresh">
                </button>
            </div>
        </div>
        <div class="form-field">
            <button type="submit" id="submitBtn" class="btn-submit hidden sm:mt-7 order_btn" style="margin-top: 40px;" onclick="handleFormSubmitForHighCourt(event)">Submit</button>
        </div>
    </div>
        </form>
    </div>

</section>
@endsection


@push('scripts')

@endpush