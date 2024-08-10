<?php
include '../../config/database.php';

header('Content-Type: application/json');

$productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

if ($productId) {
    try {
        $stmt = $pdo->prepare("SELECT m.id, m.machine_name FROM production_assignments pa JOIN machines m ON pa.machine_id = m.id WHERE pa.product_id = ?");
        $stmt->execute([$productId]);
        $machines = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($machines);
    } catch (PDOException $e) {
        // Log error and return a JSON error message
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['error' => 'Database error occurred']);
    }
} else {
    echo json_encode(['error' => 'No product ID provided']);
}
?>
