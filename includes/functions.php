<?php
function getUserProfile($userId, $db) {
    $stmt = $db->prepare("SELECT username, profile_picture, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
