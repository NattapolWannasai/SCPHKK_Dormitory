<?php
// เชื่อมต่อกับฐานข้อมูล
$conn = mysqli_connect("localhost", "root", "", "dormitory");

// ตรวจสอบการเชื่อมต่อ
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// รับค่า newStatus ที่ส่งมาจาก JavaScript
$newStatus = $_GET['newStatus'];

// รับค่า floor ที่ส่งมาจาก JavaScript
$floor = $_GET['floor'];

// คำสั่ง SQL สำหรับอัปเดตค่า ButtonStatusFloor ในฐานข้อมูล
$sql = "UPDATE room SET ButtonStatusFloor = $newStatus WHERE dormid = 4 AND floor = $floor";

// ทำการอัปเดตค่า ButtonStatusFloor
if (mysqli_query($conn, $sql)) {
    echo "ButtonStatusFloor updated successfully";
} else {
    echo "Error updating ButtonStatusFloor: " . mysqli_error($conn);
}

// ปิดการเชื่อมต่อ
mysqli_close($conn);
?>