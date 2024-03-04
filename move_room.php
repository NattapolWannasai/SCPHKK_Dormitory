<?php
include 'C:\xampp\htdocs\SCPHKK\admin\connect.php';

// ตรวจสอบว่ามีการส่งข้อมูลผ่านวิธี POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูลที่ส่งมาและไม่ว่างเปล่าหรือไม่
    if (isset($_POST['source_room_code']) && isset($_POST['target_room_code']) && isset($_POST['reservation_id'])) {
        // รับค่าที่ส่งมาจากฟอร์ม
        $sourceRoomCode = $_POST['source_room_code'];
        $targetRoomCode = $_POST['target_room_code'];
        $reservationId = $_POST['reservation_id'];

        // เพิ่มเงื่อนไขการตรวจสอบว่าห้องมีพื้นที่ว่างเพียงพอสำหรับย้ายผู้เข้าพักเข้าไปหรือไม่
        $targetRoomHeadcount = $conn->query("SELECT headcount FROM room WHERE roomcode = '{$targetRoomCode}'")->fetch_assoc()['headcount'];
        $currentOccupancy = $conn->query("SELECT COUNT(*) AS count FROM transaction WHERE roomid = (SELECT roomid FROM room WHERE roomcode = '{$targetRoomCode}')")->fetch_assoc()['count'];
        if ($currentOccupancy >= $targetRoomHeadcount) {
            // หากห้องเต็มแล้ว ไม่สามารถย้ายผู้เข้าพักเข้าไปได้
            $response = array('success' => false, 'error' => 'Room is full');
        } else {
            // กำหนดคำสั่ง SQL สำหรับอัปเดตข้อมูลห้อง
            $sql = "UPDATE transaction 
                    SET roomid = (SELECT roomid FROM room WHERE roomcode = ?) 
                    WHERE transid = ?";

            // เตรียมและ execute คำสั่ง SQL
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                // ผูกค่าและ execute คำสั่ง SQL
                $stmt->bind_param("si", $targetRoomCode, $reservationId);
                $stmt->execute();

                // ตรวจสอบผลลัพธ์หลังจาก execute คำสั่ง SQL
                if ($stmt->affected_rows > 0) {
                    // ถ้ามีการอัปเดตข้อมูลสำเร็จ
                    $response = array('success' => true);
                } else {
                    // หากไม่มีการอัปเดตข้อมูล
                    $response = array('success' => false, 'error' => 'Failed to update room data');
                }

                // ปิดคำสั่ง SQL
                $stmt->close();
            } else {
                // หากเกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL
                $response = array('success' => false, 'error' => 'Failed to prepare SQL statement');
            }
        }
    } else {
        // หากข้อมูลไม่สมบูรณ์หรือไม่ถูกต้อง
        $response = array('success' => false, 'error' => 'Incomplete or invalid data');
    }
} else {
    // หากไม่มีการเรียกใช้งานผ่านวิธี POST
    $response = array('success' => false, 'error' => 'POST method not used');
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();

// ส่งผลลัพธ์กลับไปยัง client เป็น JSON
echo json_encode($response);
?>
