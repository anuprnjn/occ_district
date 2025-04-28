@extends('public_layouts.form')

@section('content')
<section class="w-full">
<div id="civilCourtDropdown" class="dropdown w-[100%] sm:w-[50%] p-[10px] sm:-ml-2 mb-10">
    <label for="civilCourtSelect" class="mb-4">Select an option:</label>
    <select id="civilCourtSelect" class="p-[10px]" onchange="toggleDistForm()">
        <option value="applyJudgementDC" selected>Apply for Orders and Judgement Copy (Civil Court)</option>
        <option value="applyOrdersDC">Apply for Others Copy (Civil Court)</option>
    </select>   
</div>

<!-- This is apply for others copy form  -->
<form id="applyOrdersFormDC" class="dark_form p-4 bg-slate-100/70 rounded-md mb-10" style="display:none;">
    <h3 class="font-semibold text-lg">Apply for Others Copy (Civil Court) :</h3>
    <div class="dark_form flex sm:flex-row flex-col justify-center items-center w-full rounded-md gap-4 mb-4 mt-2">

        <div class="w-full sm:w-1/2">
            <label for="selectDist" class="mb-2 block">Please Select District:<span>*</span></label>
            <div class="relative w-full dark_select">
                <!-- Custom Dropdown -->
                <div id="dropdown" class="w-full p-[8px] border rounded ">
                    <div id="dropdownToggle" class="cursor-pointer" onclick="toggleDropdown()">Please Select District</div>
                    <div id="dropdownMenu" class="hidden absolute top-full left-0 w-full max-h-60 border border-gray-300 dark_select overflow-y-auto rounded shadow-lg z-10">
                        <!-- Search Box -->
                        <div class="p-2">
                            <input type="text" id="searchInput" class="w-full p-[8px] border border-gray-300 rounded" placeholder="Search District..." onkeyup="filterOptions()">
                        </div>
                        <!-- Options -->
                        <ul id="dropdownOptions" class="list-none p-0 m-0">
                            <li data-value="" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption(this); getDistCode(this)">Please Select District</li>
                            @foreach ($districts as $district)
                                <li data-value="{{ $district['dist_code'] }}" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectOption(this); getDistCode(this)">
                                    {{ $district['dist_name'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="w-full sm:w-1/2">
            <label for="selectEsta" class="mb-2 block mt-4 sm:mt-0">Select Establishment:<span>*</span></label>
            <select id="selectEsta" class="w-full p-[10px] border border-gray-300 rounded" onchange="saveEstCode(this)">
                <option value="" selected>Select Establishment</option>
            </select>
        </div>

    </div>
        @csrf
        <div class="form-row">
        <div class="form-field">
            <label for="name">Name: <span>*</span></label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
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
            value = "DC"
            onclick="sendOtp(value)"
            class="bg-[#4B3E2F] sm:w-[200px] w-[150px] sm:p-[10px] p-[8px] rounded-md text-white hover:bg-[#D09A3F]"
            >
                Send OTP
            </button>
            <!-- application details div modal  -->
            <div id="application_n_details" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[99999] hidden">
                <div class="dark_form bg-white p-6 rounded-lg shadow-lg w-[90%] sm:w-[500px] md:w-[600px] lg:w-[800px] relative">
                    <button class="absolute top-2 right-2 text-gray-500 hover:text-black" onclick="closeModal()">✖</button>
                    <h4 id="modalText" class="text-lg text-center mb-4"></h4>
                    <table id="trackedDataDC" class="w-[100%]"></table>
                </div>
            </div>
            <button id="view_recent_button" class="hidden bg-cyan-700 hover:bg-cyan-800 rounded-md text-white sm:w-[250px] w-[200px] p-[10px]" onclick="view_recent_app()">View Transactions</button>
        </div>
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
            <label for="case-no" id="field1-label">Case No: <span class="red">*</span></label>
            <input type="text" id="case-no" name="case_no" placeholder="Enter Case No" required>
        </div>
        <div class="form-field">
            <label for="case-year" id="field2-label">Case Year: <span class="red">*</span></label>
            <input type="text" id="case-year" name="case_year" placeholder="Enter Case Year" required>
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
            <label for="adv_res">Advocate Registration No <span>*</span></label>
            <input type="text" id="adv_res" name="adv_res" placeholder="Enter Advocate registration no" style="margin-top: 10px;">
        </div>
    </div>
    <div class="form-row mt-4">
        <div class="form-field">
            <label for="captcha">Evaluate the Expression<span>*</span></label>
            <div class="flex justify-center items-center gap-1">
            <img id="captchaImage" src="{{ $captcha }}" alt="Captcha" class="rounded-md">
                <input class="text-lg" type="text" id="captcha" name="captcha" required placeholder="Enter the expression">
                <button type="button" class="refresh-btn rounded-full hover:shadow-md" onclick="refreshCaptcha()" title="Refresh Captcha">
                    <img class="w-[52px]" src="{{ asset('passets/images/icons/refresh.png')}}" alt="Refresh">
                </button>
            </div>
        </div>
        <div class="form-field">
            <button type="submit" id="submitBtn" class="btn-submit hidden sm:mt-7 order_btn" style="margin-top: 40px;" onclick="handleFormSubmit(event)">Submit</button>
        </div>
    </div>
</form>


<!-- This is apply for orders and judgement copy form  -->

<div class="orderJudgement" id="orderJudgementFormDC">
       
    <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10" id="orderJudgementDC">
       @csrf
           <h3 class="font-semibold sm:text-lg text-md mb-5">Apply for Orders And Judgement Copy (Civil Court) :</h3>
           <div class="form-group">
               <label class="cursor-pointer">
                   <input type="radio" name="search-type-case" value="case" checked onchange="toggleFieldsDC(this)">
                   Case Number
               </label>
               <label class="cursor-pointer">
                   <input type="radio" name="search-type-case" value="filling" onchange="toggleFieldsDC(this)">
                   Filing Number
               </label>
           </div>
           <div class="dark_form flex sm:flex-row flex-col justify-center items-center w-full rounded-md gap-4 mb-4 mt-2">

    <div class="w-full sm:w-1/2">
        <label for="selectDist" class="mb-2 block">Please Select District:<span>*</span></label>
        <div class="relative w-full dark_select">
            <!-- Custom Dropdown -->
            <div id="dropdownDC" class="w-full p-[8px] border rounded ">
                <div id="dropdownToggleDC" class="cursor-pointer" onclick="toggleDropdownDC()">Please Select District</div>
                <div id="dropdownMenuDC" class="hidden absolute top-full left-0 w-full max-h-60 border border-gray-300 dark_select overflow-y-auto rounded shadow-lg z-10">
                    <!-- Search Box -->
                    <div class="p-2">
                        <input type="text" id="searchInputDC" class="w-full p-[8px] border border-gray-300 rounded" placeholder="Search District..." onkeyup="filterOptionsDC()">
                    </div>
                    <!-- Options -->
                    <ul id="dropdownOptionsDC" class="list-none p-0 m-0">
                        <li data-value="" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectOptionDC(this); getDistCodeDC(this)">Please Select District</li>
                        @foreach ($districts as $district)
                            <li data-value="{{ $district['dist_code'] }}" class="p-2 hover:bg-gray-100 cursor-pointer" onclick="selectOptionDC(this); getDistCodeDC(this)">
                                {{ $district['dist_name'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
    <div class="w-full sm:w-1/2">
        <label for="selectEstaDC" class="mb-2 block mt-4 sm:mt-0">Select Establishment:<span>*</span></label>
        <select id="selectEstaDC" class="w-full p-[10px] border border-gray-300 rounded" onchange="saveEstCode(this)">
            <option value="" selected>Select Establishment</option>
        </select>
    </div>

    </div>

           <div class="form-row">
    <div class="form-field">
           <label for="case-type">Case Type: <span>*</span></label>
           <div class="relative w-full dark_select">
               <!-- Custom Dropdown -->
               <div id="caseTypeDropdownForOrderJudgementDC" class="w-full p-[10px] border border-[#ccc] rounded relative">
        <div id="caseTypeToggleForOrderJudgementFormDC" 
                class="cursor-pointer overflow-hidden whitespace-nowrap text-ellipsis" 
                onclick="toggleCaseTypeDropdownForOrderJudgementDC()">Please Select Case Type</div>
        <div id="caseTypeMenuForOrderJudgementFormDC" 
        class="hidden absolute top-full left-0 w-full max-h-60 border border-[#ccc] dark_select overflow-y-auto rounded shadow-lg z-10">
       <!-- Search Box -->
       <div class="p-2">
           <input type="text" id="caseTypeSearchInputForOrderJudgementForm" 
                  class="w-full p-[10px] border border-[#ccc] rounded" 
                  placeholder="Search Case Type..." 
                  onkeyup="filterCaseTypeOptionsForOrderJudgementFormDC()">
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
              
       <div class="form-field case-field space-y-3.5" style="display: block;">
            <label for="case-no">Case Number: <span>*</span></label>
            <input type="text" id="case-no" name="case-no" placeholder="Enter Case Number" data-value="C" required>
        </div>
        <div class="form-field case-field space-y-3.5" style="display: block;">
            <label for="case-year">Case Year: <span>*</span></label>
            <input type="text" id="case-year" name="case-year" placeholder="Enter Case Year" data-value="C" required>
        </div>
        <div class="form-field filling-field space-y-3.5" style="display: none;">
            <label for="filling-no">Filing Number: <span>*</span></label>
            <input type="text" id="filling-no" name="filling-no" placeholder="Enter Filing Number" data-value="F" required>
        </div>
        <div class="form-field filling-field space-y-3.5" style="display: none;">
            <label for="filling-year">Filing Year: <span>*</span></label>
            <input type="text" id="filling-year" name="filling-year" placeholder="Enter Filing Year" data-value="F" required>
        </div>
           </div>
           <div class="form-row">
           <div class="form-field">
           <label for="captcha">Evaluate the Expression<span>*</span></label>
           <div class="flex justify-center items-center gap-1">
               <!-- <img id="captchaImageOrderJudgement" src="{{ session('captcha_image') }} alt="Captcha"> -->
               <img id="captchaImageOrderJudgement" src="{{ $captcha }}" alt="Captcha" class="rounded-md">

               <input class="text-lg" type="text" id="captcha-hc-orderJudgement" name="captcha" required placeholder="Enter the expression">
               <button type="button" class="refresh-btn-orderJudgement rounded-full hover:shadow-md" onclick="refreshCaptchaForOrderJudgement()" title="Refresh Captcha">
                   <img class="w-[52px]" src="{{ asset('passets/images/icons/refresh.png')}}" alt="Refresh">
               </button>
           </div>
       </div>
       <div class="form-field mt-10">
           <button type="submit" class="btn btn-search flex items-center justify-center gap-2" onclick="submitJudgementForm(event)" id="searchBtn">
               <span id="btnText">Search</span>
               <span id="btnSpinner" class=" animate-spin hidden border-2 border-white border-t-transparent rounded-full w-6 h-6"></span>
           </button>
       </div>
           </div>
    </form>

</div>



</section>
@endsection

