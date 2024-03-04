<?php
// ทำการเชื่อมต่อกับฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "dormitory");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// รับค่า year และ term จากการ POST โดยตรวจสอบว่ามีการส่งค่าผ่าน POST หรือไม่
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';
$selectedTerm = isset($_POST['term']) ? $_POST['term'] : '';

// ทำการสร้างคำสั่ง SQL เพื่อดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM dorm WHERE dormid = 4";
$q_m = $conn->query($sql);
$d_m = $q_m->fetch_assoc();

// ตรวจสอบว่ามีการส่งค่า year และ term ผ่านการ POST หรือไม่ก่อนที่จะดึงข้อมูล
if ($selectedYear !== '' && $selectedTerm !== '') {
    $sql = "SELECT * FROM transaction WHERE years = '$selectedYear' AND term = '$selectedTerm'";
    $q_y = $conn->query($sql);
    $d_y = $q_y->fetch_assoc();

    echo '<h4>' . $d_m['name'] . '</h4>';
    echo '<h5>ปีการศึกษา : ' . $d_y['years'] . ' เทอม : ' . $d_y['term'] . '</h5>';

    // เพิ่มปุ่มออกรายงาน
    echo '<button id="exportButton" class="mb-2 mr-2 btn btn-primary" onclick="exportReport()">ออกรายงาน</button>';

    // เพิ่มสคริปต์ JavaScript
    echo '<script>';
    echo 'function exportReport() {';
    echo '    window.location.href = "historical_exel_men.php?year=' . urlencode($selectedYear) . '&term=' . urlencode($selectedTerm) . '";';
    echo '}';
    echo '</script>';

    // ดึงข้อมูลห้องพักทั้งหมดที่เป็นชาย
    $room_data = $conn->query("SELECT * FROM room WHERE gender = 1");

    while ($room_row = $room_data->fetch_assoc()) {
        // ดึงข้อมูลผู้เข้าพักในห้องนั้นๆ ที่มี status เป็น 1
        $reservation_data = $conn->query("SELECT t.*, m.studentid, m.name AS student_name, m.surname AS student_surname, m.course AS student_course, m.province, m.phone
                            FROM transaction t 
                            JOIN member m ON t.stdid = m.memberid 
                            JOIN room r ON t.roomid = r.roomid
                            WHERE t.roomid = {$room_row['roomid']} AND t.years = '$selectedYear' AND t.term = '$selectedTerm' AND t.status = 1
                            ORDER BY t.datecreate ASC"); // ASC เพื่อเรียงลำดับจากน้อยไปมาก (จากเร็วสุดไปช้าสุด)
        echo '<table class="mb-0 table table-bordered room-table">';
        echo '<thead>
                <tr>
                    <th style="border: 2px solid #000000;" colspan="10">ชั้นที่ ' . $room_row['floor'] . ' ห้องที่ ' . $room_row['roomcode'] . '</th>
                </tr>
                <tr>
                    <th style="width: 5%; border: 1px solid #000000;">ลำดับ</th>
                    <th style="width: 10%; border: 1px solid #000000;">ชื่อ</th>
                    <th style="width: 10%; border: 1px solid #000000;">นามสกุล</th>
                    <th style="width: 10%; border: 1px solid #000000;">หลักสูตร</th>
                    <th style="width: 10%; border: 1px solid #000000;">จังหวัด</th>
                    <th style="width: 10%; border: 1px solid #000000;">วันที่และเวลาที่จอง</th>
                    <th style="width: 15%; border: 1px solid #000000;">วันที่ออก</th>
                    <th style="width: 10%; border: 1px solid #000000;">เบอร์โทร</th>
                </tr>
            </thead>';
        echo '<tbody>';
        $count = 1;
        while ($reservation_row = $reservation_data->fetch_assoc()) {
            echo '<tr>
                    <td style="border: 1px solid #000000;">' . $count . '</td>
                    <td style="border: 1px solid #000000;">' . $reservation_row['student_name'] . '</td>
                    <td style="border: 1px solid #000000;">' . $reservation_row['student_surname'] . '</td>
                    <td style="border: 1px solid #000000;">' . $reservation_row['student_course'] . '</td>
                    <td style="border: 1px solid #000000;">' . (isset($reservation_row['province']) ? $reservation_row['province'] : '') . '</td>
                    <td style="border: 1px solid #000000;">' . (isset($reservation_row['datecreate']) ? $reservation_row['datecreate'] : '') . '</td>
                    <td style="border: 1px solid #000000;">' . (isset($reservation_row['dateupdate']) ? $reservation_row['dateupdate'] : '') . '</td>
                    <td style="border: 1px solid #000000;">' . (isset($reservation_row['phone']) ? $reservation_row['phone'] : '') . '</td>                                                              
                </tr>';
            $count++;
        }
        echo '</tbody>';
        echo '</table>';
        echo '<br>'; // เพิ่มบรรทัดว่าง
    }

    // ตรวจสอบว่ามีการย้ายออกห้องหรือไม่
    $room_data = $conn->query("SELECT * FROM room WHERE gender = 1");

    $checkout_data = $conn->query("SELECT t.*, m.studentid, m.name AS student_name, m.surname AS student_surname, m.course AS student_course, m.province, m.phone
            FROM transaction t 
            JOIN member m ON t.stdid = m.memberid 
            JOIN room r ON t.roomid = r.roomid
            WHERE t.years = '$selectedYear' AND t.term = '$selectedTerm' AND t.status = 0
            ORDER BY t.datecreate ASC"); // ASC เพื่อเรียงลำดับจากน้อยไปมาก (จากเร็วสุดไปช้าสุด)
    if ($checkout_data->num_rows > 0) {
        echo '<h2>ตารางการย้ายออก</h2>';
        echo '<table class="mb-0 table table-bordered room-table">';
        echo '<thead>
                <tr>
                    <th style="border: 2px solid #000000;" colspan="10">การย้ายออก</th>
                </tr>
                <tr>
                    <th style="width: 5%; border: 1px solid #000000;">ลำดับ</th>
                    <th style="width: 10%; border: 1px solid #000000;">ชื่อ</th>
                    <th style="width: 10%; border: 1px solid #000000;">นามสกุล</th>
                    <th style="width: 10%; border: 1px solid #000000;">หลักสูตร</th>
                    <th style="width: 10%; border: 1px solid #000000;">จังหวัด</th>
                    <th style="width: 10%; border: 1px solid #000000;">วันที่และเวลาที่จอง</th>
                    <th style="width: 15%; border: 1px solid #000000;">วันที่ออก</th>
                    <th style="width: 10%; border: 1px solid #000000;">เบอร์โทร</th>
                </tr>
            </thead>';
        echo '<tbody>';
        $count = 1;
        while ($checkout_row = $checkout_data->fetch_assoc()) {
            echo '<tr>
                        <td style="border: 1px solid #000000;">' . $count . '</td>
                        <td style="border: 1px solid #000000;">' . $checkout_row['student_name'] . '</td>
                        <td style="border: 1px solid #000000;">' . $checkout_row['student_surname'] . '</td>
                        <td style="border: 1px solid #000000;">' . $checkout_row['student_course'] . '</td>
                        <td style="border: 1px solid #000000;">' . (isset($checkout_row['province']) ? $checkout_row['province'] : '') . '</td>
                        <td style="border: 1px solid #000000;">' . (isset($checkout_row['datecreate']) ? $checkout_row['datecreate'] : '') . '</td>
                        <td style="border: 1px solid #000000;">' . (isset($checkout_row['dateupdate']) ? $checkout_row['dateupdate'] : '') . '</td>                                        
                        <td style="border: 1px solid #000000;">' . (isset($checkout_row['phone']) ? $checkout_row['phone'] : '') . '</td>                                                              
                    </tr>';
            $count++;
        }
        echo '</tbody>';
        echo '</table>';
    }
} else {
    // ถ้าไม่มีการส่งค่า year และ term ผ่านการ POST ให้แสดงข้อความว่า "กรุณาเลือกปีและเทอม"
    echo '<p>กรุณาเลือกปีและเทอม</p>';
}
$conn->close(); // ปิดการเชื่อมต่อกับฐานข้อมูล
?>