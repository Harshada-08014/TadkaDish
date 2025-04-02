<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            color: #333;
        }

        .container {
            text-align: center;
            padding: 40px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            font-size: 36px;
            color: #4CAF50;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Green circle background */
        .tick-container {
            position: relative;
            display: inline-block;
            width: 100px;
            height: 100px;
            background-color: #4CAF50;
            border-radius: 50%;
            animation: pop 0.5s ease-out;
        }

        /* Tick mark inside the green circle */
        .tick {
            position: absolute;
            top: 35px;
            left: 30px;
            width: 40px;
            height: 20px;
            transform: rotate(-50deg);
            border-left: solid 6px white;
            border-bottom: solid 6px white;
            animation: drawTick 1s ease forwards;
        }

        .message {
            font-size: 18px;
            margin-top: 20px;
            color: #555;
            animation: fadeInMessage 1s ease-out;
        }

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-50px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pop {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes drawTick {
            0% {
                width: 0;
                height: 0;
                opacity: 0;
            }

            100% {
                width: 40px;
                height: 20px;
                opacity: 1;
            }
        }

        @keyframes fadeInMessage {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>

<body>

    <div class="container">
        <div class="tick-container">
            <div class="tick"></div>
        </div>
        <h1>Success!</h1>
        <p class="message">Your payment is successfully completed.</p>
    </div>

</body>

</html>
