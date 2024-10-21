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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-t from-[#fbc2eb] to-[#a6c1ee] flex items-center justify-center min-h-screen">


    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>

        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        
        <form action="register.php" method="post" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username</label>
                <input type="text" name="username" id="username" class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            
            <div>
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            
            <div>
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input type="password" name="password" id="password" class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            
            <div>
                <button type="submit" name="register" class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Register
                </button>
            </div>
        </form>

        
        <div class="mt-6 text-center">
            <a href="login.php" class="text-indigo-600 hover:underline">Back to Login</a>
        </div>
    </div>
</body>
</html>
