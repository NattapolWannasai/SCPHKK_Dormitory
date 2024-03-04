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
                                    <a href="dormitory.php" id="toggleMenu" class="mm-active">
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
                    <div class="app-page-title">
                        <h4 style="background-color: #800080; color: white; padding: 10px; display: inline-block; border-radius: 10px;">เลือกชั้น</h4>

                        <style>
                            .button-container a {
                                display: inline-block;
                                margin: 5px 5px 5px 0;
                            }

                            .button-container a:first-child {
                                margin-left: 0;
                            }
                        </style>

                        <div class="row">
                            <div class="col-lg-9 col-md-12">
                                <?php if ($_SESSION['sdtSex'] == 'M') { ?>
                                    <div class="card mt-0">
                                        <div class="row no-gutters">
                                            <div class="col-lg-7 col-md-12">
                                                <div id="carousel" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <div class="carousel-item">
                                                            <img src="assets/images/หอพัก.jpg" class="d-block w-100"
                                                                alt="...">
                                                        </div>
                                                        <div class="carousel-item active">
                                                            <img src="assets/images/หอพัก.jpg" class="d-block w-100"
                                                                alt="...">
                                                        </div>
                                                    </div>
                                                    <div class="controls-bottom">
                                                        <a class="carousel-control-prev" href="#carousel" role="button"
                                                            data-slide="prev">
                                                            <span class="carousel-control" aria-hidden="true"><i
                                                                    class="fas fa-chevron-left text-white"
                                                                    aria-hidden="true"></i></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel" role="button"
                                                            data-slide="next">
                                                            <span class="carousel-control" aria-hidden="true"><i
                                                                    class="fas fa-chevron-right"
                                                                    aria-hidden="true"></i></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-5 col-md-12">
                                                <div class="card-body">
                                                    <h3 class="card-title">
                                                        <h3>
                                                            <?php echo $d_m['name'] ?>
                                                        </h3>
                                                        <p class="text-dark">ราคา <span>800 บาท</span>/เดือน
                                                            (รวมค่าน้ำ,ค่าไฟ) </p>

                                                        <div class="d-inline">
                                                            <!-- <button type="button" class="btn mr-2 mb-2 btn-primary"
                                                                data-toggle="modal"
                                                                data-target=".bd-example-modal-lg">ข้อมูลห้องพัก</button><br> -->
                                                            <div class="button-container">
                                                                <?php
                                                                if ($men->num_rows > 0) {
                                                                    while ($row = $men->fetch_assoc()) {
                                                                        if ($row['ButtonStatusFloor'] == 1) {
                                                                            echo '<a href="roomselect.php?floor=' . $row['floor'] . '" class="btn btn-info"></i> ชั้นที่ ' . $row['floor'] . '</a>';
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } else { ?>
                                    <div class="card">
                                        <div class="row no-gutters">
                                            <div class="col-lg-7 col-md-12">
                                                <div id="carousel2" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <div class="carousel-item active">
                                                            <img src="assets/images/หอพัก.jpg" class="d-block w-100"
                                                                alt="...">
                                                        </div>
                                                        <div class="carousel-item">
                                                            <img src="assets/images/หอพัก.jpg" class="d-block w-100"
                                                                alt="...">
                                                        </div>
                                                    </div>
                                                    <div class="controls-bottom">
                                                        <a class="carousel-control-prev" href="#carousel2" role="button"
                                                            data-slide="prev">
                                                            <span class="carousel-control" aria-hidden="true"><i
                                                                    class="fas fa-chevron-left text-white"
                                                                    aria-hidden="true"></i></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel2" role="button"
                                                            data-slide="next">
                                                            <span class="carousel-control" aria-hidden="true"><i
                                                                    class="fas fa-chevron-right"
                                                                    aria-hidden="true"></i></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-12">
                                                <div class="card-body">
                                                    <h1 class="card-title">
                                                        <h3>
                                                            <?php echo $d_w['name'] ?>
                                                        </h3>
                                                    </h1>
                                                    <p class="text-dark">ราคา <span>1,200 บาท (แอร์)</span>/เดือน
                                                        (รวมค่าน้ำ,ค่าไฟ)
                                                        <br> ราคา <span>800 บาท (พัดลม)</span>/เดือน (รวมค่าน้ำ,ค่าไฟ)
                                                    </p>
                                                    <div class="d-inline">
                                                        <!-- <button type="button" class="btn mr-2 mb-2 btn-primary"
                                                            data-toggle="modal"
                                                            data-target=".bd-example-modal-lg">ข้อมูลห้องพัก</button><br> -->
                                                        <div class="button-container">
                                                            <?php
                                                            if ($women->num_rows > 0) {
                                                                while ($row = $women->fetch_assoc()) {
                                                                    if ($row['ButtonStatusFloor'] == 1) {
                                                                        echo '<a href="roomselect.php?floor=' . $row['floor'] . '" class="btn btn-info"></i> ชั้นที่ ' . $row['floor'] . '</a>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
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

<!-- modal -->
<!-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">รายละเอียดเพิ่มเติม</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <li> เตียงนอน ขนาด 3.5 ฟุต พร้อมที่นอน คนละ 1 ชุด</li>
                <li> ตู้เสื้อผ้า คนละ 1 ชุด</li>
                <li> พัดลมเพดาน </li>
                <li> โต๊ะเขียนหนังสือพร้อมเก้าอี้ คนละ 1 ชุด
                <li> ติดมิเตอร์ภายในห้องพักห้องละ 1 ชุด
                </li>นิสิตสามารถนำอุปกรณ์เครื่องใช้ไฟฟ้ามาใช้ในหอพักได้ </li>
                </p>
            </div>
        </div>
    </div>
</div> -->