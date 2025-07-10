<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>419 - Page Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    body {
        background: linear-gradient(135deg, #4B3E2F, #4B3E2F);
        display: flex;
        flex-direction: column;
        height: 100vh;
        align-items: center;
        justify-content: center;
        background-repeat: no-repeat;
        color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        text-align: center;
    }

    img {
        width: 310px;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    h1 {
        font-size: 3.2rem;
        color: #CD973D;
    }

    p {
        font-size: 1.2rem;
        color: #eee;
    }

    .page_exp {
        margin-top: -10px;
        font-size: 24px;
    }

    .btn-home {
        background-color: red;
        color: #fff;
        padding: 12px 24px;
        font-size: 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .btn-home:hover {
        background-color: red;
    }

    @media (max-width: 480px) {
        img {
            width: 90%;
            max-width: 300px;
        }

        h1 {
            font-size: 2rem;
        }

        p {
            font-size: 1rem;
        }

        .page_exp {
            font-size: 20px;
        }

        .btn-home {
            padding: 10px 20px;
            font-size: 0.95rem;
        }

        .container {
            padding: 10px;
        }
    }
</style>
  
<body>
    <div class="container">
        <img src="{{ asset('passets/images/HC-white.png') }}" alt="Banner Image">
        <h1>ONLINE CERTIFIED COPY</h1>
        <p class="page_exp">Page Expired - 419</p>
        <p>Your session has expired. Please refresh the page or return to the homepage.</p>
        <a href="{{ url('/') }}" class="btn-home">Go to Home</a>
    </div>
</body>
</html>
