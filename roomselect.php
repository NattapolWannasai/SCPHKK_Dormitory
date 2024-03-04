<!doctype html>
<html lang="en">
<?php require_once "admin/connect.php" ?>
<?php session_start(); ?>

<?php
// เช็คว่ามีการล็อกอินอยู่หรือไม่
if (!isset($_SESSION['memberid'])) {
    // ถ้าไม่ได้ล็อกอิน ให้ redirect กลับไปที่หน้า login.php
    header('Location: index.php');
    exit; // หยุดการทำงานของ script ต่อ
}

// เช็คว่าสถานะเป็น 1 หรือไม่
if ($_SESSION['status'] != '0') {
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
    <title>หอพักวิทยาลัยการสาธารณสุขสิรินธร จังหวัดขอนแก่น</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

<?php if ($_SESSION['sdtSex'] == 'M') {
    $room = $conn->query("SELECT * FROM dormitory.room INNER JOIN roomtypename ON roomtypenameid = roomtype WHERE gender = 1 AND floor = $_REQUEST[floor] order by roomcode");
} else {
    $room = $conn->query("SELECT * FROM dormitory.room INNER JOIN roomtypename ON roomtypenameid = roomtype WHERE gender = 2 AND floor = $_REQUEST[floor] order by roomcode");
} ?>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow" style="background-color: #800080">
            <div class="app-header__logo" style="font-size: 0.9rem; color: snow;">
                <img src="assets/images/logo-inverse.png" alt="Logo" width="45" height="52"
                    style="vertical-align: middle;">
                <a href="index.php" style="vertical-align: middle; font-size: 0.78rem; color: white;">&nbsp;
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
                                                <a href="profile.php" type="button" tabindex="0"
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
                            <li class="app-sidebar__heading" style="color: white;">หอพัก</li>
                            <li>
                                <a href="index.php">
                                    <i class="metismenu-icon pe-7s-home"></i>
                                    หน้าหลัก
                                </a>
                                <a href="detail.php">
                                    <i class="metismenu-icon pe-7s-info"></i>
                                    ข้อมูลหอพัก
                                </a>
                                <a href="contract.php">
                                    <i class="metismenu-icon pe-7s-call"></i>
                                    ติดต่อ
                                </a>
                            </li>


                            <?php if (isset($_SESSION['memberid'])) { ?>
                                <li class="app-sidebar__heading" style="color: white;">นักศึกษา</li>
                                <li>
                                    <a href="profile.php">
                                        <i class="metismenu-icon pe-7s-id"></i>
                                        โปรไฟล์
                                    </a>
                                </li>
                                <li>
                                    <a href="dormitory.php" id="toggleMenu">
                                        <i class="metismenu-icon pe-7s-date"></i>
                                        จองห้องพัก
                                    </a>
                                </li>
                            <?php } ?>

                            <br>
                            <?php if (!isset($_SESSION['memberid'])) { ?>
                                <div>
                                    <a href="login.php" class="mb-2 mr-2 btn btn-success btn-lg btn-block">เข้าสู่ระบบ</a>
                                </div>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="app-main__outer">
                <div class="app-main__inner">
                        <h4 style="background-color: #800080; color: white; padding: 10px; display: inline-block; border-radius: 10px;">เลือกห้องพัก</h4>
                    <div class="main-card mb-3 card align-items-center">
                        <div class="card-body" style="display: flex; flex-wrap: wrap;">
                            <?php
                            if ($room->num_rows > 0) {
                                $room->data_seek(0); // เริ่มต้นใหม่จาก record แรก
                                while ($row = $room->fetch_assoc()) {
                                    // ตรวจสอบค่า ButtonStatusRoom ว่าเป็น 1 หรือไม่
                                    if ($row['ButtonStatusRoom'] == 1) {
                                        // ตรวจสอบจำนวนการจองที่มี status = 1 ในห้อง
                                        $query = "SELECT COUNT(*) as count FROM transaction 
                                                WHERE roomid = {$row['roomid']} 
                                                AND status = 1 
                                                AND years IN (SELECT years FROM conflict_years) 
                                                AND term IN (SELECT term FROM conflict_years)";
                                        $reservation_count = $conn->query($query)->fetch_assoc()['count'];

                                        // ตรวจสอบว่าห้องมีการจองครบหรือไม่
                                        if ($reservation_count >= $row['headcount']) {
                                            // ถ้าครบแล้วให้ปุ่มเป็นสีเทาและไม่สามารถคลิกได้
                                            echo '<button type="button" class="btn mr-2 mb-2 btn-secondary disabled btn-block" data-toggle="modal" data-target=".bd-example-modal-lg-' . $row['roomcode'] . '">
                                                    <h3>ห้องที่ ' . $row['roomcode'] . '</h3> 
                                                    <h4>' . $row['name'] . '</h4>
                                                    <h4>รองรับ ' . $row['headcount'] . ' คน</h4> 
                                                    <h4>ราคา ' . $row['price'] . '/เดือน</h4>
                                                    <h3>จำนวนคนที่ว่างจองได้: ' . ($row['headcount'] - $reservation_count) . '</h3></button>';
                                        } else {
                                            // ถ้ายังไม่ครบให้ปุ่มเป็นสีเขียวและสามารถคลิกได้
                                            echo '<button type="button" class="btn mr-2 mb-2 btn-primary btn-block" data-toggle="modal" data-target=".bd-example-modal-lg-' . $row['roomcode'] . '">
                                                    <h3>ห้องที่ ' . $row['roomcode'] . '</h3> 
                                                    <h4>' . $row['name'] . '</h4>
                                                    <h4>รองรับ ' . $row['headcount'] . ' คน</h4> 
                                                    <h4>ราคา ' . $row['price'] . '/เดือน</h4>
                                                    <h3>จำนวนคนที่ว่างจองได้: ' . ($row['headcount'] - $reservation_count) . '</h3></button>';
                                        }
                                    }
                                }
                            }
                            ?>
                            <script>
                                $(document).ready(function () {
                                    // ปิดการกระทำของปุ่มที่ถูกปิด
                                    $('.btn-secondary.disabled').click(function (e) {
                                        e.preventDefault(); // ป้องกันการทำงานเมื่อคลิก
                                        e.stopPropagation(); // หยุดการกระจายเหตุการณ์
                                        return false;
                                    });
                                });
                            </script>
                        </div>
                    </div>

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
<!-- modal -->
<?php
if ($room->num_rows > 0) {
    $room->data_seek(0); // เริ่มต้นใหม่จาก record แรก
    while ($row = $room->fetch_assoc()) {
        // ตรวจสอบจำนวนการจองในห้อง
        $reservation_count = $conn->query("SELECT COUNT(*) as count FROM transaction WHERE roomid = {$row['roomid']} 
                                           AND years = (SELECT years FROM conflict_years WHERE years = transaction.years) 
                                           AND term = (SELECT term FROM conflict_years WHERE term = transaction.term)")->fetch_assoc()['count'];

        echo '<div class="modal fade bd-example-modal-lg-' . $row['roomcode'] . '" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">รายชื่อห้องที่ ' . $row['roomcode'] . '</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <div class="modal-body">
                        <div class="card-body">';

        // ตรวจสอบว่ามีการจองห้องหรือไม่
        if ($reservation_count > 0) {
            // แสดงข้อมูลการจอง
            echo '<table class="mb-0 table">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>รหัสนักศึกษา</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th>หลักสูตร</th>
                                    </tr>
                                </thead>
                                <tbody>';

            // ดึงข้อมูลการจองพร้อมข้อมูลนักศึกษา
            $reservation_data = $conn->query("SELECT t.*, m.studentid, m.name AS student_name, m.surname AS student_surname, m.course AS student_course 
                FROM transaction t 
                JOIN member m ON t.stdid = m.memberid 
                WHERE t.roomid = {$row['roomid']} AND t.status = 1 
                AND t.years = (SELECT years FROM conflict_years WHERE years = t.years) 
                AND t.term = (SELECT term FROM conflict_years WHERE term = t.term)
                ORDER BY t.datecreate ASC"); // ASC เพื่อเรียงลำดับจากน้อยไปมาก (จากเร็วสุดไปช้าสุด)
            $count = 1;
            while ($reservation_row = $reservation_data->fetch_assoc()) {
                echo '<tr>
                        <th scope="row">' . $count . '</th>
                        <td>' . $reservation_row['studentid'] . '</td>
                        <td>' . $reservation_row['student_name'] . '</td>
                        <td>' . $reservation_row['student_surname'] . '</td>
                        <td>' . $reservation_row['student_course'] . '</td>
                    </tr>';
                $count++;
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            // แสดงข้อความหากยังไม่มีการจอง
            echo '<p>ยังไม่มีผู้เข้าพักในห้องนี้</p>';
        }

        // ตรวจสอบจำนวนการจองในห้อง
        echo '<div class="modal-footer">';
        if ($reservation_count >= $row['headcount']) {
            echo '<button type="button" class="btn btn-primary" disabled>จอง (เต็ม)</button>';
        } else {
            echo '<form action="reserve.php" method="post">
                                <input type="hidden" name="selected_room_code" value="' . $row['roomcode'] . '">
                                <!-- Other form inputs -->
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">ปิด</button>
                                <button class="btn btn-primary" type="submit" name="reserve_button">จอง</button>
                                </form>';
        }

        echo '</div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>';
    }
}
?>