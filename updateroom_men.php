<?php
// เชื่อมต่อกับฐานข้อมูล
$conn = mysqli_connect("localhost", "root", "", "dormitory");

// ตรวจสอบการเชื่อมต่อ
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// รับค่า newStatus ที่ส่งมาจาก JavaScript
$newStatus = $_GET['newStatus'];

// รับค่า roomcode ที่ส่งมาจาก JavaScript
$roomcode = $_GET['roomcode'];

// คำสั่ง SQL สำหรับอัปเดตค่า ButtonStatusRoom ในฐานข้อมูล
$sql = "UPDATE room SET ButtonStatusRoom = $newStatus WHERE dormid = 4 AND roomcode = $roomcode";

// ทำการอัปเดตค่า ButtonStatusRoom
if (mysqli_query($conn, $sql)) {
    echo "ButtonStatusRoom updated successfully";
} else {
    echo "Error updating ButtonStatusRoom: " . mysqli_error($conn);
}

// ปิดการเชื่อมต่อ
mysqli_close($conn);
?>