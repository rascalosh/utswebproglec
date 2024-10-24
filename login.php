<?php
session_start();
include 'koneksi.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $input = trim($_POST['username_or_email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $input, $input); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];
            if ($_SESSION['usertype'] == 'user') {
                header("Location: index.php");
            } else if ($_SESSION['usertype'] == 'admin') {
                header("Location: ./admin/indexadmin.php");
            }
            exit();
        } else {
            $error = "Invalid username/email or password";
        }
    } else {
        $error = "Invalid username/email or password";
    }

    $stmt->close(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @keyframes slide-in {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .slide-in {
            animation: slide-in 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gradient-to-t from-[#E0EAF9] to-[#F4F7FC] flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-md slide-in">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
            <h1 class="text-3xl font-bold text-white text-center mb-2">
                <i class="fas fa-user-circle"></i> Login
            </h1>
            <p class="text-white text-center">Welcome back! Please login to your account.</p>
        </div>

        <!-- Error Message -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mt-2">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="post" class="p-6 space-y-4">
            <!-- Username Field -->
<div>
    <label for="username_or_email" class="block text-gray-700 font-medium">Username or Email</label>
    <div class="relative">
        <input type="text" name="username_or_email" id="username_or_email" class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out pl-10" required>
        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i> <!-- Changed top-2.5 to top-3 -->
    </div>
</div>

<!-- Password Field -->
<div>
    <label for="password" class="block text-gray-700 font-medium">Password</label>
    <div class="relative">
        <input type="password" name="password" id="password" class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out pl-10" required>
        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i> <!-- Changed top-2.5 to top-3 -->
    </div>
</div>

            <!-- Submit Button -->
            <div>
                <button type="submit" name="login" class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </div>
        </form>

        <div class="mt-2 mb-5 text-center">
            <a href="register.php" class="text-indigo-600 hover:underline">Don't have an account? Register here</a>
        </div>
    </div>
</body>
</html>
