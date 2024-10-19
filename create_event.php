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
    $status = 'open'; // Default status when creating a new event

    // Handle image upload
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

<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Event Name" required>
    <input type="date" name="date" required>
    <input type="time" name="time" required>
    <input type="text" name="location" placeholder="Location" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="number" name="max_participants" placeholder="Max Participants" required>
    <input type="file" name="image">
    <input type="file" name="banner">
    <button type="submit" name="create_event">Create Event</button>
</form>
