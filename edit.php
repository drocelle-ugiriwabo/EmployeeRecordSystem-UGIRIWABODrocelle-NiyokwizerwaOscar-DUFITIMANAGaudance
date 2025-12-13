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

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("Employee ID not specified");
}

$id = $_GET['id'];

// Fetch current employee data
try {
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        die("Employee not found");
    }
} catch (PDOException $e) {
    die("Error fetching employee: " . $e->getMessage());
}

// Handle form submission
if (isset($_POST['update'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $salary = trim($_POST['salary']);
    $department = $_POST['department'];

    // VALIDATION
    if (empty($fullname) || empty($email)) {
        $error = "Full name and Email are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        try {
            $stmt = $pdo->prepare(
                "UPDATE employees
                 SET fullname = :fullname,
                     email = :email,
                     phone = :phone,
                     salary = :salary,
                     department_id = :dept
                 WHERE id = :id"
            );

            $stmt->bindParam(":fullname", $fullname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":salary", $salary);
            $stmt->bindParam(":dept", $department);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            $stmt->execute();
            $success = "Employee updated successfully";

            // Refresh employee data
            $employee['fullname'] = $fullname;
            $employee['email'] = $email;
            $employee['phone'] = $phone;
            $employee['salary'] = $salary;
            $employee['department_id'] = $department;

        } catch (PDOException $e) {
            $error = "Error updating employee: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #fff;
            padding: 30px 0 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }

        .form-container {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2f3640;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #dcdde1;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #44bd32, #4cd137);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: linear-gradient(to right, #4cd137, #44bd32);
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }

        .error {
            background-color: #e84118;
            color: #fff;
        }

        .success {
            background-color: #44bd32;
            color: #fff;
        }

        a.back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: #0984e3;
            text-decoration: none;
        }

        a.back-link:hover {
            color: #e84118;
        }
    </style>
</head>
<body>

<h2>Edit Employee</h2>

<div class="form-container">
    <?php if (!empty($error)) { ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <?php if (!empty($success)) { ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php } ?>

    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($employee['fullname']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($employee['phone']); ?>">

        <label>Salary:</label>
        <input type="number" step="0.01" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>">

        <label>Department ID:</label>
        <input type="number" name="department" value="<?php echo htmlspecialchars($employee['department_id']); ?>">

        <button type="submit" name="update">Update Employee</button>
    </form>

    <a class="back-link" href="select.php">Back to Employee List</a>
</div>

</body>
</html>
