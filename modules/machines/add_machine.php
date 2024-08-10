<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../config/database.php';
include '../../includes/auth.php';
include '../../includes/roles.php';

if (!checkRole('admin')) {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $machineName = $_POST['machine_name'];
    $slug = $_POST['slug'];

    $stmt = $pdo->prepare("INSERT INTO machines (machine_name, slug) VALUES (?, ?)");
    if ($stmt->execute([$machineName, $slug])) {
        echo "Machine added successfully!";
    } else {
        echo "Failed to add machine.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Machine</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Add Machine</h2>
            <input type="text" name="machine_name" placeholder="Machine Name" required>
            <input type="text" name="slug" placeholder="Unique Slug" required>
            <input type="submit" value="Add Machine">
        </form>
    </div>
</body>
</html>
