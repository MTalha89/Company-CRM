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
    $productId = $_POST['product_id'];
    $machineId = $_POST['machine_id'];

    $stmt = $pdo->prepare("INSERT INTO production_assignments (product_id, machine_id) VALUES (?, ?)");
    if ($stmt->execute([$productId, $machineId])) {
        echo "Product assigned to machine successfully!";
    } else {
        echo "Failed to assign product.";
    }
}

$products = $pdo->query("SELECT id, product_name FROM products")->fetchAll();
$machines = $pdo->query("SELECT id, machine_name FROM machines")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Product</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Assign Product to Machine</h2>
            <select name="product_id">
                <?php foreach ($products as $product) : ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo $product['product_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="machine_id">
                <?php foreach ($machines as $machine) : ?>
                    <option value="<?php echo $machine['id']; ?>"><?php echo $machine['machine_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Assign">
        </form>
    </div>
</body>
</html>
