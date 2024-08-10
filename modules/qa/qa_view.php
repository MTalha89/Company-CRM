<?php
include '../config/database.php';
include '../includes/auth.php';

// Check user role
if (!checkRole('qa')) {
    die("Access denied");
}

// Fetch all unapproved production entries
$stmt = $pdo->query("SELECT id, product_id, machine_id, produced_quantity, rejected_quantity, production_date FROM production_entries WHERE approved = 0");
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QA Module - View Entries</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Production Entries</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Machine</th>
                    <th>Produced Quantity</th>
                    <th>Rejected Quantity</th>
                    <th>Production Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['id']); ?></td>
                        <td><?php echo htmlspecialchars($entry['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($entry['machine_id']); ?></td>
                        <td><?php echo htmlspecialchars($entry['produced_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($entry['rejected_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($entry['production_date']); ?></td>
                        <td>
                            <form method="post" action="approve_entry.php">
                                <input type="hidden" name="entry_id" value="<?php echo htmlspecialchars($entry['id']); ?>">
                                <input type="submit" name="approve" value="Approve">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
