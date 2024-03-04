<?php require_once "admin/connect.php" ?>
<?php session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = $conn->prepare("SELECT * FROM dormitory.member INNER JOIN data_std.da_stddetails ON dormitory.member.memberid = data_std.da_stddetails.sdtstdId WHERE `username` = ? AND `password` = ?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();
    $fetch = $result->fetch_assoc();
    $row = $result->num_rows;
    $status = $fetch['status'];

    if ($row > 0) {

        if ($status == '0') {
            $_SESSION['memberid'] = $fetch['memberid'];
            $_SESSION['sdtSex'] = $fetch['sdtSex'];
            $_SESSION['name'] = $fetch['name'];
            $_SESSION['surname'] = $fetch['surname'];
            $_SESSION['status'] = $status; // เพิ่มบรรทัดนี้เพื่อเก็บค่า status ลงใน session
            header('location:index.php');
        } elseif ($status == '1') {
            $_SESSION['memberid'] = $fetch['memberid'];
            $_SESSION['name'] = $fetch['name'];
            $_SESSION['surname'] = $fetch['surname'];
            $_SESSION['status'] = $status; // เพิ่มบรรทัดนี้เพื่อเก็บค่า status ลงใน session
            header('location:admin.php');
        }

    } else {
        $_SESSION["Error"] = "<p> ชื่อผู้เข้าใช้งาน หรือ รหัสผ่าน ไม่ถูกต้อง </p>";
        header("location:login.php");
    }
}
?>