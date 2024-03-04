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
$q_w = $conn->query("SELECT * FROM `dorm` WHERE `dormid`= 5");
$d_w = $q_w->fetch_assoc();
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
                                <a href="admindorm_men.php">
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
                                <a href="adminroomsettings.php" class="mm-active">
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
                        <h4 style="background-color: #800080; color: white; padding: 10px; display: inline-block; border-radius: 10px;">แก้ไขข้อมูลห้องพัก</h4>

                        <style>
                            .button-container button {
                                margin: 5px;
                            }
                        </style>

                        <div class="card col-lg-12">
                            <div class="card-body">
                                <h5>ชั้น
                                    <?php echo $d_m['name'] ?>
                                </h5>
                                <div class="button-container">
                                    <?php
                                    // เชื่อมต่อกับฐานข้อมูล
                                    $conn = mysqli_connect("localhost", "root", "", "dormitory");

                                    // ตรวจสอบการเชื่อมต่อ
                                    if ($conn === false) {
                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                    }

                                    // คำสั่ง SQL สำหรับดึงข้อมูล ButtonStatusFloor ทั้งหมดจากตาราง room
                                    $sql = "SELECT ButtonStatusFloor, floor FROM room WHERE dormid = 4";

                                    // ประมวลผลคำสั่ง SQL
                                    $result = mysqli_query($conn, $sql);

                                    // ตรวจสอบว่ามีข้อมูลในการทำงานหรือไม่
                                    if (mysqli_num_rows($result) > 0) {
                                        // สร้างตัวแปรเก็บข้อมูลของชั้นที่แสดงไปแล้ว
                                        $printedFloors = array();

                                        // วนลูปเพื่อสร้างปุ่มตามจำนวนชั้นที่มีอยู่ในฐานข้อมูล
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $floor = $row['floor'];
                                            $buttonStatusFloor = $row['ButtonStatusFloor'];

                                            // ตรวจสอบว่าชั้นนี้ได้แสดงปุ่มไปแล้วหรือยัง
                                            if (!in_array($floor, $printedFloors)) {
                                                // เพิ่มชั้นนี้เข้าไปในรายการชั้นที่แสดงไปแล้ว
                                                $printedFloors[] = $floor;

                                                // ตรวจสอบค่า ButtonStatusFloor และสร้างปุ่มตามชั้นที่
                                                if ($buttonStatusFloor == 1) {
                                                    echo "<button class='btn btn-primary' onclick='toggleButtonFloorMen(0, $floor)'>ชั้นที่ $floor เปิด</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger' onclick='toggleButtonFloorMen(1, $floor)'>ชั้นที่ $floor ปิด</button>";
                                                }
                                            }
                                        }
                                    } else {
                                        // ถ้าไม่มีข้อมูลในฐานข้อมูล
                                        echo "No data found";
                                    }

                                    // ปิดการเชื่อมต่อ
                                    mysqli_close($conn);
                                    ?>
                                </div>

                                <script>
                                    function toggleButtonFloorMen(newStatus, floor) {
                                        // ส่งค่า ButtonStatusFloor และ floor ใหม่ไปยังไฟล์ PHP ด้วย XMLHttpRequest
                                        var xhr = new XMLHttpRequest();
                                        xhr.open("GET", "updatefloor_men.php?newStatus=" + newStatus + "&floor=" + floor, true);
                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState == 4 && xhr.status == 200) {
                                                // เมื่อคำขอสำเร็จ รีโหลดหน้าเว็บ
                                                location.reload();
                                            }
                                        };
                                        xhr.send();

                                        // ปิดการใช้งานปุ่ม
                                        event.target.disabled = true;
                                    }
                                </script>
                                <br>

                                <h5>ห้อง
                                    <?php echo $d_m['name'] ?>
                                </h5>
                                <div class="button-container">
                                    <?php
                                    // เชื่อมต่อกับฐานข้อมูล
                                    $conn = mysqli_connect("localhost", "root", "", "dormitory");

                                    // ตรวจสอบการเชื่อมต่อ
                                    if ($conn === false) {
                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                    }

                                    // คำสั่ง SQL สำหรับดึงข้อมูล ButtonStatus ทั้งหมดจากตาราง room
                                    $sql = "SELECT ButtonStatusRoom, roomcode FROM room WHERE dormid = 4";

                                    // ประมวลผลคำสั่ง SQL
                                    $result = mysqli_query($conn, $sql);

                                    // ตรวจสอบว่ามีข้อมูลในการทำงานหรือไม่
                                    if (mysqli_num_rows($result) > 0) {
                                        // วนลูปเพื่อสร้างปุ่มตามจำนวนห้องที่มีอยู่ในฐานข้อมูล
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $roomcode = $row['roomcode'];
                                            $buttonStatusRoom = $row['ButtonStatusRoom']; // เปลี่ยน ButtonStatusRoom เป็น ButtonStatusRoom
                                    
                                            // ตรวจสอบค่า ButtonStatusRoom และสร้างปุ่มตามห้อง
                                            if ($buttonStatusRoom == 1) {
                                                echo "<button id='toggleButtonRoomMen_$roomcode' class='btn btn-primary' onclick='toggleButtonRoomMen(0, $roomcode)'>ห้อง $roomcode เปิด</button>";
                                            } else {
                                                echo "<button id='toggleButtonRoomMen_$roomcode' class='btn btn-danger' onclick='toggleButtonRoomMen(1, $roomcode)'>ห้อง $roomcode ปิด</button>";
                                            }
                                        }
                                    } else {
                                        // ถ้าไม่มีข้อมูลในฐานข้อมูล
                                        echo "No data found";
                                    }

                                    // ปิดการเชื่อมต่อ
                                    mysqli_close($conn);
                                    ?>

                                    <script>
                                        function toggleButtonRoomMen(newStatus, roomcode) {
                                            // ส่งค่า ButtonStatusRoom และ roomcode ใหม่ไปยังไฟล์ PHP ด้วย XMLHttpRequest
                                            var xhr = new XMLHttpRequest();
                                            xhr.open("GET", "updateroom_men.php?newStatus=" + newStatus + "&roomcode=" + roomcode, true); // เปลี่ยน updateroom_women.php เป็น updateroom_men.php
                                            xhr.onreadystatechange = function () {
                                                if (xhr.readyState == 4 && xhr.status == 200) {
                                                    // เมื่อคำขอสำเร็จ รีโหลดหน้าเว็บ
                                                    location.reload();
                                                }
                                            };
                                            xhr.send();

                                            // ปิดการใช้งานปุ่ม
                                            document.getElementById("toggleButtonRoomMen_" + roomcode).disabled = true;
                                        }
                                    </script>
                                </div>
                                <br>

                                <h5>แก้ไขข้อมูลห้องพัก :
                                    <?php echo $d_m['name'] ?>
                                </h5>
                                <div class="container">
                                    <?php
                                    // เชื่อมต่อกับฐานข้อมูล
                                    $conn = new mysqli("localhost", "root", "", "dormitory");

                                    // ตรวจสอบการเชื่อมต่อ
                                    if ($conn->connect_error) {
                                        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
                                    }

                                    // ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        // รับค่าจากฟอร์ม
                                        $roomcode = $conn->real_escape_string($_POST['roomcode']);
                                        $roomtype = $conn->real_escape_string($_POST['roomtype']);
                                        $headcount = $conn->real_escape_string($_POST['headcount']);
                                        $price = $conn->real_escape_string($_POST['price']);

                                        // คำสั่ง SQL สำหรับอัปเดตข้อมูล
                                        $sql = "UPDATE room SET roomtype='$roomtype', headcount='$headcount', price='$price' WHERE roomcode='$roomcode'";

                                        // ดำเนินการคำสั่ง SQL
                                        if (!$conn->query($sql)) {
                                            echo "Error updating record: " . $conn->error;
                                        }
                                    }
                                    ?>

                                    <table class="mb-0 table">
                                        <thead>
                                            <tr>
                                                <th>เลขที่ห้อง</th>
                                                <th>ประเภทห้องพัก(1=พัดลม,2=แอร์)</th>
                                                <th>จำนวนผู้เข้าพัก</th>
                                                <th>ราคา</th>
                                                <th>การดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // เตรียมคำสั่ง SQL
                                            $sql = "SELECT * FROM room WHERE dormid = 4";

                                            // ดำเนินการ execute คำสั่ง SQL
                                            $result = $conn->query($sql);

                                            // ตรวจสอบว่ามีข้อมูลที่สามารถดึงได้หรือไม่
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr><form action='' method='post'>";
                                                    echo "<td>" . $row['roomcode'] . "<input type='hidden' name='roomcode' value='" . $row['roomcode'] . "'></td>";
                                                    echo "<td><input type='text' name='roomtype' value='" . $row['roomtype'] . "'></td>";
                                                    echo "<td><input type='number' name='headcount' value='" . $row['headcount'] . "'></td>";
                                                    echo "<td><input type='text' name='price' value='" . $row['price'] . "'></td>";
                                                    echo "<td><button type='submit' onclick='showAlert(this)' class='mb-2 mr-2 btn btn-primary'>บันทึก</button></td>";
                                                    echo "</form></tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>ไม่พบข้อมูลห้องพัก</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <?php
                                    // ปิดการเชื่อมต่อฐานข้อมูล
                                    $conn->close();
                                    ?>

                                    <script>
                                        // ฟังก์ชันสำหรับแสดงข้อความแจ้งเตือนเมื่อคลิกปุ่มบันทึก
                                        function showAlert(button) {
                                            // หาค่า roomcode ในแถวของปุ่มที่ถูกคลิก
                                            var row = button.closest('tr');
                                            var roomcode = row.querySelector('input[name="roomcode"]').value;

                                            // แสดงข้อความแจ้งเตือน
                                            alert('บันทึกสำเร็จ ห้อง: ' + roomcode);
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="card col-lg-12">
                            <div class="card-body">
                                <h5>ชั้น
                                    <?php echo $d_w['name'] ?>
                                </h5>
                                <div class="button-container">
                                    <?php
                                    // เชื่อมต่อกับฐานข้อมูล
                                    $conn = mysqli_connect("localhost", "root", "", "dormitory");

                                    // ตรวจสอบการเชื่อมต่อ
                                    if ($conn === false) {
                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                    }

                                    // คำสั่ง SQL สำหรับดึงข้อมูล ButtonStatusFloor ทั้งหมดจากตาราง room ที่มี dormid เท่ากับ 5
                                    $sql = "SELECT ButtonStatusFloor, floor FROM room WHERE dormid = 5";

                                    // ประมวลผลคำสั่ง SQL
                                    $result = mysqli_query($conn, $sql);

                                    // ตรวจสอบว่ามีข้อมูลในการทำงานหรือไม่
                                    if (mysqli_num_rows($result) > 0) {
                                        // สร้างตัวแปรเก็บข้อมูลของชั้นที่แสดงไปแล้ว
                                        $printedFloors = array();

                                        // วนลูปเพื่อสร้างปุ่มตามจำนวนชั้นที่มีอยู่ในฐานข้อมูล
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $floor = $row['floor'];
                                            $buttonStatusFloor = $row['ButtonStatusFloor'];

                                            // ตรวจสอบว่าชั้นนี้ได้แสดงปุ่มไปแล้วหรือยัง
                                            if (!in_array($floor, $printedFloors)) {
                                                // เพิ่มชั้นนี้เข้าไปในรายการชั้นที่แสดงไปแล้ว
                                                $printedFloors[] = $floor;

                                                // ตรวจสอบค่า ButtonStatusFloor และสร้างปุ่มตามชั้นที่
                                                if ($buttonStatusFloor == 1) {
                                                    echo "<button class='btn btn-primary' onclick='toggleButtonFloorWomenMen(0, $floor)'>ชั้นที่ $floor เปิด</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger' onclick='toggleButtonFloorWomenMen(1, $floor)'>ชั้นที่ $floor ปิด</button>";
                                                }
                                            }
                                        }
                                    } else {
                                        // ถ้าไม่มีข้อมูลในฐานข้อมูล
                                        echo "No data found";
                                    }

                                    // ปิดการเชื่อมต่อ
                                    mysqli_close($conn);
                                    ?>

                                    <script>
                                        function toggleButtonFloorWomenMen(newStatus, floor) {
                                            // ส่งค่า ButtonStatusFloor และ floor ใหม่ไปยังไฟล์ PHP ด้วย XMLHttpRequest
                                            var xhr = new XMLHttpRequest();
                                            xhr.open("GET", "updatefloor_women.php?newStatus=" + newStatus + "&floor=" + floor, true);
                                            xhr.onreadystatechange = function () {
                                                if (xhr.readyState == 4 && xhr.status == 200) {
                                                    // เมื่อคำขอสำเร็จ รีโหลดหน้าเว็บ
                                                    location.reload();
                                                }
                                            };
                                            xhr.send();

                                            // ปิดการใช้งานปุ่ม
                                            event.target.disabled = true;
                                        }
                                    </script>
                                </div>
                                <br>

                                <h5>ห้อง
                                    <?php echo $d_w['name'] ?>
                                </h5>
                                <div class="button-container">
                                    <?php
                                    // เชื่อมต่อกับฐานข้อมูล
                                    $conn = mysqli_connect("localhost", "root", "", "dormitory");

                                    // ตรวจสอบการเชื่อมต่อ
                                    if ($conn === false) {
                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                    }

                                    // คำสั่ง SQL สำหรับดึงข้อมูล ButtonStatusRoom ทั้งหมดจากตาราง room
                                    $sql = "SELECT ButtonStatusRoom, roomcode FROM room WHERE dormid = 5";

                                    // ประมวลผลคำสั่ง SQL
                                    $result = mysqli_query($conn, $sql);

                                    // ตรวจสอบว่ามีข้อมูลในการทำงานหรือไม่
                                    if (mysqli_num_rows($result) > 0) {
                                        // วนลูปเพื่อสร้างปุ่มตามจำนวนห้องที่มีอยู่ในฐานข้อมูล
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $roomcode = $row['roomcode'];
                                            $buttonStatusRoom = $row['ButtonStatusRoom'];

                                            // ตรวจสอบค่า ButtonStatusRoom และสร้างปุ่มตามห้อง
                                            if ($buttonStatusRoom == 1) {
                                                echo "<button id='toggleButtonRoom' class='btn btn-primary' onclick='toggleButtonRoom(0, $roomcode)'>ห้อง $roomcode เปิด</button>";
                                            } else {
                                                echo "<button id='toggleButtonRoom' class='btn btn-danger' onclick='toggleButtonRoom(1, $roomcode)'>ห้อง $roomcode ปิด</button>";
                                            }
                                        }
                                    } else {
                                        // ถ้าไม่มีข้อมูลในฐานข้อมูล
                                        echo "No data found";
                                    }

                                    // ปิดการเชื่อมต่อ
                                    mysqli_close($conn);
                                    ?>

                                    <script>
                                        function toggleButtonRoom(newStatus, roomcode) {
                                            // ส่งค่า ButtonStatusRoom และ roomcode ใหม่ไปยังไฟล์ PHP ด้วย XMLHttpRequest
                                            var xhr = new XMLHttpRequest();
                                            xhr.open("GET", "updateroom_women.php?newStatus=" + newStatus + "&roomcode=" + roomcode, true);
                                            xhr.onreadystatechange = function () {
                                                if (xhr.readyState == 4 && xhr.status == 200) {
                                                    // เมื่อคำขอสำเร็จ รีโหลดหน้าเว็บ
                                                    location.reload();
                                                }
                                            };
                                            xhr.send();

                                            // ปิดการใช้งานปุ่ม
                                            event.target.disabled = true;
                                        }
                                    </script>
                                </div>

                                <br><br>

                                <h5>แก้ไขข้อมูลห้องพัก :
                                    <?php echo $d_w['name'] ?>
                                </h5>
                                <div class="container">
                                    <?php
                                    // เชื่อมต่อกับฐานข้อมูล
                                    $conn = new mysqli("localhost", "root", "", "dormitory");

                                    // ตรวจสอบการเชื่อมต่อ
                                    if ($conn->connect_error) {
                                        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
                                    }

                                    // ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        // รับค่าจากฟอร์ม
                                        $roomcode = $conn->real_escape_string($_POST['roomcode']);
                                        $roomtype = $conn->real_escape_string($_POST['roomtype']);
                                        $headcount = $conn->real_escape_string($_POST['headcount']);
                                        $price = $conn->real_escape_string($_POST['price']);

                                        // คำสั่ง SQL สำหรับอัปเดตข้อมูล
                                        $sql = "UPDATE room SET roomtype='$roomtype', headcount='$headcount', price='$price' WHERE roomcode='$roomcode'";

                                        // ดำเนินการคำสั่ง SQL
                                        if (!$conn->query($sql)) {
                                            echo "Error updating record: " . $conn->error;
                                        }
                                    }
                                    ?>

                                    <table class="mb-0 table">
                                        <thead>
                                            <tr>
                                                <th>เลขที่ห้อง</th>
                                                <th>ประเภทห้องพัก(1=พัดลม,2=แอร์)</th>
                                                <th>จำนวนผู้เข้าพัก</th>
                                                <th>ราคา</th>
                                                <th>การดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // เตรียมคำสั่ง SQL
                                            $sql = "SELECT * FROM room WHERE dormid = 5";

                                            // ดำเนินการ execute คำสั่ง SQL
                                            $result = $conn->query($sql);

                                            // ตรวจสอบว่ามีข้อมูลที่สามารถดึงได้หรือไม่
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr><form action='' method='post'>";
                                                    echo "<td>" . $row['roomcode'] . "<input type='hidden' name='roomcode' value='" . $row['roomcode'] . "'></td>";
                                                    echo "<td><input type='text' name='roomtype' value='" . $row['roomtype'] . "'></td>";
                                                    echo "<td><input type='number' name='headcount' value='" . $row['headcount'] . "'></td>";
                                                    echo "<td><input type='text' name='price' value='" . $row['price'] . "'></td>";
                                                    echo "<td><button type='submit' onclick='showAlert(this)' class='mb-2 mr-2 btn btn-primary'>บันทึก</button></td>";
                                                    echo "</form></tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>ไม่พบข้อมูลห้องพัก</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <?php
                                    // ปิดการเชื่อมต่อฐานข้อมูล
                                    $conn->close();
                                    ?>

                                    <script>
                                        // ฟังก์ชันสำหรับแสดงข้อความแจ้งเตือนเมื่อคลิกปุ่มบันทึก
                                        function showAlert(button) {
                                            // หาค่า roomcode ในแถวของปุ่มที่ถูกคลิก
                                            var row = button.closest('tr');
                                            var roomcode = row.querySelector('input[name="roomcode"]').value;

                                            // แสดงข้อความแจ้งเตือน
                                            alert('บันทึกสำเร็จ ห้อง: ' + roomcode);
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-lg-14">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5>แก้ไขข้อมูลห้องพัก :
                                        <?php echo $d_w['name'] ?>
                                    </h5>
                                    <?php
                                    // เชื่อมต่อกับฐานข้อมูล
                                    $conn = new mysqli("localhost", "root", "", "dormitory");

                                    // ตรวจสอบการเชื่อมต่อ
                                    if ($conn->connect_error) {
                                        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
                                    }

                                    // ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        // รับค่าจากฟอร์ม
                                        $roomcode = $conn->real_escape_string($_POST['roomcode']);
                                        $roomtype = $conn->real_escape_string($_POST['roomtype']);
                                        $headcount = $conn->real_escape_string($_POST['headcount']);
                                        $price = $conn->real_escape_string($_POST['price']);

                                        // คำสั่ง SQL สำหรับอัปเดตข้อมูล
                                        $sql = "UPDATE room SET roomtype='$roomtype', headcount='$headcount', price='$price' WHERE roomcode='$roomcode'";

                                        // ดำเนินการคำสั่ง SQL
                                        if (!$conn->query($sql)) {
                                            echo "Error updating record: " . $conn->error;
                                        }
                                    }
                                    ?>

                                    <table class="mb-0 table">
                                        <thead>
                                            <tr>
                                                <th>เลขที่ห้อง</th>
                                                <th>ประเภทห้องพัก</th>
                                                <th>จำนวนผู้เข้าพัก</th>
                                                <th>ราคา</th>
                                                <th>การดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // เตรียมคำสั่ง SQL
                                            $sql = "SELECT * FROM room WHERE dormid = 5";

                                            // ดำเนินการ execute คำสั่ง SQL
                                            $result = $conn->query($sql);

                                            // ตรวจสอบว่ามีข้อมูลที่สามารถดึงได้หรือไม่
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr><form action='' method='post'>";
                                                    echo "<td>" . $row['roomcode'] . "<input type='hidden' name='roomcode' value='" . $row['roomcode'] . "'></td>";
                                                    echo "<td><input type='text' name='roomtype' value='" . $row['roomtype'] . "'></td>";
                                                    echo "<td><input type='number' name='headcount' value='" . $row['headcount'] . "'></td>";
                                                    echo "<td><input type='text' name='price' value='" . $row['price'] . "'></td>";
                                                    echo "<td><button type='submit' onclick='showAlert(this)' class='mb-2 mr-2 btn btn-primary'>บันทึก</button></td>";
                                                    echo "</form></tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>ไม่พบข้อมูลห้องพัก</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <?php
                                    // ปิดการเชื่อมต่อฐานข้อมูล
                                    $conn->close();
                                    ?>

                                    <script>
                                        // ฟังก์ชันสำหรับแสดงข้อความแจ้งเตือนเมื่อคลิกปุ่มบันทึก
                                        function showAlert(button) {
                                            // หาค่า roomcode ในแถวของปุ่มที่ถูกคลิก
                                            var row = button.closest('tr');
                                            var roomcode = row.querySelector('input[name="roomcode"]').value;

                                            // แสดงข้อความแจ้งเตือน
                                            alert('บันทึกสำเร็จ ห้อง: ' + roomcode);
                                        }
                                    </script>
                                </div>
                            </div>
                        </div> -->

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