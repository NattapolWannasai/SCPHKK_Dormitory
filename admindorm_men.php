<!doctype html>
<html lang="en">
<?php require_once "admin/connect.php" ?>
<?php session_start(); ?>

<?php
// เช็คว่ามีการล็อกอินอยู่หรือไม่
if (!isset($_SESSION['memberid'])) {
    // ถ้าไม่ได้ล็อกอิน ให้ redirect กลับไปที่หน้า login.php
    header('Location: login.php');
    exit; // หยุดการทำงานของ script ต่อ
}

// เช็คว่าสถานะเป็น 1 หรือไม่
if ($_SESSION['status'] != '1') {
    // ถ้าสถานะไม่ใช่ 1 ให้ redirect กลับไปที่หน้า index.php หรือหน้าที่ต้องการให้ผู้ไม่มีสิทธิ์เข้าถึง
    header('Location: index.php');
    exit; // หยุดการทำงานของ script ต่อ
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>เจ้าหน้าที่</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
    <link href="./main.css" rel="stylesheet">
</head>

<?php
$q_m = $conn->query("SELECT * FROM `dorm` WHERE `dormid`= 4");
$d_m = $q_m->fetch_assoc();
?>

<?php
$q_y = $conn->query("SELECT * FROM `conflict_years` WHERE `years` && `term`");
$d_y = $q_y->fetch_assoc();
?>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow" style="background-color: #800080">
            <div class="app-header__logo" style="font-size: 0.9rem; color: snow;">
                <img src="assets/images/logo-inverse.png" alt="Logo" width="45" height="52"
                    style="vertical-align: middle;">
                <a href="admin.php" style="vertical-align: middle; font-size: 0.78rem; color: white;">&nbsp;
                    วิทยาลัยการสาธารณสุขสิรินธร<br>&nbsp; จังหวัดขอนแก่น</a>
                <!-- <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div> -->
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button"
                        class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header__content">
                <!-- <div class="app-header-left">
                    <div class="search-wrapper">
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Type to search">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="close"></button>
                    </div>
                </div> -->
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        <?php
                                        // ตรวจสอบว่ามีข้อมูลสมาชิกที่ล็อกอินอยู่หรือไม่
                                        if (isset($_SESSION['memberid'])) {
                                            // ใช้ memberid ที่อยู่ใน session ในการดึงข้อมูลจากฐานข้อมูล
                                            $memberid = $_SESSION['memberid'];

                                            // ใช้ parameterized query เพื่อป้องกัน SQL injection
                                            $q_members = $conn->prepare("SELECT * FROM `member` WHERE `memberid` = ?");
                                            $q_members->bind_param("i", $memberid);
                                            $q_members->execute();

                                            $result = $q_members->get_result();

                                            // ดึงข้อมูลที่ได้จาก query
                                            $d_members = $result->fetch_assoc();

                                            // ตรวจสอบว่า $d_members ไม่มีข้อมูลหรือว่า name เป็นค่าว่าง
                                            if (!empty($d_members) && !empty($d_members["name"])) {
                                                echo "<div class='text-white'>";
                                                echo $d_members["name"] . " " . $d_members["surname"];
                                                echo "</div>";
                                            } else {
                                                echo "ไม่พบข้อมูลผู้ใช้";
                                            }

                                            // ปิด statement
                                            $q_members->close();
                                        } else {
                                            // ถ้าไม่มี session ให้ทำการล็อกอินก่อน
                                            echo "";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if (isset($_SESSION['memberid'])) { ?>
                                    <div class="widget-content-left">
                                        <div class="btn-group">
                                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                class="p-0 btn">
                                                <i class="fa fa-angle-down ml-2 opacity-8" style="color: white;"></i>
                                            </a>
                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                class="dropdown-menu dropdown-menu-right">
                                                <a href="adminprofile.php" type="button" tabindex="0"
                                                    class="dropdown-item">โปรไฟล์</a>
                                                <div tabindex="-1" class="dropdown-divider"></div>
                                                <a href="logout.php" type="button" tabindex="0"
                                                    class="dropdown-item">ออกจากระบบ</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <div class="logo-src"></div>
                    <div class="header__pane ml-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__menu">
                    <span>
                        <button type="button"
                            class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
                <div class="scrollbar-sidebar" style="background-color: #DDA0DD">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading" style="color: white;">Dashboards</li>
                            <li>
                                <a href="admin.php">
                                    <i class="metismenu-icon pe-7s-rocket"></i>
                                    หน้าหลัก
                                </a>
                            </li>

                            <li class="app-sidebar__heading" style="color: white;">นักศึกษา</li>
                            <li>
                                <a href="admindorm_women.php">
                                    <i class="metismenu-icon pe-7s-display2"></i>
                                    หอนักศึกษาลีลาวดี (หอพักหญิง)
                                </a>
                            </li>
                            <li>
                                <a href="admindorm_men.php" class="mm-active">
                                    <i class="metismenu-icon pe-7s-display2"></i>
                                    ตึก 10 ชั้น (หอพักชาย)
                                </a>
                            </li>
                            <li>
                                <a href="admin_historicalwomen.php">
                                    <i class="metismenu-icon pe-7s-display2"></i>
                                    ตารางย้อนหลัง (หอพักหญิง)
                                </a>
                            </li>
                            <li>
                                <a href="admin_historicalmen.php">
                                    <i class="metismenu-icon pe-7s-display2"></i>
                                    ตารางย้อนหลัง (หอพักชาย)
                                </a>
                            </li>

                            <li class="app-sidebar__heading" style="color: white;">ข้อมูลเว็บไซต์</li>
                            <li>
                                <a href="admin_sitesetting.php">
                                    <i class="metismenu-icon pe-7s-display2"></i>
                                    แก้ไขข้อมูลเว็บไซต์
                                </a>
                                <a href="adminroomsettings.php">
                                    <i class="metismenu-icon pe-7s-display2"></i>
                                    แก้ไขข้อมูลห้องพัก
                                </a>
                                <a href="adminprofile.php">
                                    <i class="metismenu-icon pe-7s-display2"></i>
                                    โปรไฟล์
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <h4 style="background-color: #800080; color: white; padding: 10px; display: inline-block; border-radius: 10px;">
                            <?php echo $d_m['name'] ?>
                        </h4> <br>
                        <h5 style="background-color: #800080; color: white; padding: 10px; display: inline-block; border-radius: 10px;">
                            ปีการศึกษา :
                            <?php echo $d_y['years'] ?>
                            เทอม :
                            <?php echo $d_y['term'] ?>
                        </h5>
                    </div>

                    <button id="exportButton" class="mb-2 mr-2 btn btn-primary">ออกรายงาน</button>

                    <script>
                        document.getElementById("exportButton").addEventListener("click", function () {
                            window.location.href = 'export_excel_men.php';
                        });
                    </script>

                    <style>
                        .room-table {
                            border-collapse: collapse;
                            width: 100%;
                            margin-bottom: 20px;
                            background-color: #ffffff;
                        }

                        .room-table th,
                        .room-table td {
                            border: 1px solid #000000;
                            /* เปลี่ยนเส้นขอบเป็น 1px */
                            text-align: left;
                            padding: 8px;
                        }

                        .room-table th {
                            background-color: #f2f2f2;
                        }

                        .room-table tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }

                        .room-table+.room-table {
                            margin-top: 20px;
                        }
                    </style>

                    <?php
                    // ดึงข้อมูลห้องพักทั้งหมดที่เป็นชาย
                    $room_data = $conn->query("SELECT * FROM room WHERE gender = 1");

                    while ($room_row = $room_data->fetch_assoc()) {
                        // ดึงข้อมูลผู้เข้าพักในห้องนั้นๆ ที่มี status เป็น 1
                        $reservation_data = $conn->query("SELECT t.*, m.studentid, m.name AS student_name, m.surname AS student_surname, m.course AS student_course, m.province, m.phone
                            FROM transaction t 
                            JOIN member m ON t.stdid = m.memberid 
                            JOIN room r ON t.roomid = r.roomid
                            JOIN conflict_years cy ON t.years = cy.years AND t.term = cy.term
                            WHERE t.roomid = {$room_row['roomid']} AND t.status = 1
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
                                    <th style="width: 10%; border: 1px solid #000000;">ย้ายห้อง</th>
                                    <th style="width: 10%; border: 1px solid #000000;">ย้ายออก</th>
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
                                    <td style="border: 1px solid #000000;"><button class="mb-2 mr-2 btn btn-primary move-room-btn" data-roomcode="' . $room_row['roomcode'] . '" data-reservation-id="' . $reservation_row['transid'] . '" tabindex="0">ย้ายห้อง</button></td>
                                    <td style="border: 1px solid #000000;"><button class="mb-2 mr-2 btn btn-danger checkout-btn" data-reservation-id="' . $reservation_row['transid'] . '">ย้ายออก</button></td>                                                                
                                </tr>';
                            $count++;
                        }
                        echo '</tbody>';
                        echo '</table>';
                    }
                    ?>

                    <?php
                    // ดึงข้อมูลห้องที่ว่างทั้งหมด
                    $available_room_data = $conn->query("SELECT * FROM room WHERE gender = 1");

                    // เช็คว่ามีห้องว่างหรือไม่
                    if ($available_room_data->num_rows > 0) {
                        // เก็บข้อมูลห้องที่ว่างในรูปแบบของ options tag สำหรับ dropdown list
                        $options = '<select class="dropdown">';
                        while ($row = $available_room_data->fetch_assoc()) {
                            // เช็คจำนวนผู้เข้าพักในห้องนั้น
                            $currentOccupancy = $conn->query("SELECT COUNT(*) AS occupancy FROM transaction WHERE roomid = " . $row['roomid']);
                            $currentOccupancy = $currentOccupancy->fetch_assoc()['occupancy'];

                            // จำนวนผู้เข้าพักที่ห้องนี้รองรับ
                            $targetRoomHeadcount = $row['headcount'];

                            // เพิ่มเงื่อนไขเช็คว่าห้องมีพื้นที่ว่างเพียงพอหรือไม่
                            if ($currentOccupancy < $targetRoomHeadcount) {
                                $options .= '<option value="' . $row["roomcode"] . '">' . $row["roomcode"] . '</option>';
                            }
                        }
                        $options .= '</select>';
                    } else {
                        $options = '<p>ไม่พบห้องที่ว่าง</p>';
                    }
                    ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var roomRows = document.querySelectorAll(".room-table tbody tr");
                            roomRows.forEach(function (row) {
                                var moveRoomBtn = row.querySelector(".move-room-btn");
                                moveRoomBtn.addEventListener("click", function () {
                                    var sourceRoomCode = moveRoomBtn.getAttribute("data-roomcode");
                                    var reservationId = moveRoomBtn.getAttribute("data-reservation-id");

                                    // เพิ่ม event listener เพื่อตรวจสอบค่าของ dropdown ทุกครั้งที่มีการเปลี่ยนแปลง
                                    var targetRoomSelect = document.createElement("div");
                                    targetRoomSelect.classList.add("dropdown-container");
                                    targetRoomSelect.innerHTML = '<?php echo $options; ?>';
                                    var targetRoomCodeDropdown = targetRoomSelect.querySelector(".dropdown");

                                    // เพิ่มปุ่มยืนยัน
                                    var confirmButton = document.createElement("button");
                                    confirmButton.textContent = "ยืนยัน";
                                    confirmButton.classList.add("mb-2", "mr-2", "btn", "btn-success");
                                    confirmButton.addEventListener("click", function () {
                                        var targetRoomCode = targetRoomCodeDropdown.value;
                                        if (targetRoomCode !== null && targetRoomCode !== "") {
                                            // เพิ่มเงื่อนไขตรวจสอบว่าห้องมีพื้นที่ว่างเพียงพอสำหรับย้ายผู้เข้าพักเข้าไปหรือไม่
                                            var targetRoomHeadcount = parseInt(targetRoomCodeDropdown.selectedOptions[0].dataset.headcount);
                                            var currentOccupancy = row.parentNode.querySelectorAll("td[data-roomcode='" + targetRoomCode + "']").length;
                                            if (currentOccupancy >= targetRoomHeadcount) {
                                                alert("ห้องหมายเลข " + targetRoomCode + " เต็มแล้ว กรุณาเลือกห้องอื่น");
                                                return;
                                            }

                                            if (confirm("ยืนยันการย้ายห้องจากห้องหมายเลข " + sourceRoomCode + " ไปยังห้องหมายเลข " + targetRoomCode + " หรือไม่?")) {
                                                var formData = new FormData();
                                                formData.append('source_room_code', sourceRoomCode);
                                                formData.append('reservation_id', reservationId);
                                                formData.append('target_room_code', targetRoomCode);
                                                fetch('move_room.php', {
                                                    method: 'POST',
                                                    body: formData
                                                })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            alert("การย้ายห้องเรียบร้อยแล้ว");
                                                            setTimeout(function () {
                                                                location.reload(); // รีโหลดหน้าเว็บ
                                                            }, 1000); // รอ 1 วินาทีก่อนที่จะรีโหลดหน้า
                                                        } else {
                                                            alert("เกิดข้อผิดพลาดในการย้ายห้อง");
                                                            console.error('เกิดข้อผิดพลาดในการย้ายห้อง:', data.error); // แสดงข้อผิดพลาดใน console
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('เกิดข้อผิดพลาด:', error);
                                                        alert("เกิดข้อผิดพลาดในการย้ายห้อง");
                                                    });
                                            }
                                        } else {
                                            alert("กรุณาเลือกห้องที่ต้องการย้าย");
                                        }
                                    });

                                    // เพิ่ม dropdown และปุ่มยืนยันลงในตาราง หลังจากปุ่ม "ย้ายห้อง"
                                    var cell = moveRoomBtn.parentElement;
                                    cell.appendChild(targetRoomSelect);
                                    cell.appendChild(confirmButton);
                                });
                            });
                        });
                    </script>

                    <?php
                    // ตรวจสอบว่ามีการย้ายออกห้องหรือไม่
                    
                    $room_data = $conn->query("SELECT * FROM room WHERE gender = 1");


                    $checkout_data = $conn->query("SELECT t.*, m.studentid, m.name AS student_name, m.surname AS student_surname, m.course AS student_course, m.province, m.phone
                            FROM transaction t 
                            JOIN member m ON t.stdid = m.memberid 
                            JOIN room r ON t.roomid = r.roomid
                            JOIN conflict_years cy ON t.years = cy.years AND t.term = cy.term
                            WHERE t.status = 0 AND (r.roomid = 0 OR r.gender = 1)
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
                    ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var checkoutButtons = document.querySelectorAll(".checkout-btn");
                            checkoutButtons.forEach(function (button) {
                                button.addEventListener("click", function () {
                                    // ข้อความยืนยัน
                                    var confirmationMessage = "ยืนยันการย้ายออก?";
                                    if (confirm(confirmationMessage)) {
                                        var reservationId = button.getAttribute("data-reservation-id");
                                        var currentDate = new Date().toISOString(); // ดึงวันที่และเวลาปัจจุบันในรูปแบบ ISO

                                        // ส่งคำขอ HTTP POST ไปยังไฟล์ moveout_room.php เพื่ออัปเดตค่า status และวันเวลา
                                        fetch('moveout_room.php', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            body: 'reservation_id=' + reservationId + '&current_date=' + currentDate,
                                        })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    alert("การย้ายออกเรียบร้อยแล้ว");
                                                    location.reload(); // รีโหลดหน้าเว็บ
                                                } else {
                                                    alert("เกิดข้อผิดพลาดในการย้ายออก");
                                                    console.error('เกิดข้อผิดพลาดในการย้ายออก:', data.error);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('เกิดข้อผิดพลาดในการส่งคำขอ:', error);
                                                alert("เกิดข้อผิดพลาดในการย้ายออก");
                                            });
                                    }
                                });
                            });
                        });
                    </script>

                    <!-- <div class="app-wrapper-footer">
                        <div class="app-footer">
                            <div class="app-footer__inner">
                                <div class="app-footer-left">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                Footer Link 1
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                Footer Link 2
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="app-footer-right">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                Footer Link 3
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <div class="badge badge-success mr-1 ml-0">
                                                    <small>NEW</small>
                                                </div>
                                                Footer Link 4
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
            </div>
        </div>
        <script type="text/javascript" src="./assets/scripts/main.js"></script>
</body>

</html>