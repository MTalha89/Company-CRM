<?php
include '../../config/database.php';
include '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password, $pdo)) {
        header("Location: ../../index.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <form method="post">
            <h2>Login</h2>
            <?php if (isset($error)) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required value="mo">
            <input type="password" name="password" placeholder="Password" required value="mo">
            <input type="submit" value="Login">
        </form>
        <div class="register-link">
            <p>Don't have an account?</p>
            <a href="register.php" class="register-button">Register</a>
        </div>
    </div>
</body>
</html>
