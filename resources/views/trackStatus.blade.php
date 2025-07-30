@extends('public_layouts.app')

@section('content')

<section class="content-section h-[68vh]">
    <h3 class="font-semibold text-xl -mt-8">Online Certified Copy Portal - Sign In</h3>
    <form class="dark_form p-4 mt-10 bg-slate-100/70 rounded-md mb-10" id='trackApplicationForm'>
        <div class="form-group -ml-1">
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
            <div class="form-field w-[100%] sm:w-[100%]">
                <label for="application_number">Mobile Number / Application Number  <span>*</span></label>
                <input type="text" id="application_number" name="application_number" placeholder="Enter Mobile Number / Application Number" class="sm:mb-5 h-[45px]">
            </div>  
            <div class="hidden form-field mt-4 sm:mt-0 w-[100%] sm-w-[100%]" id="mobile_otp">
                <div class="flex items-center justify-start gap-3">
                    <label for="otp" id="otp_label">Enter OTP :  <span>*</span></label>
                    <span id="otpTimertrack" class="sm:text-md text-sm text-rose-600 -mt-1"></span>
                </div>
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" class="sm:mb-5 h-[45px]">
            </div>  
            <div class="form-field flex flex-col justify-center items-center sm:block mt-2 sm:mt-0">
                <button id="otpButtonTrack" type="button" class="rounded-md sm:w-[50%] w-[120%] btn-submit order_btn mt-4" onClick="checkInputType(event)">GO</button>
            </div>
        </div>
        <span id="error_span" class="text-red-500 font-normal text-sm ml-5 sm:ml-0 sm:text-left text-center mt-4 sm:mt-0 block"></span>
    </form>
</section>

@endsection

@push('scripts')

<script type="text/javascript" src="{{ asset('passets/js/extra_script.js')}}" defer></script>

