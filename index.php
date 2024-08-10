<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/auth.php';
include 'includes/functions.php'; // Include the file with getUserProfile function

try {
    $db = new PDO('mysql:host=localhost;dbname=tahaerp;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit; // Stop script execution if database connection fails
}

if (!isLoggedIn()) {
    header("Location: modules/auth/login.php");
    exit;
}

$userProfile = isset($_SESSION['user_id']) ? getUserProfile($_SESSION['user_id'], $db) : null;

$username = $userProfile['username'] ?? 'Guest';
$profilePicture = $userProfile['profile_picture'] ?? 'default_profile_pic.jpg';
$role = $userProfile['role'] ?? 'undefined';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERP System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Header Styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }
        .user-info {
            display: flex;
            flex-direction: column;
        }
        .user-info h2, .user-info p {
            margin: 0;
        }
        .navbar {
            display: flex;
            gap: 15px;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .navbar a:hover {
            background-color: #555;
        }
        .logout-button {
            background-color: #e74c3c;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .logout-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="profile-pic">
            <div class="user-info">
                <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
                <p>Your role: <?php echo htmlspecialchars($role); ?></p>
            </div>
        </div>
        <div class="navbar">
            <?php if ($role === 'admin') : ?>
                <a href="modules/products/add_product.php">Add Product</a>
                <a href="modules/machines/add_machine.php">Add Machine</a>
                <a href="modules/production/assign_product.php">Assign Product</a>
                <a href="modules/truck/add_truck.php">Add Truck</a>
            <?php endif; ?>

            <?php if ($role === 'machine_operator') : ?>
                <a href="modules/production/add_entry.php">Add Production Entry</a>
            <?php endif; ?>

            <?php if ($role === 'qa') : ?>
                <a href="modules/qa/approve.php">QA Approval</a>
            <?php endif; ?>

            <?php if ($role === 'store_incharge') : ?>
                <a href="modules/store/add_to_inventory.php">Add to Inventory</a>
                <a href="modules/dispatch/dispatch.php">Dispatch</a>
            <?php endif; ?>
        </div>
        <a href="modules/auth/logout.php" class="logout-button">Logout</a>
    </header>

    <div class="form-container">
        <ul>
            <!-- Additional content if needed -->
        </ul>
    </div>
</body>
</html>
