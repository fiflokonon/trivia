<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .terminal {
            font-family: monospace;
            font-size: 24px;
            background-color: black;
            color: lime;
            padding: 20px;
            border-radius: 5px;
        }

        .command {
            position: relative;
            overflow: hidden;
        }

        .command::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: lime;
            bottom: 0;
            left: 0;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .command:hover::after {
            transform: scaleX(1);
        }
    </style>
    <title>Trivia</title>
</head>
<body>
<div class="terminal">
    <div class="command">T-R-I-V-I-A BACKEND</div>
</div>
</body>
</html>
