<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Certified Copy || Jharkhand High Court</title>
    <link rel="stylesheet" href="{{ asset('passets/style.css')}}" />
    <link rel="stylesheet" href="{{ asset('passets/additional_style.css')}}" />
    <link rel="icon" href="{{ asset('passets/images/favicon.png')}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<script src="{{ asset('passets/js/tailwind.js')}}"></script>
<script src="{{ asset('passets/js/jquery_7.1.js')}}"></script>
<body>
    <nav id="sidebar">
        <ul class="font-semibold">
            <li>
                <span class="logo">
                    <img 
                    src="{{ asset('passets/images/HC-main.png') }}" 
                    alt="logo" 
                    id="logo"
                    data-light-logo="{{ asset('passets/images/HC-main.png') }}" 
                    data-dark-logo="{{ asset('passets/images/HC-white.png') }}">
                </span>
                <button onclick="toggleSidebar()" id="toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                        <path d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"/>
                    </svg>
                </button>
            </li>
            
            <li id="home" class="active">
                <a href="/">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
                    <span>Home</span>
                </a>
            </li>
            <li id="track_app">
                <a href="{{ route('trackStatus') }}">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m105-399-65-47 200-320 120 140 160-260 120 180 135-214 65 47-198 314-119-179-152 247-121-141-145 233Zm475 159q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29ZM784-80 676-188q-21 14-45.5 21t-50.5 7q-75 0-127.5-52.5T400-340q0-75 52.5-127.5T580-520q75 0 127.5 52.5T760-340q0 26-7 50.5T732-244l108 108-56 56Z"/></svg>
                    <span>Track Application</span>
                </a>
            </li>
            <li id="pending_payments">
                <a href="{{ route('pendingPayments') }}">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M549-120 280-400v-80h140q53 0 91.5-34.5T558-600H240v-80h306q-17-35-50.5-57.5T420-760H240v-80h480v80H590q14 17 25 37t17 43h88v80h-81q-8 85-70 142.5T420-400h-29l269 280H549Z"/></svg>
                    <span>Pending payments</span>
                </a>
            </li>
            <li id="">
                <a href="{{ asset('passets/uploads/usermannual.pdf') }}" target="new">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M360-460h40v-80h40q17 0 28.5-11.5T480-580v-40q0-17-11.5-28.5T440-660h-80v200Zm40-120v-40h40v40h-40Zm120 120h80q17 0 28.5-11.5T640-500v-120q0-17-11.5-28.5T600-660h-80v200Zm40-40v-120h40v120h-40Zm120 40h40v-80h40v-40h-40v-40h40v-40h-80v200ZM320-240q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320Zm0-80h480v-480H320v480ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm160-720v480-480Z"/></svg>
                    <span>User Mannual</span>
                </a>
            </li>
           
            <li class="logout_btn">
                <a href="{{ route('login') }}" style="background-color: #D09A3F; color: white; font-weight: 500; text-decoration: none;" 
                onmouseover="this.style.backgroundColor='#4B3E2F'" 
                onmouseout="this.style.backgroundColor='#D09A3F'">
                <svg style="fill: white;" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M806-440H320v-80h486l-62-62 56-58 160 160-160 160-56-58 62-62ZM600-600v-160H200v560h400v-160h80v160q0 33-23.5 56.5T600-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h400q33 0 56.5 23.5T680-760v160h-80Z"/></svg>
                    <span>LOGIN</span>
                </a>
            </li>
           
        </ul>
    </nav>
    <main>
    <div class="main-top">
    <!-- Left Side -->
        <div class="left-section">
            <span>
                <a href="#main-content" class="skip-link">Skip to Main Content</a>
            </span>
            <span class="divider"></span>
            <span>
                <a href="#screen-reader-access" class="screen-reader-link">Screen Reader Access <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M320-80q-83 0-141.5-58.5T120-280q0-83 58.5-141.5T320-480v80q-50 0-85 35t-35 85q0 50 35 85t85 35q50 0 85-35t35-85h80q0 83-58.5 141.5T320-80Zm360-40v-200H440q-44 0-68-37.5t-6-78.5l74-164h-91l-24 62-77-22 28-72q9-23 29.5-35.5T350-680h208q45 0 68.5 36.5T632-566l-66 146h114q33 0 56.5 23.5T760-340v220h-80Zm-40-580q-33 0-56.5-23.5T560-780q0-33 23.5-56.5T640-860q33 0 56.5 23.5T720-780q0 33-23.5 56.5T640-700Z"/></svg></a>
            </span>
        </div>

        <!-- Right Side -->
        <div class="right-section">
            <div class="light_dark_div_resp">
              
              <label class="switch">
                <input type="checkbox" id="mode-toggle">
                <span class="slider round"></span>
              </label>
             
              </div>
            <span class="divider"></span>
            <span id="current-time">Time & Date</span>
        </div>
    </div>

    <div class="line-animation" id="line"></div>
    <div class="top-bar">
    <h2 class='sm:text-5xl text-2xl mb-1'>ONLINE CERTIFIED COPY</h2>

    </div>