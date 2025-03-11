<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 || Online Certified Copy</title>
    <style>
        /* Base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #4B3D2F;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        .container {
            /* max-width: 500px; */
        }

        h1 {
            font-size: 200px;
            font-weight: bold;
            color: #D09A3F;
            text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.3);
        }

        p {
            font-size: 22px;
            margin: 20px 0;
        }

        .back-button {
            display: inline-block;
            background-color: #D09A3F;
            color: #4B3D2F;
            padding: 12px 25px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            background-color: #b88030;
            transform: scale(1.05);
            color: white;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 90px;
            }

            p {
                font-size: 18px;
            }

            .back-button {
                font-size: 16px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>404</h1>
        <p>Oops! The page you're looking for doesn't exist.</p>
        <a href="{{ url('/') }}" class="back-button">Go Back Home</a>
    </div>

</body>
</html>