@extends('public_layouts.app')

@section('content')

<section class="content-section h-[60vh]">
    <h3 class="font-semibold text-xl -mt-8">Download Certified Copy</h3>

    <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10" id='trackApplicationForm'>
        <div class="form-group">
            <label>
                <input type="radio" name="search-type" value="HC" checked>
                High Court
            </label>
            <label>
                <input type="radio" name="search-type" value="DC">
                Civil Court
            </label>
        </div>
        <div class="flex justify-center sm:flex-row flex-col items-center sm:gap-10">
            <div class="form-field">
                <label for="application_number">Application Number: <span>*</span></label>
                <input type="text" id="application_number" name="application_number" placeholder="Enter Application Number" class="sm:mb-5">
            </div>    
            <div class="form-field">
                <button type="submit" class="sm:w-[50%] w-[100%] btn-submit order_btn mt-4" onClick="trackApplication(event)">Submit</button>
            </div>
        </div>
        <span id="error_span" class="text-red-500 font-bold text-sm ml-5 sm:ml-0"></span>
    </form>
</section>

@endsection

@push('scripts')
   

@endpush