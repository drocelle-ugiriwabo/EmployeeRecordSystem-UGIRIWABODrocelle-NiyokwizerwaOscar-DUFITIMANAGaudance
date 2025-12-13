<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .logout-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            color: #2f3640;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 30px;
            color: #333;
        }

        a {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(to right, #44bd32, #4cd137);
            color: #fff;
            font-weight: bold;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        a:hover {
            background: linear-gradient(to right, #4cd137, #44bd32);
        }
    </style>
</head>
<body>

<div class="logout-container">
    <h2>You have been logged out</h2>
    <p>Thank you! You have successfully logged out.</p>
    <a href="insert.php">Go to Login Page</a>
</div>

</body>
</html>
