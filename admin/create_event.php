<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $details = $_POST['details'];
    $max_participants = $_POST['max_participants'];
    $status = 'open'; 

    $image_path = '';
    $banner_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
        $banner_path = 'uploads/' . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner_path);
    }

    
    $stmt = $conn->prepare("INSERT INTO events (name, date, time, location, location_details, description, details, max_participants, image_path, banner_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssisss", $name, $date, $time, $location, $location_details, $description, $details, $max_participants, $image_path, $banner_path, $status);
    $stmt->execute();

    echo "Event created successfully!";
    header("Location: indexadmin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-[Poppins] min-h-screen bg-gradient-to-t from-[#E0EAF9] to-[#F4F7FC] flex flex-col">
<!-- Header Template -->
<header class="bg-[#3A3D99] py-3 shadow-lg mb-10">
    <nav class="flex justify-between items-center w-[92%] mx-auto">
        <div>
            <a href="indexadmin.php"><img class="w-20" src="assets/baileo3.png" alt="..."></a>
        </div>
        <div class="nav-links duration-500 md:static absolute bg-[#3A3D99] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5"> <!-- Changed to solid blue -->
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="indexadmin.php">Dashboard</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="create_event.php">Create Event</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="user_management.php">Manage Users</a>
                </li>
            </ul>
        </div>

        <div class="flex items-center gap-6"> 
            <a href="../logout.php" class="bg-gradient-to-r from-[#243c9a] via-[#7e22ce] to-[#ec4899] text-white px-6 py-3 rounded-full shadow-md text-lg font-bold tracking-wide uppercase transition-transform transform hover:scale-105 hover:shadow-lg hover:from-[#1e3a8a] hover:via-[#6b21a8] hover:to-[#db2777] duration-300 ease-in-out">
                Log Out
            </a>
            <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-2xl text-white cursor-pointer md:hidden"></ion-icon>
        </div>
    </nav>
</header>
    <script>
        const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
            navLinks.classList.toggle('z-50')
        }
    </script>
    <!-- Main Content -->
<main class="flex-grow flex justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto transform transition-transform duration-500 hover:scale-105 hover:shadow-xl">
        <h1 class="text-2xl font-bold mb-6 text-center flex items-center justify-center space-x-2 fade-in">
            <ion-icon name="add-circle" class="text-3xl text-indigo-600"></ion-icon>
            <span>Create New Event</span>
        </h1>
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Event Name</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Time</label>
                <input type="time" name="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Location" required>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Location Details</label>
                <input type="text" name="location_details" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Location details" required>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Event Description"></textarea>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Details</label>
                <textarea name="details" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Event Details"></textarea>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Max Participants</label>
                <input type="number" name="max_participants" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Maximum Participants" required>
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <div class="transition duration-500 ease-in-out transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700">Upload Banner</label>
                <input type="file" name="banner" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <div class="text-center">
                <button type="submit" name="create_event" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 transition duration-300 transform hover:scale-105">Create Event</button>
            </div>
        </form>
    </div>
</main>

<!-- Footer -->
    <footer class="bg-[#3A3D99] text-gray-100 py-6 mt-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left">
                    <p class="text-sm">&copy; <?php echo date("Y"); ?> Baileo Event Organizer. All rights reserved.</p>
                </div>

                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-300 hover:text-white"><ion-icon name="logo-facebook" class="text-xl"></ion-icon></a>
                    <a href="#" class="text-gray-300 hover:text-white"><ion-icon name="logo-twitter" class="text-xl"></ion-icon></a>
                    <a href="#" class="text-gray-300 hover:text-white"><ion-icon name="logo-instagram" class="text-xl"></ion-icon></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