<!-- script to add js controls to the input box  -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const otpInput = document.getElementById('otp');

    if (otpInput) {
        otpInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });
        ['paste', 'copy', 'cut'].forEach(evt =>
            otpInput.addEventListener(evt, e => e.preventDefault())
        );
        otpInput.addEventListener('contextmenu', e => e.preventDefault());
    }
});
</script>
<!-- script to check the mobile number or application number  -->
<script>
    function checkInputType(event) {
        event.preventDefault();

        const otp_input = document.getElementById("mobile_otp");
        const errorSpan = document.getElementById('error_span');
        const input = document.getElementById('application_number').value.trim();
        if(input == ''){
           errorSpan.innerHTML='Please enter application number or mobile number.';
           return;
        }

        const isValidMobile = /^[6-9]\d{9}$/.test(input);
       
        const isOnlyDigits = /^\d+$/.test(input);
        const isTenDigits = input.length === 10;
    
        if (isOnlyDigits && !isValidMobile) {
            errorSpan.innerHTML='Mobile number should be 10 digits.';
            return;
        }
        var selectedCourt = document.querySelector('input[name="search-type"]:checked').value;
        if (isValidMobile) {
            // get the valid mobile number 
            const validatedMobile = input;
           
            //var selectedCourt = document.querySelector('input[name="search-type"]:checked').value;
            if(selectedCourt === 'HC'){
                fetch('/check-mobile-number-hc', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ 
                        input_type: 'mobile_number',
                        input_value: validatedMobile, 
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetch('/set-track-response-hc',{
                            method : 'POST',
                            headers:{
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ data: data }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success){
                                console.log(data.message);
                                
                            }else{
                                console.log("Error while setting track data to session");
                            }
                        });
                            const orderCopyLength = data.data.order_copy.length;
                            const otherCopyLength = data.data.other_copy.length;
                            if (orderCopyLength > 0 || otherCopyLength > 0) {
                                sendOtpTrack(selectedCourt,validatedMobile);
                                const maskedMobile = input.replace(/^(\d{2})\d{4}(\d{4})$/, '$1xxxx$2');
                                otp_input.classList.remove('hidden');
                                errorSpan.style.color = 'green';
                                errorSpan.innerHTML = `OTP has been sent to mobile number - <span style="color: red;">${maskedMobile}</span>.`;

                            } else {
                                errorSpan.textContent="Mobile number not registered !";
                            }
                    }else{
                       alert("Internal server error !");
                        return;       
                    }
                });
            }else{
                fetch('/check-mobile-number-dc', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ 
                        input_type: 'mobile_number',
                        input_value: validatedMobile,
                     }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetch('/set-track-response-dc',{
                            method : 'POST',
                            headers:{
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ data: data }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success){
                                console.log(data.message);
                            }else{
                                console.log("Error while setting track data to session");
                            }
                        });
                            const orderCopyLength = data.data.order_copy.length;
                            const otherCopyLength = data.data.other_copy.length;
                            if (orderCopyLength > 0 || otherCopyLength > 0) {
                                sendOtpTrack(selectedCourt,validatedMobile);
                                const maskedMobile = input.replace(/^(\d{2})\d{4}(\d{4})$/, '$1xxxx$2');
                                otp_input.classList.remove('hidden');
                                errorSpan.style.color = 'green';
                                errorSpan.innerHTML = `OTP has been sent to mobile number - <span style="color: red;">${maskedMobile}</span>.`;

                            } else {
                                errorSpan.textContent="Mobile number not registered !";
                            }
                    }else{
                       alert("Internal server error !");
                        return;       
                    }
                });
            }

        } else {
            if(selectedCourt === 'HC'){
                fetch('/check-mobile-number-hc', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ 
                        input_type: 'application_number',
                        input_value: input, 
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetch('/set-track-response-hc',{
                            method : 'POST',
                            headers:{
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ data: data }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success){
                                console.log(data.message);
                                
                            }else{
                                console.log("Error while setting track data to session");
                            }
                        });
                            const orderCopyLength = data.data.order_copy.length;
                            const otherCopyLength = data.data.other_copy.length;
                            if (orderCopyLength > 0 || otherCopyLength > 0) {
                                const validatedMobile =
                                (otherCopyLength > 0 && data.data.other_copy[0].mobile_number) ||
                                (orderCopyLength > 0 && data.data.order_copy[0].mobile_number) ||
                                '';
                                sendOtpTrack(selectedCourt,validatedMobile);
                                const maskedMobile = validatedMobile.replace(/^(\d{2})\d{4}(\d{4})$/, '$1xxxx$2');
                                otp_input.classList.remove('hidden');
                                errorSpan.style.color = 'green';
                                errorSpan.innerHTML = `OTP has been sent to mobile number - <span style="color: red;">${maskedMobile}</span>.`;

                            } else {
                                errorSpan.textContent="Application number not found !";
                            }
                    }else{
                       alert("Internal server error !");
                        return;       
                    }
                });

            } else {
                fetch('/check-mobile-number-dc', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ 
                        input_type: 'application_number',
                        input_value: input,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetch('/set-track-response-dc',{
                            method : 'POST',
                            headers:{
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ data: data }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success){
                                console.log(data.message);
                            }else{
                                console.log("Error while setting track data to session");
                            }
                        });
                            const orderCopyLength = data.data.order_copy.length;
                            const otherCopyLength = data.data.other_copy.length;
                            if (orderCopyLength > 0 || otherCopyLength > 0) {
                                const validatedMobile =
                                (otherCopyLength > 0 && data.data.other_copy[0].mobile_number) ||
                                (orderCopyLength > 0 && data.data.order_copy[0].mobile_number) ||
                                '';
                                sendOtpTrack(selectedCourt,validatedMobile);
                                const maskedMobile = validatedMobile.replace(/^(\d{2})\d{4}(\d{4})$/, '$1xxxx$2');
                                otp_input.classList.remove('hidden');
                                errorSpan.style.color = 'green';
                                errorSpan.innerHTML = `OTP has been sent to mobile number - <span style="color: red;">${maskedMobile}</span>.`;

                            } else {
                                errorSpan.textContent="Application number not found !";
                            }
                    }else{
                       alert("Internal server error !");
                        return;       
                    }
                });
            }
        }
    }

</script>   


@endpush