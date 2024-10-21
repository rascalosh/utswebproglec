<?php
include 'koneksi.php';
session_start();

$user_id = $_SESSION['user_id'];

// Fetch user data for the form
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $description = $_POST['description'];
    $image_path = $user['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = 'admin/uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    
    // Update query
    if (!empty($password)) {
        // If password is provided, hash it before updating
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $update_query = "UPDATE users SET username = ?, email = ?, description = ?, image = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssi", $username, $email, $description, $image_path, $password_hash, $user_id);
    } else {
        // If no password is provided, update only username and email
        $update_query = "UPDATE users SET username = ?, email = ?, description = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $username, $email, $description, $image_path, $user_id);
    }

    // Execute the update query
    if ($stmt->execute()) {
        // If the update is successful, redirect back to the profile page
        header("Location: profile.php");
        exit();
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-[Poppins] bg-gradient-to-t from-[#fbc2eb] to-[#a6c1ee] min-h-screen text-gray-900 flex flex-col">
    <header class="bg-[#FFE1FF] py-3">
        <nav class="flex justify-between items-center w-[92%] mx-auto">
            <div>
                <img class="w-20" src="assets/baileo3.png" alt="...">
            </div>
            <div class="nav-links duration-500 md:static absolute bg-[#FFE1FF] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5">
                <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                    <li>
                        <a class="text-gray-700 hover:text-pink-900 transition duration-100 ease-in-out" href="index.php">Events</a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-pink-900 transition duration-100 ease-in-out" href="registered_events.php">Registered Event</a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-pink-900 transition duration-100 ease-in-out" href="profile.php">My Profile</a>
                    </li>
                </ul>
            </div>
            <div class="flex items-center gap-6"> 
                <a href="logout.php" class="bg-[#7E60BF] text-white px-5 py-2 rounded-full hover:bg-[#CDC1FF]">Log Out</a>
            </div>
    </header>
    <script>
        const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
        }
    </script>

<div class="container mx-auto mt-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-4">Edit Profile</h1>
        <?php if (isset($error)): ?>
            <p class="text-red-600"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="username">Username</label>
                <input type="text" name="username" value="<?php echo $user['username']; ?>" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="email">Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="image">Profile Picture</label>
                <input type="file" name="image" class="w-full px-3 py-2 border rounded" placeholder="Profile Picture">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="description">Description</label>
                <input type="text" name="description" value="<?php echo $user['description']; ?>" class="w-full px-3 py-2 border rounded" >
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="password">Password (Leave blank to keep current password)</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded" placeholder="New password">
            </div>
            <div>
                <button type="submit" class="bg-purple-600 text-white py-2 px-4 rounded">Save Changes</button>
                <a href="profile.php" class="bg-gray-500 text-white py-2 px-4 rounded ml-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
<div class="flex-col space-y-1 mt-5">
            <div class="mb-5">
                
            </div>
        </div>
    </div>
    <footer class="bg-[#FFE1FF] text-gray-700 py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left">
                    <p class="text-sm">&copy; <?php echo date("Y"); ?> Baileo Event Organizer. All rights reserved.</p>
                </div>

                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-600 hover:text-gray-900"><ion-icon name="logo-facebook" class="text-xl"></ion-icon></a>
                    <a href="#" class="text-gray-600 hover:text-gray-900"><ion-icon name="logo-twitter" class="text-xl"></ion-icon></a>
                    <a href="#" class="text-gray-600 hover:text-gray-900"><ion-icon name="logo-instagram" class="text-xl"></ion-icon></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
