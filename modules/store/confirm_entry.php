<?php
include '../../config/database.php';
include '../../includes/auth.php';

if (!checkRole('store_incharge')) {
    die("Access denied");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entryId = isset($_POST['entry_id']) ? intval($_POST['entry_id']) : null;

    if ($entryId) {
        try {
            // Confirm the entry
            $stmt = $pdo->prepare("UPDATE production_entries SET confirmed = 1 WHERE id = ?");
            $stmt->execute([$entryId]);

            // Update store inventory
            $stmt = $pdo->prepare("INSERT INTO inventory (product_id, quantity) SELECT product_id, produced_quantity FROM production_entries WHERE id = ?");
            $stmt->execute([$entryId]);

            $message = "Entry confirmed and added to inventory successfully!";
        } catch (PDOException $e) {
            $message = "Failed to confirm entry: " . $e->getMessage();
        }
    } else {
        $message = "Invalid entry ID.";
    }
} else {
    $message = "Invalid request method.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="message-container">
        <p><?php echo htmlspecialchars($message); ?></p>
        <button onclick="window.location.href='store_upcoming_products.php';">OK</button>
    </div>
</body>
</html>
