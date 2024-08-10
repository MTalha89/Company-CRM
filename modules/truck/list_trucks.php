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

$trucks = $pdo->query("SELECT * FROM trucks")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Trucks</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Trucks List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Number Plate</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trucks as $truck) : ?>
                    <tr>
                        <td><?php echo $truck['id']; ?></td>
                        <td><?php echo $truck['truck_number_plate']; ?></td>
                        <td><?php echo $truck['truck_color']; ?></td>
                        <td><?php echo $truck['truck_size']; ?></td>
                        <td>
                            <!-- Add edit and delete links here if needed -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
