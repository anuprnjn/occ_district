<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Jharkhand High Court</title>
    
    <link rel="stylesheet" href="{{ asset('passets/style.css')}}" />
    <link rel="stylesheet" href="{{ asset('passets/additional_style.css')}}" />
    <link rel="icon" href="{{ asset('passets/images/favicon.png')}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="{{ asset('passets/js/tailwind.js')}}"></script>
    <script src="{{ asset('passets/js/jquery_7.1.js')}}"></script>
</head>
<body class="flex items-center justify-center h-screen bg-cover bg-center" 
      style="background-image: url('{{ asset('passets/images/law.jpg') }}');">

    <div class="bg-slate-100/70 shadow-md rounded-lg p-6 max-w-md w-full backdrop-blur-lg">
        <h3 class="text-2xl font-bold text-center mb-6 flex items-center w-full justify-center gap-2"><img src="{{ asset('passets/images/icons/userlogin.svg')}}" class="w-12">REGISTERED USER LOGIN</h3>

        <!-- Court Selection Dropdown -->
        <div class="mb-4">
            <label class='text-sm'>Select Court <span class="text-red-500">*</span></label>
            <select id="courtType" class=" block p-[11px] mt-2">
                <option value="high_court">High Court User Login</option>
                <option value="civil_court">Civil Court User Login</option>
            </select>
        </div>

        <!-- Login Form -->
        <form id="loginForm">
            <!-- User ID -->
            <div class="mb-4">
                <label class='text-sm'>User ID <span class="text-red-500">*</span></label>
                <input type="text" id="userID" name="userID" placeholder="Enter your User ID" required class='mt-2'>
            </div>

            <!-- Password Field with Eye Icon -->
            <div class="mb-4">
                <label class='text-sm'>Password <span class="text-red-500">*</span></label>
                <div class="relative">
                <input type="password" id="password" name="password" placeholder="Enter your password" required class='mt-2'>
                    <button type="button" id="togglePassword" class="absolute mt-2 inset-y-1 right-3 flex items-center">
                        <img id="eyeOpen" src="{{ asset('passets/images/icons/eyeopen.svg')}}" alt="Show" class="w-6">
                        <img id="eyeClosed" src="{{ asset('passets/images/icons/eyeclose.svg')}}" alt="Hide" class="w-6 hidden">
                    </button>
                </div>
            </div>
            <div class="form-field">
                <label for="captcha">Enter the Captcha<span>*</span></label>
                <div class="flex justify-center items-center gap-1">
                    <img id="captchaImage" src="{{ captcha_src() }}" alt="Captcha">
                    <input class="text-lg" type="text" id="captcha" name="captcha" required placeholder="Enter the captcha">
                    <button type="button" class="refresh-btn rounded-full hover:shadow-md" onclick="refreshCaptcha()" title="Refresh Captcha">
                        <img class="w-[52px]" src="{{ asset('passets/images/icons/refresh.png')}}" alt="Refresh">
                    </button>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full btn-submit order_btn" onclick='userLogin(event)'>
                    Login
                </button>
            </div>
        </form>
    </div>

<script>
    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
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
</body>
</html>