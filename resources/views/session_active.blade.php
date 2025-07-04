<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session warning || Online Certified Copy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('passets/js/tailwind.js')}}"></script>
</head>
<body class="h-screen w-full flex items-center justify-center font-[Segoe UI] bg-gradient-to-r from-[#D09A3F] to-[#4B3E2F] text-white">

    <div class="flex flex-col items-center justify-center text-center space-y-5">
        <img src="{{ asset('passets/images/HC-white.png') }}" alt="Logo" class="w-[300px]">

        <h1 class="text-4xl sm:text-5xl font-bold uppercase text-white">Online Certified Copy</h1>
        <h2 class="text-xl sm:text-2xl font-semibold uppercase text-yellow-400">Session Active Warning</h2>

        <p class="text-white text-base sm:text-lg leading-relaxed">
            You're already logged in. Your session is still active ! <br>
            Please logout before accessing.
        </p>

        <form method="POST" action="{{ route('logout.tracking') }}">
            @csrf
            <button type="submit"
                class="mt-4 flex items-center gap-4 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                <img src="{{ asset('passets/images/icons/loginicon.svg') }}" alt="Logout Icon" class="w-6 h-6">
                LOGOUT
            </button>
        </form>

        <footer class="pt-8 text-sm sm:text-base font-medium text-white opacity-90">
            ONLINE CERTIFIED COPY – Jharkhand High Court
        </footer>
    </div>

</body>
</html>
