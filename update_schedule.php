<?php // กำหนดการ
$conn = new mysqli('localhost', 'root', '', 'dormitory');
$schedule = $_POST["schedule"];
$sql = "UPDATE system_settings SET schedule='$schedule' WHERE id = 1";
if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูล";
} else {
    echo "Error updating data: " . $conn->error;
}
$conn->close();
?>