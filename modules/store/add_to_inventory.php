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
    $qaId = $_POST['qa_id'];
    $stmt = $pdo->prepare("SELECT approved_quantity, production_entry_id FROM quality_assurance WHERE id = ?");
    $stmt->execute([$qaId]);
    $qaEntry = $stmt->fetch();

    if ($qaEntry) {
        $approvedQuantity = $qaEntry['approved_quantity'];
        $productionEntryId = $qaEntry['production_entry_id'];

        $stmt = $pdo->prepare("SELECT product_id FROM production_entries WHERE id = ?");
        $stmt->execute([$productionEntryId]);
        $productionEntry = $stmt->fetch();

        if ($productionEntry) {
            $productId = $productionEntry['product_id'];

            $stmt = $pdo->prepare("SELECT id FROM inventory WHERE product_id = ?");
            $stmt->execute([$productId]);
            $inventoryEntry = $stmt->fetch();

            if ($inventoryEntry) {
                // Update existing inventory
                $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity + ? WHERE product_id = ?");
                if ($stmt->execute([$approvedQuantity, $productId])) {
                    echo "Inventory updated successfully!";
                } else {
                    echo "Failed to update inventory.";
                }
            } else {
                // Insert new inventory
                $stmt = $pdo->prepare("INSERT INTO inventory (product_id, quantity) VALUES (?, ?)");
                if ($stmt->execute([$productId, $approvedQuantity])) {
                    echo "Inventory added successfully!";
                } else {
                    echo "Failed to add to inventory.";
                }
            }
        }
    }
}

$qaEntries = $pdo->query("SELECT id, production_entry_id FROM quality_assurance")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add to Inventory</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Add to Inventory</h2>
            <select name="qa_id">
                <?php foreach ($qaEntries as $qaEntry) : ?>
                    <option value="<?php echo $qaEntry['id']; ?>">QA ID: <?php echo $qaEntry['id']; ?> (Production Entry ID: <?php echo $qaEntry['production_entry_id']; ?>)</option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Add to Inventory">
        </form>
    </div>
</body>
</html>
