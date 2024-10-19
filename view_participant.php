<?php
include 'koneksi.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

session_start();


if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (!isset($_GET['id'])) {
    die("Event ID not provided.");
}

$event_id = $_GET['id'];


$stmt = $conn->prepare("SELECT u.username, u.email, e.name, r.registration_date FROM users u 
                        JOIN registrations r ON u.id = r.user_id
                        JOIN events e ON e.id = r.event_id
                        WHERE r.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$registrants = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['export'])) {
    $fileType = $_POST['file_type'];


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $sheet->setCellValue('A1', 'Username');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Event');
    $sheet->setCellValue('D1', 'Registration Date');

    $rowNumber = 2;
    foreach ($registrants as $registrant) {
        $sheet->setCellValue("A$rowNumber", $registrant['username']);
        $sheet->setCellValue("B$rowNumber", $registrant['email']);
        $sheet->setCellValue("C$rowNumber", $registrant['name']);
        $sheet->setCellValue("D$rowNumber", $registrant['registration_date']);
        $rowNumber++;
    }

    if ($fileType == 'excel') {
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="registrants.xlsx"');
        $writer->save('php://output');
    } elseif ($fileType == 'csv') {
        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="registrants.csv"');
        $writer->save('php://output');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registrants</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Event Registrants</h1>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Username</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">Event</th>
                    <th class="py-2 px-4 border-b">Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrants as $registrant): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['username']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['email']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['name']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['registration_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form method="post" class="mt-4">
            <label for="file_type" class="mr-2">Export as:</label>
            <select name="file_type" id="file_type" class="border px-2 py-1">
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
            <button type="submit" name="export" class="bg-blue-500 text-white px-4 py-2 rounded">Export</button>
        </form>

        <a href="indexadmin.php" class="text-blue-600 mt-4 block">Back to Dashboard</a>
    </div>
</body>
</html>
