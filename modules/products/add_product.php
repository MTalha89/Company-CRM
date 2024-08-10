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
    $productName = $_POST['product_name'];
    $slug = $_POST['slug'];

    $stmt = $pdo->prepare("INSERT INTO products (product_name, slug) VALUES (?, ?)");
    if ($stmt->execute([$productName, $slug])) {
        echo "Product added successfully!";
    } else {
        echo "Failed to add product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Add Product</h2>
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="slug" placeholder="Unique Slug" required>
            <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
