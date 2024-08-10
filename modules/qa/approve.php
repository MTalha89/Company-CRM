<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../config/database.php';
include '../../includes/auth.php';
include '../../includes/roles.php';

if (!checkRole('qa')) {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entryId = $_POST['production_entry_id'];
    $qaId = $_SESSION['user_id'];
    $approvedQuantity = $_POST['approved_quantity'];
    $rejectedQuantity = $_POST['rejected_quantity'];
    $holdQuantity = $_POST['hold_quantity'];
    $qaDate = date('Y-m-d H:i:s');

    // Insert QA approval record
    $stmt = $pdo->prepare("INSERT INTO quality_assurance (production_entry_id, qa_id, approved_quantity, rejected_quantity, hold_quantity, qa_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$entryId, $qaId, $approvedQuantity, $rejectedQuantity, $holdQuantity, $qaDate]);

    // Update quality assurance entry status
    $stmt = $pdo->prepare("UPDATE quality_assurance SET status = 'Approved' WHERE production_entry_id = ?");
    $stmt->execute([$entryId]);

    echo "QA approval recorded successfully!";
}

// Fetch unapproved entries
$entries = $pdo->query("SELECT id, product_id FROM production_entries WHERE id NOT IN (SELECT production_entry_id FROM quality_assurance WHERE status = 'Approved')")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QA Approval</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>QA Approval</h2>
            <select name="production_entry_id" required>
                <?php foreach ($entries as $entry) : ?>
                    <option value="<?php echo $entry['id']; ?>">Entry ID: <?php echo $entry['id']; ?> (Product ID: <?php echo $entry['product_id']; ?>)</option>
                <?php endforeach; ?>
            </select>
            Approved Quantity: <input type="number" name="approved_quantity" required>
            Rejected Quantity: <input type="number" name="rejected_quantity" required>
            Hold Quantity: <input type="number" name="hold_quantity" required>
            <input type="submit" value="Approve">
        </form>
    </div>
</body>
</html>
