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

    // Insert event into database
    $stmt = $conn->prepare("INSERT INTO events (name, date, time, location, description, max_participants, image_path, banner_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisss", $name, $date, $time, $location, $description, $max_participants, $image_path, $banner_path, $status);
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
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center relative">
    <a href="indexadmin.php" class="absolute top-5 left-5 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300">
        &larr; Back to Main Page
    </a>

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Create New Event</h1>
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Event Name</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Time</label>
                <input type="time" name="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Location" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Event Description"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Max Participants</label>
                <input type="number" name="max_participants" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Maximum Participants" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Banner</label>
                <input type="file" name="banner" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <div class="text-center">
                <button type="submit" name="create_event" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300">Create Event</button>
            </div>
        </form>
    </div>
</body>
</html>
