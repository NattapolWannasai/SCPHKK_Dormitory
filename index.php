<!doctype html>
<?php require_once "admin/connect.php" ?>
<?php session_start(); ?>
<html lang="en">

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
$q_news = $conn->query("SELECT * FROM `system_settings` WHERE `id` = 1");
$d_news = $q_news->fetch_assoc();
?>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow" style="background-color: #800080">
            <div class="app-header__logo" style="font-size: 0.9rem; color: snow;">
                <img src="assets/images/logo-inverse.png" alt="Logo" width="45" height="52"
                    style="vertical-align: middle;">
                <!-- <span style="vertical-align: middle; font-size: 0.78rem;">&nbsp; วิทยาลัยการสาธารณสุขสิรินธร<br>&nbsp; จังหวัดขอนแก่น</span> -->
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
                                <a href="index.php" class="mm-active">
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
                    <!-- <div class="app-page-title">
                        <h4><i class="fa fa-info-circle"></i> หน้าหลัก</h4>
                    </div> -->

                    <!-- รูปสไลด์ -->
                    <div class="col-md-14">
                        <div class="main-card mb-3 card">
                            <div class="card-body">

                                <div id="carouselExampleControls1" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img class="d-block w-100" src="assets/images/หอชาย.jpg" alt="First slide">
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="assets/images/หอหญิง.jpg"
                                                alt="Second slide">
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleControls1" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleControls1" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-14">
                        <div class="row">
                            <div class="col-lg-8 col-xs-12 col-sm-12">
                                <div class="col-md-14">
                                </div>
                                <div class="thumbnail">
                                    <div class="card-shadow-primary border mb-3 card card-body border-primary"
                                        class="tabbable-custom ">
                                        <?php echo $d_news['news']; ?>
                                    </div>
                                    
                                    <div class="card-shadow-primary border mb-3 card card-body border-primary"
                                        class="tabbable-custom ">
                                        <?php echo $d_news['schedule']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-xs-12 col-sm-12">
                                <div class="card-shadow-primary border mb-3 card card-body border-primary">
                                    <div class="portlet-title ">
                                        <div class="caption">
                                            <i class="jstree-icon jstree-ocl"></i>
                                            <h5>สถิติห้องว่าง ปีการศึกษา 2566/2</h5>
                                        </div>
                                    </div>

                                    <div class="portlet-body">
                                        <center>
                                            <h6 class="font-red"> หอพัก</h6>
                                        </center>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>หอพัก</th>
                                                    <th class="text-center"> ทั้งหมด (เตียง) </th>
                                                    <th class="text-center"> ว่าง (เตียง) </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>ชาย </td>
                                                    <td class="text-center">
                                                        <?php
                                                        // เชื่อมต่อฐานข้อมูล
                                                        $connection = mysqli_connect("localhost", "root", "", "dormitory");

                                                        // ตรวจสอบการเชื่อมต่อ
                                                        if ($connection === false) {
                                                            die("ERROR: Could not connect. " . mysqli_connect_error());
                                                        }

                                                        // คำสั่ง SQL เพื่อดึงจำนวน headcount ทั้งหมดที่ dormid = 4
                                                        $sql = "SELECT SUM(headcount) AS total_headcount FROM room WHERE dormid = 4";
                                                        $result = mysqli_query($connection, $sql);
                                                        $row = mysqli_fetch_assoc($result);

                                                        // ตรวจสอบว่ามีผลลัพธ์หรือไม่
                                                        if ($row) {
                                                            // กำหนดค่าให้กับตัวแปร $total_headcount
                                                            $total_headcount = $row['total_headcount'];

                                                            // แสดงจำนวน headcount ทั้งหมดที่ dormid = 4
                                                            echo $total_headcount;
                                                        } else {
                                                            // แสดงข้อความแจ้งเตือนว่าไม่พบข้อมูล
                                                            echo "ไม่พบข้อมูล";
                                                        }

                                                        // ปิดการเชื่อมต่อฐานข้อมูล
                                                        mysqli_close($connection);
                                                        ?>
                                                    </td>
                                                    <td class="text-center"> <b class="font-red">

                                                            <?php
                                                            // เชื่อมต่อฐานข้อมูล
                                                            $connection = mysqli_connect("localhost", "root", "", "dormitory");

                                                            // ตรวจสอบการเชื่อมต่อ
                                                            if ($connection === false) {
                                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                                            }

                                                            // คำสั่ง SQL เพื่อดึงจำนวน headcount ทั้งหมดที่ dormid = 5
                                                            $sql = "SELECT SUM(headcount) AS total_headcount FROM room WHERE dormid = 4";
                                                            $result = mysqli_query($connection, $sql);
                                                            $row = mysqli_fetch_assoc($result);

                                                            if ($row) {
                                                                // กำหนดค่าให้กับตัวแปร $total_headcount
                                                                $total_headcount = $row['total_headcount'];

                                                                // คำสั่ง SQL เพื่อนับ transaction ที่มี status = 1 และ roomid อยู่ระหว่าง 56 ถึง 71
                                                                $sql = "SELECT COUNT(*) AS status_count FROM transaction 
                                                                        JOIN conflict_years ON transaction.years = conflict_years.years AND transaction.term = conflict_years.term 
                                                                        WHERE transaction.status = 1 AND transaction.roomid BETWEEN 56 AND 71";
                                                                $result = mysqli_query($connection, $sql);
                                                                $row = mysqli_fetch_assoc($result);

                                                                if ($row) {
                                                                    // ลดค่า $total_headcount ตามจำนวน transaction ที่มี status = 1
                                                                    $total_headcount -= $row['status_count'];
                                                                }

                                                                // แสดงจำนวน headcount ทั้งหมดที่ปรับแล้ว
                                                                echo $total_headcount;
                                                            } else {
                                                                // แสดงข้อความแจ้งเตือนว่าไม่พบข้อมูล
                                                                echo "ไม่พบข้อมูล";
                                                            }

                                                            // ปิดการเชื่อมต่อฐานข้อมูล
                                                            mysqli_close($connection);
                                                            ?>

                                                        </b> </td>
                                                </tr>
                                                <tr>
                                                    <td>หญิง </td>
                                                    <td class="text-center">
                                                        <?php
                                                        // เชื่อมต่อฐานข้อมูล
                                                        $connection = mysqli_connect("localhost", "root", "", "dormitory");

                                                        // ตรวจสอบการเชื่อมต่อ
                                                        if ($connection === false) {
                                                            die("ERROR: Could not connect. " . mysqli_connect_error());
                                                        }

                                                        // คำสั่ง SQL เพื่อดึงจำนวน headcount ทั้งหมดที่ dormid = 5
                                                        $sql = "SELECT SUM(headcount) AS total_headcount FROM room WHERE dormid = 5";
                                                        $result = mysqli_query($connection, $sql);
                                                        $row = mysqli_fetch_assoc($result);

                                                        // ตรวจสอบว่ามีผลลัพธ์หรือไม่
                                                        if ($row) {
                                                            // กำหนดค่าให้กับตัวแปร $total_headcount
                                                            $total_headcount = $row['total_headcount'];

                                                            // แสดงจำนวน headcount ทั้งหมดที่ dormid = 5
                                                            echo $total_headcount;
                                                        } else {
                                                            // แสดงข้อความแจ้งเตือนว่าไม่พบข้อมูล
                                                            echo "ไม่พบข้อมูล";
                                                        }

                                                        // ปิดการเชื่อมต่อฐานข้อมูล
                                                        mysqli_close($connection);
                                                        ?>
                                                    </td>
                                                    <td class="text-center"> <b class="font-red">

                                                            <?php
                                                            // เชื่อมต่อฐานข้อมูล
                                                            $connection = mysqli_connect("localhost", "root", "", "dormitory");

                                                            // ตรวจสอบการเชื่อมต่อ
                                                            if ($connection === false) {
                                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                                            }

                                                            // คำสั่ง SQL เพื่อดึงจำนวน headcount ทั้งหมดที่ dormid = 5
                                                            $sql = "SELECT SUM(headcount) AS total_headcount FROM room WHERE dormid = 5";
                                                            $result = mysqli_query($connection, $sql);
                                                            $row = mysqli_fetch_assoc($result);

                                                            if ($row) {
                                                                // กำหนดค่าให้กับตัวแปร $total_headcount
                                                                $total_headcount = $row['total_headcount'];

                                                                // คำสั่ง SQL เพื่อนับ transaction ที่มี status = 1 และ roomid อยู่ระหว่าง 1 ถึง 55
                                                                $sql = "SELECT COUNT(*) AS status_count FROM transaction 
                                                                        JOIN conflict_years ON transaction.years = conflict_years.years AND transaction.term = conflict_years.term 
                                                                        WHERE transaction.status = 1 AND transaction.roomid BETWEEN 1 AND 55";
                                                                $result = mysqli_query($connection, $sql);
                                                                $row = mysqli_fetch_assoc($result);

                                                                if ($row) {
                                                                    // ลดค่า $total_headcount ตามจำนวน transaction ที่มี status = 1
                                                                    $total_headcount -= $row['status_count'];
                                                                }

                                                                // แสดงจำนวน headcount ทั้งหมดที่ปรับแล้ว
                                                                echo $total_headcount;
                                                            } else {
                                                                // แสดงข้อความแจ้งเตือนว่าไม่พบข้อมูล
                                                                echo "ไม่พบข้อมูล";
                                                            }

                                                            // ปิดการเชื่อมต่อฐานข้อมูล
                                                            mysqli_close($connection);
                                                            ?>

                                                        </b> </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <div class="pull-right">
                                            <a href="../home/dormlist.jsp" class="btn green">
                                                <i class="fa fa-share"></i> รายละเอียดเพิ่มเติม</a>
                                        </div>
                                        <div style="clear: both;">&nbsp;</div>
                                    </div>

                                </div>
                                <div
                                    class="portlet box green card-shadow-primary border mb-3 card card-body border-primary portlet-body">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <h5>อัตราค่าธรรมเนียมหอพัก</h5>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-left" width="170">
                                                        หอพัก
                                                    </th>
                                                    <th class="text-center">
                                                        เทอม 1
                                                    </th>
                                                    <th class="text-center">
                                                        เทอม 2
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-left">
                                                        หอพักหญิง
                                                    </td>
                                                    <td class="text-center">
                                                        800
                                                    </td>
                                                    <td class="text-center">
                                                        800
                                                    </td>

                                                </tr>
                                                <tr>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        หอพักชาย
                                                    </td>
                                                    <td class="text-center">
                                                        800
                                                    </td>
                                                    <td class="text-center">
                                                        800
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>
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

</html>