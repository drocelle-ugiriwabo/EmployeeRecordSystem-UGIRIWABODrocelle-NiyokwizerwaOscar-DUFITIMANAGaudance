<?php
// PDO connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=employee_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch employees with department name
$stmt = $pdo->query(
    "SELECT employees.*, departments.dept_name
     FROM employees
     LEFT JOIN departments ON employees.department_id = departments.id"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <style>
        /* Global Styles */
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

        /* Table Card */
        .table-container {
            width: 95%;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
        }

        th {
            background-color: #273c75;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background-color: #f1f2f6;
        }

        tr:hover {
            background-color: #e1b12c;
            color: #fff;
            transition: 0.3s;
        }

        a {
            color: #0984e3;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            color: #e84118;
            text-decoration: underline;
        }

        /* Add Employee Button */
        .add-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 12px;
            background: linear-gradient(to right, #44bd32, #4cd137);
            color: #fff;
            text-align: center;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .add-btn:hover {
            background: linear-gradient(to right, #4cd137, #44bd32);
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            table, th, td {
                display: block;
                width: 100%;
            }
            tr {
                margin-bottom: 15px;
                display: block;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                border-radius: 10px;
                overflow: hidden;
            }
            th {
                display: none;
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
<body>

<h2>Employee List</h2>

<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Salary</th>
            <th>Department</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td data-label="ID"><?php echo $row['id']; ?></td>
            <td data-label="Full Name"><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
            <td data-label="Phone"><?php echo htmlspecialchars($row['phone']); ?></td>
            <td data-label="Salary"><?php echo $row['salary']; ?></td>
            <td data-label="Department"><?php echo htmlspecialchars($row['dept_name']); ?></td>
            <td data-label="Action">
                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this employee?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<a class="add-btn" href="insert.php">Add New Employee</a>
<a href="logout.php">Logout</a>

</body>
</html>
