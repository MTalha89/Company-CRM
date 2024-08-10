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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $dispatchDate = date('Y-m-d H:i:s');
    $truckId = $_POST['truck_id'];
    $driverId = $_POST['driver_id'];
    $gatePassNumber = $_POST['gate_pass_number'];

    $stmt = $pdo->prepare("SELECT quantity FROM inventory WHERE product_id = ?");
    $stmt->execute([$productId]);
    $inventory = $stmt->fetch();

    if ($inventory && $inventory['quantity'] >= $quantity) {
        $stmt = $pdo->prepare("INSERT INTO dispatch (product_id, quantity, dispatch_date, truck_id, driver_id, gate_pass_number) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$productId, $quantity, $dispatchDate, $truckId, $driverId, $gatePassNumber])) {
            // Update inventory
            $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?");
            $stmt->execute([$quantity, $productId]);
            echo "Dispatch recorded successfully!";
        } else {
            echo "Failed to record dispatch.";
        }
    } else {
        echo "Insufficient inventory quantity.";
    }
}

$products = $pdo->query("SELECT id, product_name FROM products")->fetchAll();
$trucks = $pdo->query("SELECT id, truck_number FROM trucks")->fetchAll();
$drivers = $pdo->query("SELECT id, username FROM users WHERE role = 'driver'")->fetchAll();
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
            <h2>Dispatch</h2>
            <select name="product_id">
                <?php foreach ($products as $product) : ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo $product['product_name']; ?></option>
                <?php endforeach; ?>
            </select>
             <input style="width:100% !important;" type="number" name="quantity" required placeholder="Quantity">
            <select name="truck_id">
                <?php foreach ($trucks as $truck) : ?>
                    <option value="<?php echo $truck['id']; ?>"><?php echo $truck['truck_number']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="driver_id">
                <?php foreach ($drivers as $driver) : ?>
                    <option value="<?php echo $driver['id']; ?>"><?php echo $driver['username']; ?></option>
                <?php endforeach; ?>
            </select>
             <input style="width:100% !important;" type="text" name="gate_pass_number" required placeholder="Gate Pass Number">
            <input type="submit" value="Dispatch">
        </form>
    </div>
</body>
</html>
