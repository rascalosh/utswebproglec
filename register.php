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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-t from-[#E0EAF9] to-[#F4F7FC] flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
        <h1 class="text-4xl font-extrabold mb-6 text-center text-indigo-600">Create an Account</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="post" class="space-y-6">
            <!-- Username Field -->
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username</label>
                <div class="relative">
                    <input type="text" name="username" id="username" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out pl-10" required>
                    <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <div class="relative">
                    <input type="email" name="email" id="email" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out pl-10" required>
                    <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out pl-10" required>
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" name="register" class="w-full bg-indigo-600 text-white font-semibold p-3 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out shadow-lg transform hover:scale-105">
                    Create Account
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <a href="login.php" class="text-indigo-600 hover:underline">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>
