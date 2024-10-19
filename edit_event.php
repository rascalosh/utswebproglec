<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    
    // Use bind_param to securely pass the event ID
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    // Check if the event data was retrieved
    if ($event) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_event'])) {
            $name = $_POST['name'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            $location = $_POST['location'];
            $description = $_POST['description'];
            $max_participants = $_POST['max_participants'];
            $status = $_POST['status'];

            // Use existing image and banner paths if no new files are uploaded
            $image_path = $event['image_path'];
            $banner_path = $event['banner_path'];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_path = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }

            // Handle banner upload
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
                $banner_path = 'uploads/' . basename($_FILES['banner']['name']);
                move_uploaded_file($_FILES['banner']['tmp_name'], $banner_path);
            }

            // Update event in the database
            $stmt = $conn->prepare("UPDATE events SET name = ?, date = ?, time = ?, location = ?, description = ?, max_participants = ?, image_path = ?, banner_path = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssssisssi", $name, $date, $time, $location, $description, $max_participants, $image_path, $banner_path, $status, $event_id);
            $stmt->execute();

            echo "Event updated successfully!";
            header("Location: indexadmin.php");
            exit();
        }
    } else {
        echo "Event not found or failed to retrieve event details.";
        header("Location: indexadmin.php");
        exit();
    }
} else {
    echo "No event ID specified.";
    header("Location: indexadmin.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center relative">

    <!-- Back Button at Top Left -->
    <a href="indexadmin.php" class="absolute top-5 left-5 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300">
        &larr; Back to Main Page
    </a>

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Edit Event</h1>
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Event Name</label>
                <input type="text" name="name" value="<?php echo $event['name'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" value="<?php echo $event['date'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Time</label>
                <input type="time" name="time" value="<?php echo $event['time'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="location" value="<?php echo $event['location'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"><?php echo $event['description'] ?? ''; ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Max Participants</label>
                <input type="number" name="max_participants" value="<?php echo $event['max_participants'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Banner</label>
                <input type="file" name="banner" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="open" <?php echo ($event['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
                    <option value="closed" <?php echo ($event['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
                    <option value="canceled" <?php echo ($event['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" name="edit_event" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300">Update Event</button>
            </div>
        </form>
    </div>
</body>
</html>

