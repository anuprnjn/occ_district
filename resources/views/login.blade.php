@extends('public_layouts.app')

@section('content')

<section class="content-section">
    <h3 class="font-semibold text-xl -mt-8 flex items-center justify-start gap-2 bg-[#D09A3F]/10 rounded-md p-2"><img src="{{ asset('passets/images/icons/userlogin.svg')}}" class="w-12">Registered User Login </h3>

    <form autocomplete='off' class=" w-[] dark_form p-4 mt-4 bg-slate-100/70 border rounded-md mb-10 pb-10 pt-4" id='trackApplicationForm'>
        <div class="form-group">
            <select name="court_login" id="courtLogin" class='w-[50%] p-[11px]'>
                <option value="HC">High Court Login</option>
                <option value="DC">Civil Court Login</option>
            </select>
        </div>
        <div class="flex justify-center sm:flex-row flex-col items-center sm:gap-10 w-[50%]">
            
            <div class="form-field ">
                <!-- <label for="userID"> Username: <span>*</span></label> -->
                <input type="text" id="userID" name="userID" placeholder="Enter username" class="" >
                <!-- <label for="password"> Password: <span>*</span></label> -->
                <div class="relative w-full">
                    <input type="password" id="password" name="password" placeholder="Enter Password" 
                        class="w-full p-2 pr-12 border rounded-md bg-gray-800 text-white">
                    <button type="button" id="togglePassword" 
                        class="absolute inset-y-1 right-3 flex items-center justify-center rounded-full ">
                        <img id="eyeOpen" src="{{ asset('passets/images/icons/eyeopen.svg')}}" alt="Show" class="w-6" style="display:block;">
                        <img id="eyeClosed" src="{{ asset('passets/images/icons/eyeclose.svg')}}" alt="Hide" class="w-6" style="display:none;">
                    </button>
                </div>
                <label for="captcha">Enter the Captcha<span>*</span></label>
                <div class="flex justify-center items-center gap-1">
                    <img id="captchaImage" src="{{ captcha_src() }}" alt="Captcha">
                    <input class="text-lg" type="text" id="captcha" name="captcha" required placeholder="Enter the captcha">
                    <button type="button" class="refresh-btn rounded-full hover:shadow-md" onclick="refreshCaptcha()" title="Refresh Captcha">
                        <img class="w-[52px]" src="{{ asset('passets/images/icons/refresh.png')}}" alt="Refresh">
                    </button>
                </div>
                <button type="submit" class=" w-[100%] btn-submit order_btn mt-4 flex items-center justify-center gap-1" onClick="userLogin(event)"> LOGIN <img src="{{ asset('passets/images/icons/loginicon.svg')}}" alt=""> </button>
            </div>    
        </div>
       
    </form>
</section>

@endsection

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');
    
    // Toggle Password Visibility
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
});
</script>    
<script>
function userLogin(event){
    event.preventDefault();
    const userId = document.getElementById('userID').value.trim();
    const pass = document.getElementById('password').value.trim();
    const captcha = document.getElementById('captcha').value.trim();
    if(userId === ''){
        alert('Please enter UserID')
        return;
    }if(pass === ''){
        alert('Please enter Password')
        return;
    }if(captcha === ''){
        alert('Please enter Captcha')
        return;
    }
    fetch('/validate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',  // Ensure the response is expected in JSON format
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            captcha: captcha,
        }),
    })
    .then(response => response.json()) // Ensure you're parsing the JSON response
    .then(data => {
        if (!data.success) {
            alert('CAPTCHA validation failed. Please try again.');
            document.getElementById('captcha').value = '';  // Clear captcha input field
            refreshCaptcha(); // Optional: refresh captcha image
            return;  // Stop further validation if CAPTCHA fails
        }
    })
    .catch(error => {
        console.error('CAPTCHA validation error:', error);
        alert('An error occurred while validating the CAPTCHA.');
    });
}
</script>   
<script>
    function refreshCaptcha() {
        const refresh = document.querySelector(".refresh-btn");
        const captchaImg = document.getElementById('captchaImage');
        
        // Add animation class to the refresh button
        refresh.classList.add("animate-spin");

        // Make an AJAX request to the route that generates the CAPTCHA
        fetch('/refresh-captcha')
            .then(response => response.json())
            .then(data => {
                // Update the CAPTCHA image with the new source URL
                captchaImg.src = data.captcha_src + '?' + new Date().getTime(); 
            })
            .catch(error => {
                console.error('Error refreshing CAPTCHA:', error);
            })
            .finally(() => {
                // Remove the spin animation after the request
                setTimeout(function() {
                    refresh.classList.remove("animate-spin");
                }, 1000);
            });
    }
</script>

@endpush