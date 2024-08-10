<?php
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Initialize profile picture variable
    $profilePicture = '';

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file extensions and directory
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadFileDir = '../../uploads/';
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        // Validate file extension and move the file
        if (in_array($fileExtension, $allowedExtensions) && move_uploaded_file($fileTmpPath, $dest_path)) {
            $profilePicture = $newFileName;
        }
    }

    // Insert data into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, profile_picture) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $password, $role, $profilePicture])) {
            echo "User registered successfully!";
        } else {
            echo "Failed to register user.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post" enctype="multipart/form-data">
            <h2>Register</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="machine_operator">Machine Operator</option>
                <option value="qa">QA</option>
                <option value="store_incharge">Store Incharge</option>
                <option value="driver">Driver</option>
            </select>
            <input type="file" name="profile_picture" accept="image/*">
            <input type="submit" value="Register">
        </form>
        <div class="login-link">
            <p>Already have an account?</p>
            <a href="login.php" class="login-button">Login</a>
        </div>
    </div>
</body>
</html>
