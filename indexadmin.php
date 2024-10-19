<?php
session_start();
require_once 'koneksi.php'; 


$message = '';
$showModal = false; 

if (isset($_GET['delete_id'])) {
    $event_id = $_GET['delete_id'];


    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            $message = "Event deleted successfully!";
            $showModal = true; 
        } else {
            $message = "Error deleting event: " . $stmt->error;
            $showModal = true; 
        }
    } else {
        $message = "Event not found.";
        $showModal = true;
    }

    $stmt->close();
}

$total_events_query = "SELECT COUNT(*) AS total FROM events";
$total_result = $conn->query($total_events_query);
$total_row = $total_result->fetch_assoc();
$total_events = $total_row['total'];


if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$results_per_page = 5;


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;


$total_query = "SELECT COUNT(*) AS total FROM events";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_events = $total_row['total'];
if($total_events == 0){
    $total_pages = 1;
} else
$total_pages = ceil($total_events / $results_per_page);


$query = "
    SELECT events.id, events.name, events.date, events.time, events.location, events.status,
           events.image_path, events.banner_path, events.max_participants,
           COUNT(registrations.id) AS total_registrants
    FROM events
    LEFT JOIN registrations ON events.id = registrations.event_id
    GROUP BY events.id
    LIMIT $start_from, $results_per_page
";

$result = $conn->query($query);

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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mt-8 mb-6 bg-gray">Admin Dashboard</h1>
        <div class="flex justify-between mb-4">
        <form action="logout.php" method="get" class="inline">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                Logout
            </button>
        </form>
        <form action="user_management.php" method="get" class="inline">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Manage Users
            </button>
        </form>
        </div>

        <h2 class="text-2xl font-semibold mb-4">Available Events</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead>
                    <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6">Event Name</th>
                        <th class="py-3 px-6">Date</th>
                        <th class="py-3 px-6">Time</th>
                        <th class="py-3 px-6">Location</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Total Registrants</th>
                        <th class="py-3 px-6">Image</th>
                        <th class="py-3 px-6">Banner</th>
                        <th class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($event = $result->fetch_assoc()) : ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6"><?php echo $event['name']; ?></td>
                                <td class="py-3 px-6"><?php echo $event['date']; ?></td>
                                <td class="py-3 px-6"><?php echo $event['time']; ?></td>
                                <td class="py-3 px-6"><?php echo $event['location']; ?></td>
                                <td class="py-3 px-6"><?php echo $event['status']; ?></td>
                                <td class="py-3 px-6"><?php echo $event['total_registrants'] ,'/', $event['max_participants']; ?></td>
                                <td class="py-3 px-6">
                                    <img src="<?php echo $event['image_path']; ?>" class="w-24 h-auto rounded-lg" alt="Event Image">
                                </td>
                                <td class="py-3 px-6">
                                    <img src="<?php echo $event['banner_path']; ?>" class="w-24 h-auto rounded-lg" alt="Event Banner">
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex items-center space-x-3">
                                        <form action="edit_event.php" method="get" class="inline">
                                            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                                Edit
                                            </button>
                                        </form>
                                        <form action="indexadmin.php" method="get" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            <input type="hidden" name="delete_id" value="<?php echo $event['id']; ?>">
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Delete</button>
                                        </form>

                                        <form action="view_participant.php" method="get" class="inline">
                                            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                                View Participant
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center py-6">No events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($showModal): ?>
        <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80">
                <h2 class="text-lg font-bold mb-2">Notification</h2>
                <p class="mb-4"><?php echo $message; ?></p>
                <div class="flex justify-end">
                    <button onclick="closeModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Close</button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="mt-4 flex justify-between">
            <?php if ($page > 1 && $total_pages > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Previous
                </a>
            <?php else: ?>
                <span class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg cursor-not-allowed">Previous</span>
            <?php endif; ?>

            <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

            <?php if ($page < $total_pages && $total_pages > 1): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Next
                </a>
            <?php else: ?>
                <span class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg cursor-not-allowed">Next</span>
            <?php endif; ?>
        </div>


        <div class="flex-col space-y-1 mt-5">
            <div class="mb-5">
                <form action="create_event.php" method="get" class="inline">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Create Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
