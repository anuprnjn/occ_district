<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Certified Copy && Jharkhand High Court</title>
    <link rel="stylesheet" href="{{ asset('passets/style.css')}}" />
    <link rel="stylesheet" href="{{ asset('passets/additional_style.css')}}" />
    <link rel="icon" href="{{ asset('passets/images/favicon.png')}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                <svg xmlns="#" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"/>
                </svg>
            </button>
        </li>
        @php
            $isLoggedIn = (bool) session('isUserLoggedIn');
            $isGrasRespCC = Str::contains(request()->fullUrl(), 'gras_resp_cc');
            
            // Determine UI based on login status or payment page access
            $showLoggedInUI = $isLoggedIn || ($isGrasRespCC && $isLoggedIn);
        @endphp
       
        {{-- Only show Home if not showing logged-in UI --}}
        @unless($showLoggedInUI)
        <li id="home" class="active">
            <a href="/">
                <svg xmlns="#" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/>
                </svg>
                <span>HOME</span>
            </a>
        </li>
        @endunless

        {{-- Track/User Sign-in or Welcome User --}}
        @if($showLoggedInUI)
           <li id="track_app">
                <a href="javascript:void(0)" 
                class="cursor-not-allowed text-gray-400 pointer-events-none flex items-center gap-2">
                    <svg xmlns="#" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Z"/>
                    </svg>
                    <span>WELCOME USER</span>
                </a>
            </li>
        @else
            <li id="track_app">
                <a href="{{ route('trackStatus') }}">
                    <svg xmlns="#" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Z"/>
                    </svg>
                    <span>USER SIGN-IN</span>
                </a>
            </li>
        @endif

        {{-- Only show User Manual if not showing logged-in UI --}}
        @unless($showLoggedInUI)
        <li>
            <a href="{{ asset('passets/uploads/usermannual.pdf') }}" target="_blank">
                <svg xmlns="#" height="24px" viewBox="0-960 960 960" width="24px" fill="#e3e3e3">
                    <path d="M560-564v-68q33-14 67.5-21t72.5-7q26 0 51 4t49 10v64q-24-9-48.5-13.5T700-600q-38 0-73 9.5T560-564Zm0 220v-68q33-14 67.5-21t72.5-7q26 0 51 4t49 10v64q-24-9-48.5-13.5T700-380q-38 0-73 9t-67 27Zm0-110v-68q33-14 67.5-21t72.5-7q26 0 51 4t49 10v64q-24-9-48.5-13.5T700-490q-38 0-73 9.5T560-454ZM260-320q47 0 91.5 10.5T440-278v-394q-41-24-87-36t-93-12q-36 0-71.5 7T120-692v396q35-12 69.5-18t70.5-6Zm260 42q44-21 88.5-31.5T700-320q36 0 70.5 6t69.5 18v-396q-33-14-68.5-21t-71.5-7q-47 0-93 12t-87 36v394Zm-40 118q-48-38-104-59t-116-21q-42 0-82.5 11T100-198q-21 11-40.5-1T40-234v-482q0-11 5.5-21T62-752q46-24 96-36t102-12q58 0 113.5 15T480-740q51-30 106.5-45T700-800q52 0 102 12t96 36q11 5 16.5 15t5.5 21v482q0 23-19.5 35t-40.5 1q-37-20-77.5-31T700-240q-60 0-116 21t-104 59Z"/>
                </svg>
                <span>USER MANUAL</span>
            </a>
        </li>
        @endunless

        {{-- Logout or Admin Login --}}
        @if($showLoggedInUI)
        <li class="logout_btn">
            <form method="GET" action="{{ $isGrasRespCC ? url('/') : url('/logout-tracking') }}">
            @csrf
                <button
                title="Click to logout" 
                type="submit"
                    class="w-full flex items-center gap-2 px-4 py-2 text-white font-medium bg-red-600 hover:bg-red-700 transition-colors duration-200
                        sm:rounded-md rounded-none h-[62px] sm:h-auto">
                    <svg style="fill: white;" class="w-6 h-6 -ml-2 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M806-440H320v-80h486l-62-62 56-58 160 160-160 160-56-58 62-62ZM600-600v-160H200v560h400v-160h80v160q0 33-23.5 56.5T600-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h400q33 0 56.5 23.5T680-760v160h-80Z"/>
                    </svg>
                    <span>LOGOUT</span>
                </button>
            </form>
        </li>
        @else
        <li class="logout_btn">
            <a target="new" href="{{ route('login') }}" style="background-color: #D09A3F; color: white; font-weight: 500; text-decoration: none;" 
            onmouseover="this.style.backgroundColor='#4B3E2F'" 
            onmouseout="this.style.backgroundColor='#D09A3F'">
                    <img src="{{ asset('passets/images/icons/admin_login.svg') }}" alt="">
                <span class="tracking-wide">ADMIN LOGIN</span>
            </a>
        </li>
        @endif
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
                <a href="{{ route('screenReader') }}" class="screen-reader-link">Screen Reader Access 
                    <img src="{{ asset('passets/images/icons/screen_reader.svg') }}" alt="">
                </a>
            </span>
        </div>

        <!-- Right Side -->
        <div class="right-section">
            <div class="light_dark_div_resp">
                <label class="switch" title="Dark/Light Mode Toggle">
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
        <h2 class='sm:text-5xl text-[1.66rem] mb-1'>ONLINE CERTIFIED COPY</h2>
    </div>
