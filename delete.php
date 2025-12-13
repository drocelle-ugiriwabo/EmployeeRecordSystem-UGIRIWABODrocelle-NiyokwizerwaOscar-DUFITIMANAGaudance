<?php
// PDO connection (standalone, no includes)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=employee_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("Employee ID not specified");
}

$id = $_GET['id'];

// Delete employee
try {
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to employee list
    header("Location: select.php");
    exit;
} catch (PDOException $e) {
    die("Error deleting employee: " . $e->getMessage());
}
?>
