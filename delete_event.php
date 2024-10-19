<?php
session_start();
require_once 'koneksi.php'; // Include database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if 'id' is provided in the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // First, fetch the event to ensure it exists
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Event found, now prepare to delete
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $event_id);
        
        if ($stmt->execute()) {
            echo "Event deleted successfully!";
            header("Location : indexadmin.php");
        } else {
            echo "Error deleting event: " . $stmt->error;
            header("Location : indexadmin.php");
        }
    } else {
        echo "No event found with the provided ID.";
        
    }

    // Free the result and close the statement
    $result->free();
    $stmt->close();
} else {
    echo "No ID specified for deletion.";
}

$conn->close();
?>
