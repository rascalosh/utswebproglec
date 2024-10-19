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

<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" value="<?php echo htmlspecialchars($event['name'] ?? '', ENT_QUOTES); ?>" required>
    <input type="date" name="date" value="<?php echo htmlspecialchars($event['date'] ?? '', ENT_QUOTES); ?>" required>
    <input type="time" name="time" value="<?php echo htmlspecialchars($event['time'] ?? '', ENT_QUOTES); ?>" required>
    <input type="text" name="location" value="<?php echo htmlspecialchars($event['location'] ?? '', ENT_QUOTES); ?>" required>
    <textarea name="description"><?php echo htmlspecialchars($event['description'] ?? '', ENT_QUOTES); ?></textarea>
    <input type="number" name="max_participants" value="<?php echo htmlspecialchars($event['max_participants'] ?? '', ENT_QUOTES); ?>" required>
    <input type="file" name="image">
    <input type="file" name="banner">
    <select name="status">
        <option value="open" <?php if (isset($event['status']) && $event['status'] == 'open') echo 'selected'; ?>>Open</option>
        <option value="closed" <?php if (isset($event['status']) && $event['status'] == 'closed') echo 'selected'; ?>>Closed</option>
        <option value="canceled" <?php if (isset($event['status']) && $event['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
    </select>
    <button type="submit" name="edit_event">Update Event</button>
</form>
