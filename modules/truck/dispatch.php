<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../config/database.php';
include '../../includes/auth.php';
include '../../includes/roles.php';

if (!checkRole('store_incharge')) {
    die("Access denied");
}

// Fetch trucks for dropdown
$trucks = $pdo->query("SELECT * FROM trucks")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $approvedEntryId = $_POST['approved_entry_id'];
    $dispatchQuantity = $_POST['dispatch_quantity'];
    $truckId = $_POST['truck_id'];
    $dispatchDate = date('Y-m-d H:i:s');

    // Dispatch the products
    $stmt = $pdo->prepare("INSERT INTO dispatches (production_entry_id, quantity, truck_id, dispatch_date) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$approvedEntryId, $dispatchQuantity, $truckId, $dispatchDate])) {
        echo "Dispatch recorded successfully!";
    } else {
        echo "Failed to record dispatch.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dispatch</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Dispatch Products</h2>
            <select name="approved_entry_id" required>
                <!-- Populate with approved entries -->
            </select>
            Dispatch Quantity: <input type="number" name="dispatch_quantity" required>
            Select Truck: 
            <select name="truck_id" required>
                <?php foreach ($trucks as $truck) : ?>
                    <option value="<?php echo $truck['id']; ?>"><?php echo $truck['truck_number_plate']; ?> (<?php echo $truck['truck_size']; ?>)</option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Dispatch">
        </form>
    </div>
</body>
</html>
