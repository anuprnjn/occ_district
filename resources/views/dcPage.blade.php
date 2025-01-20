@extends('public_layouts.form')

@section('content')
<section class="w-full">
    <h3 class="font-semibold text-xl mb-4 p-2">Apply for Others Copy </h3>

    <div class="dark_form flex sm:flex-row flex-col justify-center items-center w-full sm:space-x-4 bg-slate-100/70 p-4 rounded-md -mb-3">

        <div class="w-full sm:w-1/2">
            <label for="selectDist" class="mb-2 block pl-2">Select District:<span>*</span></label>
            <div class="relative w-full dark_select">
                <!-- Custom Dropdown -->
                <div id="dropdown" class="w-full p-[8px] border rounded ">
                    <div id="dropdownToggle" class="cursor-pointer" onclick="toggleDropdown()">Select District</div>
                    <div id="dropdownMenu" class="hidden absolute top-full left-0 w-full max-h-60 border border-gray-300 dark_select overflow-y-auto rounded shadow-lg z-10">
                        <!-- Search Box -->
                        <div class="p-2">
                            <input type="text" id="searchInput" class="w-full p-[8px] border border-gray-300 rounded" placeholder="Search District..." onkeyup="filterOptions()">
                        </div>
                        <!-- Options -->
                        <ul id="dropdownOptions" class="list-none p-0 m-0">
                            <li data-value="" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption(this)">Select District</li>
                            @foreach ($districts as $district)
                                <li data-value="{{ $district['dist_code'] }}" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption(this)">
                                    {{ $district['dist_name'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="w-full sm:w-1/2">
            <label for="selectEsta" class="mb-2 block">Select Establishment:<span>*</span></label>
            <select id="selectEsta" class="w-full p-[10px] border border-gray-300 rounded">
                <option value="" selected>Select Establishment</option>
            </select>
        </div>
    </div>

    <form id="applyOrdersForm" class="dark_form p-4 bg-slate-100/70 rounded-md mb-10">
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
            >
            <button
            type="button"
            id="otpButton"
            onclick="sendOtp()"
            class="bg-[#4B3E2F] sm:w-[200px] w-[150px] sm:p-[10px] p-[8px] rounded-md text-white hover:bg-[#D09A3F]"
            >
                Send OTP
            </button>
                 
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
                <input type="radio" id="urgent" name="request_mode" value="urgent" required>
                <label for="urgent">Case No</label>
                <input type="radio" id="ordinary" name="request_mode" value="ordinary" required>
                <label for="ordinary">Filling No</label>
            </div>
        </div>
        <div class="form-field">
            <label for="case-type">Case Type: <span>*</span></label>
            <div class="relative w-full dark_select">
                <!-- Custom Dropdown -->
                <div id="caseTypeDropdown" class="w-full p-[10px] border rounded ">
                    <div id="caseTypeToggle" class="cursor-pointer" onclick="toggleCaseTypeDropdown()">Please Select</div>
                    <div id="caseTypeMenu" class="hidden absolute top-full left-0 w-full max-h-60 border border-gray-300 dark_select overflow-y-auto rounded shadow-lg z-10">
                        <!-- Search Box -->
                        <div class="p-2">
                            <input type="text" id="caseTypeSearchInput" class="w-full p-[10px] border border-gray-300 rounded" placeholder="Search Case Type..." onkeyup="filterCaseTypeOptions()">
                        </div>
                        <!-- Options -->
                        <ul id="caseTypeOptions" class="list-none p-0 m-0">
                            <li data-value="" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectCaseTypeOption(this)">Please Select</li>
                            @if (!empty($caseTypes) && is_array($caseTypes))
                                @foreach ($caseTypes as $caseType)
                                    <li data-value="{{ $caseType['case_type'] }}" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectCaseTypeOption(this)">
                                        {{ $caseType['type_name'] }}
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
            <label for="case-no">Case No: <span>*</span></label>
            <input type="text" id="case-no" name="case_no" placeholder="Enter Case No" required>
        </div>
        <div class="form-field">
            <label for="case-year">Case Year: <span>*</span></label>
            <input type="text" id="case-year" name="case_year" placeholder="Enter Case Year" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-field">
            <label for="request-mode">Request Mode: <span>*</span></label>
            <div class="mt-2">
                <input type="radio" id="urgent" name="request_mode" value="urgent" required>
                <label for="urgent">Urgent</label>
                <input type="radio" id="ordinary" name="request_mode" value="ordinary" required>
                <label for="ordinary">Ordinary</label>
            </div>
        </div>
        <div class="form-field">
            <label for="required-document">Required Document: <span>*</span></label>
            <textarea id="required-document" name="required_document" placeholder="Enter Document Details" rows="3" required></textarea>
        </div>
    </div>
    <div class="form-row">
        <div class="form-field">
            <label for="apply-by">Apply By: <span>*</span></label>
            <select id="apply-by" name="apply_by" required class="p-[10px]">
                <option value="">--Select--</option>
                <!-- Add more options here -->
            </select>
        </div>
        <div class="form-field">
            <label for="case-year">Advocate Registration No <span>*</span></label>
            <input type="text" id="adv_res" name="adv_res" placeholder="Enter Advocate registration no" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-field">
            <label for="captcha">Enter Captcha Code Here: </label>
            <input type="text" id="captcha" name="captcha" required>
            <img src="path-to-captcha.jpg" alt="Captcha">
            <button type="button" class="refresh-captcha">
                <img src="refresh-icon-path.jpg" alt="Refresh">
            </button>
        </div>
    </div>
    <div>
        <button type="submit" class="btn-submit hidden order_btn">Submit</button>
    </div>
    </form>
</section>
@endsection
