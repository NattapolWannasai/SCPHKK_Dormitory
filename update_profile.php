<?php // แอดมินโปรไฟล์
$conn = new mysqli('localhost', 'root', '', 'dormitory');
$name = $_POST["name"];
$surname = $_POST["surname"];
$course = $_POST["course"];
$province = $_POST["province"];
$phone = $_POST["phone"];
$username = $_POST["username"];
$password = $_POST["password"];
$sql = "UPDATE member SET name='$name' , surname='$surname' , course='$course' , province='$province' , phone='$phone' , username='$username' , password='$password' WHERE memberid = 1";
if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูล";
} else {
    echo "Error updating data: " . $conn->error;
}
$conn->close();
?>