<?php
$roles = [
    'admin' => ['add_product', 'add_machine', 'assign_product', 'view_reports'],
    'machine_operator' => ['add_entry'],
    'qa' => ['approve'],
    'store_incharge' => ['add_to_inventory', 'dispatch'],
    'driver' => []
];

function hasAccess($action) {
    global $roles;
    $role = $_SESSION['role'] ?? null;
    return $role && in_array($action, $roles[$role]);
}
?>
