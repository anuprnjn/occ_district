<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | Jharkhand High Court</title>
    
    <link rel="stylesheet" href="{{ asset('passets/style.css')}}" />
    <link rel="stylesheet" href="{{ asset('passets/additional_style.css')}}" />
    <link rel="icon" href="{{ asset('passets/images/favicon.png')}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="{{ asset('passets/js/tailwind.js')}}"></script>
    <script src="{{ asset('passets/js/jquery_7.1.js')}}"></script>
</head>
<body class="flex items-center justify-center h-screen bg-cover bg-center" 
      style="background-image: url('{{ asset('passets/images/justice.jpg') }}');">

    <div class="bg-slate-100/70 shadow-md rounded-lg p-6 max-w-md w-full backdrop-blur-lg">
        <h3 class="text-2xl font-bold text-center mb-6 flex items-center w-full justify-center gap-2"><img src="{{ asset('passets/images/icons/userlogin.svg')}}" class="w-12">REGISTERED USER LOGIN</h3>
        <div id="error-message" class="text-red-500 text-center -mt-4 hidden"></div>
        <!-- Court Selection Dropdown -->
        <div class="mb-4">
            <label class='text-sm'>Select Court <span class="text-red-500">*</span></label>
            <select id="courtType" class=" block p-[11px] mt-2">
                <option value="HC">High Court User Login</option>
                <option value="DC">Civil Court User Login</option>
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
                <input type="password" id="password" name="password" placeholder="Enter your password" required class="mt-2" autocomplete="current-password">
                    <button type="button" id="togglePassword" class="absolute mt-2 inset-y-1 right-3 flex items-center">
                        <img id="eyeOpen" src="{{ asset('passets/images/icons/eyeopen.svg')}}" alt="Show" class="w-6">
                        <img id="eyeClosed" src="{{ asset('passets/images/icons/eyeclose.svg')}}" alt="Hide" class="w-6 hidden">
                    </button>
                </div>
            </div>
            <div class="form-field">
                <label for="captcha">Evaluate the Expression<span>*</span></label>
                <div class="flex justify-center items-center gap-1">
                    <!-- <img id="captchaImage" src="{{ session('captcha') }}" alt="Captcha" class="rounded-md"> -->
                    <img id="captchaImage" src="" alt="Captcha" class="rounded-md">
                    <input class="text-lg" type="text" id="captcha" name="captcha" required placeholder="Enter the expression">
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
    window.onload = function() {
        fetch('/get-login-captcha')
            .then(response => response.json())
            .then(data => {
                // console.log("CAPTCHA fetched successfully:", data);
                // Update the image source with the new CAPTCHA
                document.getElementById('captchaImage').src = data.captcha_src;
            })
            .catch(error => {
                console.error("Error fetching CAPTCHA:", error);
                // alert("Error fetching CAPTCHA: " + error);
            });
    };
</script>  
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
async function userLogin(event) {
    event.preventDefault();

    const userId = document.getElementById('userID').value.trim();
    const pass = document.getElementById('password').value.trim();
    const courtType = document.getElementById('courtType').value;
    const captcha = document.getElementById('captcha').value.trim();
    const errorDiv = document.getElementById('error-message'); 
    const loginButton = document.querySelector('.btn-submit'); 
    
    errorDiv.classList.add("hidden");
    errorDiv.innerText = "";

    if (!userId) return showError("Please enter User ID");
    if (!pass) return showError("Please enter Password");
    if (!captcha) return showError("Please enter Captcha");

    try {
        loginButton.disabled = true;
        loginButton.innerText = "Logging in...";

        // Validate CAPTCHA
        let captchaResponse = await fetch('/validate-captcha', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ captcha: captcha })
        });

        let captchaData = await captchaResponse.json();
        if (!captchaData.success) {
            refreshCaptcha();
            return showError("CAPTCHA validation failed. Please try again.");
        }

        // Perform Login
        let loginResponse = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ username: userId, password: pass, courtType: courtType })
        });

        let loginData = await loginResponse.json();

        if (loginData.success) {
            window.location.href = loginData.redirect;
        } else {
            refreshCaptcha();
            showError(loginData.message || "Invalid credentials. Please try again.");
        }
    } catch (error) {
        console.error("Login Error:", error);
        showError("Something went wrong. Please try again.");
    } finally {
        loginButton.disabled = false;
        loginButton.innerText = "Login";
    }
}

// Function to show error messages
function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.innerText = message;
    errorDiv.classList.remove("hidden");
}
</script>    

<script>
    function refreshCaptcha() {
        const refreshBtn = document.querySelector(".refresh-btn img"); // Select the refresh icon
        const captchaImg = document.getElementById('captchaImage');

        // Add spin animation
        refreshBtn.classList.add("animate-spin");

        fetch('/refresh-captcha')
            .then(response => response.json())
            .then(data => {
                if (data.captcha_src) {
                    captchaImg.src = data.captcha_src;
                    document.getElementById('captcha').value = '';
                } else {
                    console.error('Failed to update CAPTCHA');
                }
            })
            .catch(error => {
                console.error('Error refreshing CAPTCHA:', error);
            })
            .finally(() => {
                setTimeout(() => refreshBtn.classList.remove("animate-spin"), 1000);
            });
    }
</script>
</body>
</html>