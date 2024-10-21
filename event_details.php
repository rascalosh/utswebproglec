<?php
include 'koneksi.php';
session_start();

$already_registered = false; // Flag to indicate registration status

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $query = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
} else {
    header('Location: events.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $user_id = $_SESSION['user_id'];

    // Check if the user is already registered for this event
    $check_query = "SELECT * FROM registrations WHERE event_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $event_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // User is already registered for this event
        $already_registered = true; // Set the flag to true
    } else {
        // Proceed with registration
        $registration_date = date("Y-m-d H:i:s");
        $query = "INSERT INTO registrations (event_id, user_id, registration_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $event_id, $user_id, $registration_date);

        if ($stmt->execute()) {
            header('Location: registered_events.php');
            exit();
        } else {
            echo "Error: Could not register for the event.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }
    </script>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
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
                <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-2xl cursor-pointer md:hidden"></ion-icon>
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
        <img src="<?php echo './admin/', $event['banner_path']; ?>" alt="Banner Image" class="w-full h-64 object-cover rounded-t-lg">
        <h1 class="text-3xl font-bold mt-4"><?php echo $event['name']; ?></h1>
        <p class="text-gray-600 mt-2"><?php echo $event['description']; ?></p>
        <p class="text-sm text-gray-500 mt-2">Location: <?php echo $event['location']; ?></p>
        <p class="text-sm text-gray-500 mt-2">Date: <?php echo $event['date']; ?></p>
        <p class="text-sm text-gray-500 mt-2">Time: <?php echo $event['time']; ?></p>
        <form method="post" class="mt-4">
            <button type="submit" name="register" class="bg-purple-600 text-white py-2 px-4 rounded">Register</button>
        </form>
    </div>
</div>

<?php if ($already_registered): ?>
        <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80">
                <h2 class="text-lg font-bold mb-2">Notification</h2>
                <p class="mb-4"><?php echo "You have registered for this event"; ?></p>
                <div class="flex justify-end">
                    <button onclick="closeModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Close</button>
                </div>
            </div>
        </div>
<?php endif; ?>
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
