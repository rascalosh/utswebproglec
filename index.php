<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$query = "SELECT * FROM events WHERE status = 'open'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
</head>
<body class="font-[Poppins] bg-gradient-to-t from-[#fbc2eb] to-[#a6c1ee] h-screen text-gray-900 flex flex-col">
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
<!-- Event List -->
<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-bold text-center mb-6">Available Events</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($event = $result->fetch_assoc()) { ?>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <img src="<?php echo './admin/',$event['image_path']; ?>" alt="Event Image" class="w-full h-48 object-cover rounded-t-lg">
            <h2 class="text-xl font-semibold mt-4"><?php echo $event['name']; ?></h2>
            <p class="text-gray-600"><?php echo $event['description']; ?></p>
            <p class="text-sm text-gray-500">Location: <?php echo $event['location']; ?></p>
            <p class="text-sm text-gray-500">Date: <?php echo $event['date']; ?></p>
            <a href="event_details.php?id=<?php echo $event['id']; ?>" class="mt-4 inline-block bg-purple-600 text-white py-2 px-4 rounded">View Details</a>
        </div>
        <?php } ?>
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
