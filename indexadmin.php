<?php
session_start();
require_once 'koneksi.php'; // Include database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all events and count the number of registrants per event
$query = "
    SELECT events.id, events.name, events.date, events.time, events.location, events.status,
           events.image_path, events.banner_path,
           COUNT(registrations.id) AS total_registrants
    FROM events
    LEFT JOIN registrations ON events.id = registrations.event_id
    GROUP BY events.id
";

$result = $conn->query($query);  // Run the query

if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a { margin-right: 10px; }
        .event-image, .event-banner { width: 100px; height: auto; } /* Set appropriate width */
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="logout.php">Logout</a>

    <h2>Available Events</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Status</th>
                <th>Total Registrants</th>
                <th>Image</th> <!-- Added Image Column -->
                <th>Banner</th> <!-- Added Banner Column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) : ?>
                <?php while ($event = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['name']); ?></td>
                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                        <td><?php echo htmlspecialchars($event['time']); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo htmlspecialchars($event['status']); ?></td>
                        <td><?php echo $event['total_registrants']; ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($event['image_path']); ?>" class="event-image" alt="Event Image">
                        </td>
                        <td>
                            <img src="<?php echo htmlspecialchars($event['banner_path']); ?>" class="event-banner" alt="Event Banner">
                        </td>
                        <td class="actions">
                            <a href="edit_event.php?id=<?php echo $event['id']; ?>">Edit</a>
                            <a href="delete_event.php?id=<?php echo $event['id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Manage</h2>
    <ul>
        <li><a href="create_event.php">Create New Event</a></li>
        <li><a href="user_registration.php">User Registration Management</a></li>
    </ul>
</body>
</html>
