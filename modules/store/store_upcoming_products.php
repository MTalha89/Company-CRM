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

try {
    // Fetch approved production entries that haven't been confirmed yet
    $query = "
        SELECT pe.id, pe.product_id, pe.machine_id, pe.produced_quantity, pe.production_date
        FROM production_entries pe
        JOIN quality_assurance qa ON qa.production_entry_id = pe.id
        WHERE qa.approved_quantity > 0 AND pe.confirmed = 0
    ";

    $stmt = $pdo->query($query);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Store Module - Upcoming Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Upcoming Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Machine</th>
                    <th>Produced Quantity</th>
                    <th>Production Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($entries)): ?>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entry['id']); ?></td>
                            <td><?php echo htmlspecialchars($entry['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($entry['machine_id']); ?></td>
                            <td><?php echo htmlspecialchars($entry['produced_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($entry['production_date']); ?></td>
                            <td>
                                <form method="post" action="confirm_entry.php">
                                    <input type="hidden" name="entry_id" value="<?php echo htmlspecialchars($entry['id']); ?>">
                                    <input type="submit" name="confirm" value="Confirm">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No upcoming products available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
