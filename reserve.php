<?php
session_start();
// ตัวอย่างการกำหนดค่า $conn ในไฟล์ที่เรียกใช้
include 'C:\xampp\htdocs\SCPHKK\admin\connect.php';

// ตรวจสอบว่ามีการกดปุ่ม "จอง" หรือไม่
if (isset($_POST['reserve_button'])) {
    // ตรวจสอบห้องที่เลือก
    $selected_room_code = $_POST['selected_room_code']; // ค่านี้คือหมายเลขห้องที่เลือก

    // ดึงข้อมูล years และ term จากตาราง conflict_years
    $get_conflict_years = $conn->prepare("SELECT years, term FROM conflict_years");
    $get_conflict_years->execute();

    // ผลลัพธ์จาก query
    $result_years = $get_conflict_years->get_result();

    // ดึงข้อมูลแถวเดียวจากผลลัพธ์
    $row_years = $result_years->fetch_assoc();

    // ปิด statement
    $get_conflict_years->close();

    // กำหนดค่าให้กับ $years และ $term
    $years = $row_years['years'];
    $term = $row_years['term'];

    // เพิ่มเงื่อนไขตรวจสอบจำนวนคนที่จองห้องนี้แล้ว
    $check_reservation_count = $conn->prepare("SELECT COUNT(*) as count FROM transaction WHERE roomid = ? AND years = ? AND term = ?");

    $check_reservation_count->bind_param("iss", $selected_room_code, $years, $term);
    $check_reservation_count->execute();

    $result = $check_reservation_count->get_result();
    $row = $result->fetch_assoc();
    $reservation_count = $row['count'];

    // ปิด statement
    $check_reservation_count->close();

    // ตรวจสอบว่าห้องนี้จะรองรับคนได้หรือไม่
    $get_room_headcount = $conn->prepare("SELECT headcount FROM room WHERE roomcode = ?");
    $get_room_headcount->bind_param("i", $selected_room_code);
    $get_room_headcount->execute();

    $result_headcount = $get_room_headcount->get_result();
    $row_headcount = $result_headcount->fetch_assoc();
    $room_headcount = $row_headcount['headcount'];

    // ปิด statement
    $get_room_headcount->close();

    // เช็คว่ามีการจองห้องพักไปแล้วหรือไม่
    $check_previous_reservation = $conn->prepare("SELECT COUNT(*) as count FROM transaction WHERE stdid = ? AND years = ? AND term = ?");
    $check_previous_reservation->bind_param("iss", $_SESSION['memberid'], $years, $term);
    $check_previous_reservation->execute();
    $result_previous_reservation = $check_previous_reservation->get_result();
    $row_previous_reservation = $result_previous_reservation->fetch_assoc();
    $previous_reservation_count = $row_previous_reservation['count'];

    // ปิด statement
    $check_previous_reservation->close();

    // เช็คว่ายังสามารถจองได้หรือไม่ และห้องว่างอยู่
    if ($previous_reservation_count > 0) {
        // ตรวจสอบว่ามีการจองห้องพักไปแล้วหรือไม่
        if ($room_headcount - $reservation_count > 0) {
            echo '<script>';
            echo 'alert("คุณได้ทำการจองห้องพักไปแล้ว ไม่สามารถทำการจองซ้ำได้");';
            echo 'window.history.back();'; // กลับไปยังหน้าที่เดิม
            echo '</script>';
        } else {
            echo '<script>';
            echo 'alert("ห้องนี้มีผู้จองเต็มแล้ว กรุณาเลือกห้องอื่น");';
            echo 'window.history.back();'; // กลับไปยังหน้าที่เดิม
            echo '</script>';
        }
    } else {
        // ทำการบันทึกข้อมูลการจองในฐานข้อมูล
        $memberid = $_SESSION['memberid']; // ค่านี้คือ memberid ของผู้ใช้ที่ล็อกอิน
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
        $thoughts = "ข้อความคิดเกี่ยวกับการบันทึกข้อมูลที่นี่...";
        echo $reserve_date . " " . $thoughts;

        $status = 1; //  คือ ยังพักอยู่

        // เพิ่มข้อมูลการจองในตาราง transaction
        $transid = generateRandomString(8); // ฟังก์ชันสร้างข้อมูลสตริงแบบสุ่ม 8 ตัวอักษร
        // ดึงข้อมูล roomid จากตาราง room
        $get_room_id = $conn->prepare("SELECT roomid FROM room WHERE roomcode = ?");
        $get_room_id->bind_param("i", $selected_room_code);
        $get_room_id->execute();
        $result_room_id = $get_room_id->get_result();
        $row_room_id = $result_room_id->fetch_assoc();
        $room_id = $row_room_id['roomid'];

        // ปิด statement
        $get_room_id->close();

        // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลการจอง
        $insert_reservation = $conn->prepare("INSERT INTO transaction (transid, stdid, roomid, prsupdate, datecreate, status, years, term) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // ผูกค่าพารามิเตอร์
        $insert_reservation->bind_param("isisssis", $transid, $memberid, $room_id, $_SESSION['memberid'], $reserve_date, $status, $years, $term);
        $insert_reservation->execute();

        // ปิด statement
        $insert_reservation->close();

        // แสดงข้อความแบบ modal แล้วเด้งไปยังหน้า profile.php
        echo '<script>';
        echo 'alert("การจองสำเร็จ!");';
        echo 'window.location.href = "profile.php";';
        echo '</script>';
    }
} else {
    // แสดงข้อความแบบ modal แล้วเด้งไปยังหน้า roomselect.php
    echo '<script>';
    echo 'alert("ห้องนี้มีผู้จองเต็มแล้ว กรุณาเลือกห้องอื่น");';
    echo 'document.getElementById("reserve_button").disabled = true;'; // ปิดปุ่มจอง
    echo 'document.getElementById("reserve_button").classList.add("btn-secondary");'; // เพิ่ม CSS class เพื่อแสดงให้เห็นว่าปุ่มนี้ไม่สามารถใช้งานได้
    echo '</script>';
    // exit; // ไม่จำเป็นต้องใช้ exit ในกรณีนี้
}

// ฟังก์ชันสร้างข้อมูลสตริงแบบสุ่ม
function generateRandomString($length = 8)
{
    $characters = '0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}
?>