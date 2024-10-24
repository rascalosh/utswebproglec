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
<body class="font-[Poppins] bg-gradient-to-t from-[#E0EAF9] to-[#F4F7FC] min-h-screen text-gray-900 flex flex-col">
    <!-- Header Template Ke -->
<header class="bg-[#3A3D99] py-3 shadow-lg">
    <nav class="flex justify-between items-center w-[92%] mx-auto">
        <div>
            <a href="index.php"><img class="w-20" src="assets/baileo3.png" alt="..."></a>
        </div>
        <div class="nav-links duration-500 md:static absolute bg-[#3A3D99] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5"> <!-- Changed to solid blue -->
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="index.php">Events</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="registered_events.php">Registered Event</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="profile.php">My Profile</a>
                </li>
            </ul>
        </div>

        <div class="flex items-center gap-6"> 
            <a href="logout.php" class="bg-gradient-to-r from-[#243c9a] via-[#7e22ce] to-[#ec4899] text-white px-6 py-3 rounded-full shadow-md text-lg font-bold tracking-wide uppercase transition-transform transform hover:scale-105 hover:shadow-lg hover:from-[#1e3a8a] hover:via-[#6b21a8] hover:to-[#db2777] duration-300 ease-in-out">
                Log Out
            </a>
            <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-2xl text-white cursor-pointer md:hidden"></ion-icon>
        </div>
    </nav>
</header>


<div class="container mx-auto mt-8">
    <div class="bg-cover bg-center rounded-lg shadow-lg p-8 mb-6 transition-transform transform hover:scale-105 hover:shadow-2xl duration-300 ease-in-out"
         style="background-image: url('assets/banner.jpg');">
        <h2 class="text-4xl font-bold text-white mb-2 flex items-center justify-center">
            <ion-icon name="calendar" class="mr-2 text-3xl"></ion-icon>
            Available Events
        </h2>
        <p class="text-white mb-4 text-center text-lg">Discover and Join Exciting Events Around You!</p>
        <div class="flex justify-center mb-6">
            <div class="relative w-full md:w-1/2">
                <input type="text" id="searchInput" placeholder="Search events..."
                       class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-300 transition duration-300 ease-in-out shadow-lg"
                       onkeyup="searchEvents()">
            </div>
        </div>
    </div>
</div>

<!-- Event List -->
<div id="eventList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
    <?php while ($event = $result->fetch_assoc()) { ?>
    <a href="event_details.php?id=<?php echo $event['id']; ?>" class="event-item bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 transform hover:shadow-2xl hover:scale-105 flex flex-col">
        <img src="<?php echo './admin/', $event['image_path']; ?>" alt="Event Image" class="w-full h-40 object-cover transition-transform duration-300 hover:scale-105">
        <div class="p-4 flex-grow"> 
            <h2 class="event-name text-xl font-semibold text-blue-900 mb-2 hover:text-purple-900 transition-colors duration-200"><?php echo $event['name']; ?></h2>
            <p class="text-gray-700 mb-2"><?php echo $event['description']; ?></p>
            <p class="text-sm text-blue-900 uppercase"><ion-icon name="pin"></ion-icon> <?php echo strtoupper($event['location']); ?></p>
            <p class="text-sm text-blue-900 mb-4"><ion-icon name="calendar"></ion-icon><span class="ml-1"><?php $date = $event['date']; $date_name = date('j F Y', strtotime($date)); echo $date_name; ?></span></p>
        </div>
    </a>
    <?php } ?>
</div>



    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($event = $result->fetch_assoc()) { ?>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <img src="<?php echo './admin/',$event['image_path']; ?>" alt="Event Image" class="w-full h-48 object-cover rounded-t-lg">
            <h2 class="text-xl font-semibold mt-4"><?php echo $event['name']; ?></h2>
            <p class="text-gray-600"><?php echo $event['description']; ?></p>
            <p class="text-sm text-blue-900 uppercase"><ion-icon name="pin"></ion-icon> <?php echo strtoupper($event['location']); ?></p>
            <p class="text-sm text-blue-900""><ion-icon name="calendar"></ion-icon><span class="ml-1"><?php $date = $event['date']; $date_name = date('j F Y', strtotime($date)); echo $date_name;?></p>
            <a href="event_details.php?id=<?php echo $event['id']; ?>" class="mt-4 inline-block bg-purple-600 text-white py-2 px-4 rounded"></a>
        </div>
        <?php } ?>
    </div>
</div>

<div class="flex-col space-y-1 mt-5">
            <div class="mb-5">
                
            </div>
        </div>
    </div>
<!-- Footer -->
    <footer class="bg-[#3A3D99] text-gray-100 py-6 mt-auto">
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

    <script>
        const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
            navLinks.classList.toggle('z-50')
        }
        function searchEvents() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let eventItems = document.querySelectorAll('.event-item');

            eventItems.forEach(function(eventItem) {
                let eventName = eventItem.querySelector('.event-name').innerText.toLowerCase();
                if (eventName.includes(input)) {
                    eventItem.style.display = '';
                } else {
                    eventItem.style.display = 'none';
                }
            });
        }
    </script>
    
</body>
</html>
