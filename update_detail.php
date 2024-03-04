<?php // ข้อมูล
$conn = new mysqli('localhost', 'root', '', 'dormitory');
$detail = $_POST["detail"];
$sql = "UPDATE system_settings SET detail='$detail' WHERE id = 1";
if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูล";
} else {
    echo "Error updating data: " . $conn->error;
}
$conn->close();
?>