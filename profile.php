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
                                    <a href="profile.php" class="mm-active">
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
                    <div class="app-page-title">
                        <h4 style="background-color: #800080; color: white; padding: 10px; display: inline-block; border-radius: 10px;">โปรไฟล์</h4>
                        <div class="col-lg-14">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5>ข้อมูลส่วนตัว</h5>
                                    <table class="mb-0 table">
                                        <thead>
                                            <tr>
                                                <th>รหัสนักศึกษา</th>
                                                <th>ชื่อ</th>
                                                <th>นามสกุล</th>
                                                <th>หลักสูตร</th>
                                                <th>จังหวัด</th>
                                                <th>เบอร์โทร</th>
                                                <th>username</th>
                                                <th>password</th>
                                                <!-- <th></th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <?php echo $d_members['studentid']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $d_members['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $d_members['surname']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $d_members['course']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $d_members['province']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $d_members['phone']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $d_members['username']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $d_members['password']; ?>
                                                </td>
                                                <!-- <td><button type="button" class="mb-2 mr-2 btn btn-primary"
                                                    data-toggle="modal"
                                                    data-target=".bd-example-modal-lg">แก้ไขข้อมูล</button></td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-14">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5>ห้องพักของฉัน</h5>
                                    <table class="mb-0 table">
                                        <thead>
                                            <tr>
                                                <th>เลขที่ห้อง</th>
                                                <th>รหัสนักศึกษา</th>
                                                <th>ชื่อ</th>
                                                <th>สกุล</th>
                                                <th>หลักสูตร</th>
                                                <th>จังหวัด</th>
                                                <th>ปีการศึกษา(ที่เข้าพัก)</th>
                                                <th>เทอม</th>
                                                <th>วันที่และเวลาที่จอง </th>
                                                <th>วันที่ย้ายออก</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // ตรวจสอบว่ามีการล็อกอินหรือไม่
                                            if (isset($_SESSION['memberid'])) {
                                                $memberid = $_SESSION['memberid'];

                                                // ดึงข้อมูลการจองห้องพักของสมาชิกที่ล็อกอินอยู่จากตาราง transaction
                                                $q_reservations = $conn->prepare("SELECT r.roomcode, m.studentid, m.name, m.surname, m.course, m.province, t.years, t.term, t.datecreate, t.dateupdate
                                                                                FROM transaction t
                                                                                JOIN member m ON t.stdid = m.memberid
                                                                                JOIN room r ON t.roomid = r.roomid
                                                                                WHERE t.stdid = ?
                                                                                ORDER BY t.datecreate ASC"); // ASC เพื่อเรียงลำดับจากน้อยไปมาก (จากเร็วสุดไปช้าสุด)
                                                $q_reservations->bind_param("i", $memberid);

                                                $q_reservations->execute();
                                                $result = $q_reservations->get_result();

                                                // ตรวจสอบว่ามีข้อมูลการจองห้องพักหรือไม่
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row['roomcode'] . "</td>";
                                                        echo "<td>" . $row['studentid'] . "</td>";
                                                        echo "<td>" . $row['name'] . "</td>";
                                                        echo "<td>" . $row['surname'] . "</td>";
                                                        echo "<td>" . $row['course'] . "</td>";
                                                        echo "<td>" . $row['province'] . "</td>";
                                                        echo "<td>" . $row['years'] . "</td>";
                                                        echo "<td>" . $row['term'] . "</td>";
                                                        echo "<td>" . $row['datecreate'] . "</td>";
                                                        echo "<td>" . ($row['dateupdate']) . "<br> (ให้ย้ายออกก่อนสิ้นเดือนนี้)</td>";
                                                        // ข้อมูลอื่น ๆ ที่ต้องการแสดง
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    // ถ้าไม่มีข้อมูลการจองห้องพัก
                                                    echo "<tr><td colspan='10'>ไม่พบข้อมูลการจองห้องพัก</td></tr>";
                                                }

                                                // ปิด statement
                                                $q_reservations->close();
                                            } else {
                                                // ถ้าไม่มีการล็อกอิน
                                                echo "<tr><td colspan='10'>กรุณาล็อกอินก่อน</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
<!-- modal -->
<!-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">แก้ไขข้อมูล</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <h1 class="card-title"></h1>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'dormitory');
                    $sql = "SELECT * FROM member WHERE memberid = 1";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<h5>ชื่อจริง</h5><input type="text" class="form-control" id="name" value="' . $row['name'] . '">';
                        echo '<h5>นามสกุล</h5><input type="text" class="form-control" id="surname" value="' . $row['surname'] . '">';
                        echo '<h5>หลักสูตร</h5><input type="text" class="form-control" id="course" value="' . $row['course'] . '">';
                        echo '<h5>จังหวัด</h5><input type="text" class="form-control" id="province" value="' . $row['province'] . '">';
                        echo '<h5>เบอร์โทร</h5><input type="text" class="form-control" id="phone" value="' . $row['phone'] . '">';
                        echo '<h5>username</h5><input type="text" class="form-control" id="username" value="' . $row['username'] . '">';
                        echo '<h5>password</h5><input type="text" class="form-control" id="password" value="' . $row['password'] . '">';
                    }
                    ?>
                    
                    <script>
                        $(document).ready(function () {
                            $("#update").click(function () {
                                var name = $("#name").val();
                                var surname = $("#surname").val();
                                var course = $("#course").val();
                                var province = $("#province").val();
                                var phone = $("#phone").val();
                                var username = $("#username").val();
                                var password = $("#password").val();
                                $.ajax({
                                    url: 'update_profile.php',
                                    method: 'POST',
                                    data: {
                                        name: name,
                                        surname: surname,
                                        course: course,
                                        province: province,
                                        phone: phone,
                                        username: username,
                                        password: password,
                                    },
                                    success: function (response) {
                                        alert(response);
                                    }
                                });
                            });
                        });
                    </script>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" id="update" class="btn btn-success" data-dismiss="modal">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->