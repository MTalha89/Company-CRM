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
    $truckNumberPlate = $_POST['truck_number_plate'];
    $truckColor = $_POST['truck_color'];
    $truckSize = $_POST['truck_size'];

    $stmt = $pdo->prepare("INSERT INTO trucks (truck_number_plate, truck_color, truck_size) VALUES (?, ?, ?)");
    if ($stmt->execute([$truckNumberPlate, $truckColor, $truckSize])) {
        echo "Truck added successfully!";
    } else {
        echo "Failed to add truck.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Truck</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Add Truck</h2>
            Truck Number Plate: <input type="text" name="truck_number_plate" required>
            Truck Color: <input type="text" name="truck_color" required>
            Truck Size: <input type="text" name="truck_size" required>
            <input type="submit" value="Add Truck">
        </form>
    </div>
</body>
</html>
