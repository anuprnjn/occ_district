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
                    <label for="case-type">Case Type:</label>
                    <select id="case-type" name="case-type" class="p-[12.5px]">
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
    </div>

    <!-- Form for Apply for Others Copy -->
    <div class="otherform" id="otherForm" style="display:none;">
        <form id="applyOrdersForm" class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10">
            <h3 class="font-semibold text-lg mb-4">Apply for Others Copy :</h3>
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
        </form>
    </div>

</section>
@endsection


@push('scripts')

@endpush