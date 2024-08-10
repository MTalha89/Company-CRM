<?php
include '../config/database.php';
include '../includes/auth.php';

if (!checkRole('qa')) {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entryId = isset($_POST['entry_id']) ? intval($_POST['entry_id']) : null;

    if ($entryId) {
        try {
            $stmt = $pdo->prepare("UPDATE production_entries SET approved = 1 WHERE id = ?");
            $stmt->execute([$entryId]);
            echo "Entry approved successfully!";
        } catch (PDOException $e) {
            echo "Failed to approve entry: " . $e->getMessage();
        }
    } else {
        echo "Invalid entry ID.";
    }
} else {
    echo "Invalid request method.";
}
?>
