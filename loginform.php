<?php
session_start();

$host = "localhost";
$dbname = "employee_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Enter username and password";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: select.php"); 
            exit;
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Employee Management</title>
<style>
    /* Background and layout */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    /* Login Card */
    .login-card {
        background: #ffffffee;
        width: 400px;
        padding: 40px 35px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.35);
    }

    .login-card h2 {
        margin-bottom: 30px;
        font-weight: 700;
        color: #2f3640;
        letter-spacing: 1px;
    }

    /* Input fields */
    .login-card input[type="text"],
    .login-card input[type="password"] {
        width: 100%;
        padding: 14px 18px;
        margin: 12px 0;
        border-radius: 12px;
        border: 1px solid #ccc;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .login-card input[type="text"]:focus,
    .login-card input[type="password"]:focus {
        border-color: #764ba2;
        box-shadow: 0 0 8px rgba(118,75,162,0.5);
        outline: none;
    }

    /* Button */
    .login-card button {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #44bd32, #4cd137);
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .login-card button:hover {
        background: linear-gradient(135deg, #4cd137, #44bd32);
        transform: translateY(-2px);
    }

    /* Error message */
    .error-msg {
        color: #e84118;
        font-weight: 600;
        margin-bottom: 15px;
    }

    /* Responsive */
    @media screen and (max-width: 420px) {
        .login-card {
            width: 90%;
            padding: 30px 20px;
        }
    }
</style>
</head>
<body>

<div class="login-card">
    <h2>Employee Login</h2>

    <?php if ($error != ""): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
