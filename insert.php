<?php
// PDO connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=employee_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$error = "";
$success = "";

if (isset($_POST['save'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $salary = trim($_POST['salary']);
    $department = $_POST['department'];

    if (empty($fullname) || empty($email)) {
        $error = "Full name and Email are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO employees (fullname, email, phone, salary, department_id)
                 VALUES (:fullname, :email, :phone, :salary, :dept)"
            );

            $stmt->bindParam(":fullname", $fullname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":salary", $salary);
            $stmt->bindParam(":dept", $department);

            $stmt->execute();
            $success = "Employee added successfully";

        } catch (PDOException $e) {
            $error = "Email already exists or database error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing: border-box; font-family: 'Roboto', sans-serif; }

        body {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            width: 400px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #0072ff;
        }

        input[type="text"], input[type="email"], input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #0072ff;
            box-shadow: 0 0 8px rgba(0, 114, 255, 0.5);
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: linear-gradient(to right, #0072ff, #00c6ff);
        }

        .link {
            display: block;
            margin-top: 20px;
            color: #0072ff;
            text-decoration: none;
            font-weight: bold;
        }

        .link:hover {
            text-decoration: underline;
        }

        .message {
            margin-bottom: 15px;
            font-weight: 500;
        }

        .error { color: #e84118; }
        .success { color: #44bd32; }
    </style>
</head>
<body>

<div class="card">
    <h2>Add Employee</h2>

    <?php if ($error != "") { ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($success != "") { ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone">
        <input type="number" step="0.01" name="salary" placeholder="Salary">
        <input type="number" name="department" placeholder="Department ID">
        <button type="submit" name="save">Save Employee</button>
    </form>

    <a href="select.php" class="link">View All Employees</a>
</div>

</body>
</html>
