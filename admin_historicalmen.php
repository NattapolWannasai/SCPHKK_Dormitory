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
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<?php
$q_y = $conn->query("SELECT * FROM `conflict_years` WHERE `years` && `term`");
$d_y = $q_y->fetch_assoc();
?>

<?php
$q_m = $conn->query("SELECT * FROM `dorm` WHERE `dormid`= 4");
$d_m = $q_m->fetch_assoc();
?>
<?php
$q_w = $conn->query("SELECT * FROM `dorm` WHERE `dormid`= 5");
$d_w = $q_w->fetch_assoc();
?>
<?php
$women = $conn->query("SELECT * FROM dormitory.room WHERE gender = 2 group by floor order by floor");
?>
<?php
$men = $conn->query("SELECT * FROM dormitory.room WHERE gender = 1 group by floor order by floor");
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
                                <a href="admin_historicalmen.php" class="mm-active">
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
                        <h4 style="background-color: #800080; color: white; padding: 10px; display: inline-block; border-radius: 10px;">ตารางย้อนหลัง</h4>
                        <div>
                            <!-- Dropdown ปี -->
                            <select id="yearsDropdown" class="btn btn-warning dropdown-toggle">
                                <option value="">เลือกปี</option>
                                <!-- PHP เรียกข้อมูลปีจากฐานข้อมูลแล้วเติมเข้ามาใน option นี้ -->
                                <?php
                                $conn = new mysqli("localhost", "root", "", "dormitory");
                                $sql = "SELECT DISTINCT years FROM transaction";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['years'] . "'>" . $row['years'] . "</option>";
                                }
                                ?>
                            </select>

                            <!-- Dropdown เทอม -->
                            <select id="termDropdown" class="btn btn-success dropdown-toggle">
                                <option value="">เลือกเทอม</option>
                                <!-- PHP เรียกข้อมูลเทอมจากฐานข้อมูลแล้วเติมเข้ามาใน option นี้ -->
                                <?php
                                $sql = "SELECT DISTINCT term FROM transaction";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['term'] . "'>" . $row['term'] . "</option>";
                                }
                                ?>
                            </select>

                            <!-- ปุ่มตกลง -->
                            <button id="submitButton" class="btn btn-primary">ตกลง</button>
                        </div>

                        <div id="result">
                            <!-- ที่นี่จะแสดงผลลัพธ์หลังจากกดปุ่มตกลง -->

                        </div>

                        <script>
                            $(document).ready(function () {
                                // เมื่อคลิกที่ปุ่มตกลง
                                $("#submitButton").click(function () {
                                    // ดึงค่าที่เลือกจาก dropdown ปี และ dropdown เทอม
                                    var selectedYear = $("#yearsDropdown").val();
                                    var selectedTerm = $("#termDropdown").val();

                                    // ทำการโพสต์ข้อมูลไปยังไฟล์ PHP เพื่อประมวลผล
                                    $.post("process_selection_men.php", {
                                        year: selectedYear,
                                        term: selectedTerm
                                    },
                                        function (data, status) {
                                            // แสดงผลลัพธ์ที่ได้ใน div ที่มี id เป็น "result"
                                            $("#result").html(data);
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
                        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
                    </div>
                </div>
                <script type="text/javascript" src="./assets/scripts/main.js"></script>
</body>

</html>