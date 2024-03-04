<?php // ข่าว
$conn = new mysqli('localhost', 'root', '', 'dormitory');
$news = $_POST["news"];
$sql = "UPDATE system_settings SET news='$news' WHERE id = 1";
if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูล";
} else {
    echo "Error updating data: " . $conn->error;
}
$conn->close();
?>