<?php
session_start();

include 'koneksi.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($conn->real_escape_string($_POST['username']));
    $password = trim($conn->real_escape_string($_POST['password']));

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];
            if ($_SESSION['usertype'] == 'user') {
                h6eader("Location: index.php");
            } else if ($_SESSION['usertype'] == 'admin') {
                header("Location: ./admin/indexadmin.php");
            }
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-t from-[#fbc2eb] to-[#a6c1ee] flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="post" class="space-y-4">
            <!-- Username Field -->
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username</label>
                <input type="text" name="username" id="username" class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input type="password" name="password" id="password" class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" name="login" class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Login
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <a href="register.php" class="text-indigo-600 hover:underline">Don't have an account? Register here</a>
        </div>
    </div>
</body>
</html>
