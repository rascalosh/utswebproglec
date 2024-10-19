<?php
include 'koneksi.php';

session_start();

if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
    $email = $conn->real_escape_string($_POST['email']);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        echo "User registered successfully.";
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body >
    <div >
        <h1 >Register</h1>
        <a href="login.php" >Back to Login</a>
        <?php if (isset($error)): ?>
            <p ><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div >
                <label >Username:</label>
                <input type="text" name="username"  required>
            </div>
            <div >
                <label >Password:</label>
                <input type="password" name="password"  required>
            </div>
            <div >
                <label >Email:</label>
                <input type="email" name="email"  required>
            </div>
            <div>
                <button type="submit" name="register" >Register</button>
            </div>
        </form>
    </div>
</body>
</html>
