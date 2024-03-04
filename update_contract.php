<?php // ข่าว
$conn = new mysqli('localhost', 'root', '', 'dormitory');
$contract = $_POST["contract"];
$sql = "UPDATE system_settings SET contract='$contract' WHERE id = 1";
if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูล";
} else {
    echo "Error updating data: " . $conn->error;
}
$conn->close();
?>