<?php
// Include your database connection file here
include 'C:\xampp\htdocs\SCPHKK\admin\connect.php';

// รับค่า reservation_id และ current_date จาก HTTP POST
$reservation_id = $_POST['reservation_id'];
$current_date = $_POST['current_date'];

// ปรับวันที่และเวลาเป็นรูปแบบไทย
date_default_timezone_set('Asia/Bangkok');
$month_names_thai = array(
    1 => 'มกราคม',
    2 => 'กุมภาพันธ์',
    3 => 'มีนาคม',
    4 => 'เมษายน',
    5 => 'พฤษภาคม',
    6 => 'มิถุนายน',
    7 => 'กรกฎาคม',
    8 => 'สิงหาคม',
    9 => 'กันยายน',
    10 => 'ตุลาคม',
    11 => 'พฤศจิกายน',
    12 => 'ธันวาคม'
);
$reserve_date = date("d ") . $month_names_thai[date("n")] . ' ' . (date("Y") + 543) . date(" H:i:s");

// Check if reservation_id and current_date are set and not empty
if(isset($_POST['reservation_id'], $_POST['current_date']) && !empty($_POST['reservation_id']) && !empty($_POST['current_date'])) {
    // Sanitize the input
    $reservation_id = intval($_POST['reservation_id']);
    $current_date = $reserve_date;

    // Check if reservation_id is a positive integer
    if($reservation_id > 0) {
        // Update the status and dateupdate for the reservation with the given ID
        $sql = "UPDATE transaction SET status = 0, dateupdate = ? WHERE transid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $current_date, $reservation_id);

        if($stmt->execute()) {
            // Return success response
            echo json_encode(array("success" => true));
        } else {
            // Return error response if the query fails
            echo json_encode(array("success" => false, "error" => "Failed to update status"));
        }
    } else {
        // Return error response if reservation_id is not a positive integer
        echo json_encode(array("success" => false, "error" => "Invalid reservation ID"));
    }
} else {
    // Return error response if reservation_id or current_date is not set or empty
    echo json_encode(array("success" => false, "error" => "Reservation ID or current date is not provided"));
}

// Close prepared statement
$stmt->close();
// Close database connection
$conn->close();
?>