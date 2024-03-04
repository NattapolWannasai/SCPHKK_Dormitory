<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "dormitory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// คิวรีข้อมูลหอพักจากฐานข้อมูล
$q_w = $conn->query("SELECT * FROM `dorm` WHERE `dormid`= 5");
$d_w = $q_w->fetch_assoc();

$q_y = $conn->query("SELECT * FROM `conflict_years` WHERE `years` && `term`");
$d_y = $q_y->fetch_assoc();

// สร้าง Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// เพิ่มหัวข้อลงไปบนสุดของไฟล์ Excel
$sheet->setCellValue('A1', $d_w['name']);
$sheet->setCellValue('A2', 'เพิ่ม ปีการศึกษา : ' . $d_y['years']);
$sheet->setCellValue('A3', 'เทอม : ' . $d_y['term']);

// ตั้งค่าสไตล์สำหรับเซลล์ A1
$sheet->getStyle('A1')->getFont()->setBold(true);
$sheet->getStyle('A1')->getFont()->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
$sheet->setCellValue('A1', $d_w['name'] . str_repeat(" ", 1) . "\n" . 'ปีการศึกษา : ' . $d_y['years'] . str_repeat(" ", 1) . 'เทอม : ' . $d_y['term']);
$sheet->mergeCells('A1:H1'); // รวมเซลล์สำหรับหัวข้อ

// ตั้งค่าสไตล์สำหรับเซลล์ตาราง
$sheet->getStyle("A1:H1")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// คำสั่ง SQL สำหรับดึงข้อมูลห้องพักสำหรับเพศหญิง
$roomsQuery = "SELECT * FROM room WHERE gender = 2 ORDER BY floor, roomcode";
if ($roomsResult = $conn->query($roomsQuery)) {
    $rowCount = 2; // เริ่มจากแถวที่ 2 หลังจากหัวข้อ

    // เริ่มดึงข้อมูลห้องพัก
    while ($roomRow = $roomsResult->fetch_assoc()) {
        // เพิ่มหัวข้อตารางสำหรับแต่ละห้อง
        $sheet->setCellValue('A' . $rowCount, "ชั้นที่ " . $roomRow['floor'] . " ห้องที่ " . $roomRow['roomcode']);
        $sheet->mergeCells("A$rowCount:H$rowCount");
        $sheet->getStyle("A$rowCount")->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowCount . ':H' . $rowCount)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $rowCount++;

        // ตั้งค่าสไตล์สำหรับหัวข้อตาราง
        $headings = ['ลำดับ', 'ชื่อ', 'นามสกุล', 'หลักสูตร', 'จังหวัด', 'วันที่จอง', 'วันที่ออกออก', 'เบอร์โทร'];
        $column = 'A';
        foreach ($headings as $heading) {
            $sheet->setCellValue($column . $rowCount, $heading);
            $sheet->getStyle($column . $rowCount)->getFont()->setBold(true); // ตั้งให้ตัวอักษรเป็นตัวหนา
            $sheet->getStyle($column . $rowCount)->getAlignment()->setHorizontal('center'); // ตั้งค่าการจัดวางข้อความในแนวนอนตรงกลาง
            $sheet->getStyle($column . $rowCount)->getAlignment()->setVertical('center'); // ตั้งค่าการจัดวางข้อความในแนวตั้งตรงกลาง
            $sheet->getColumnDimension($column)->setAutoSize(true); // ตั้งค่าให้คอลัมน์ปรับขนาดโดยอัตโนมัติ
            $column++;
        }
        $sheet->getStyle("A$rowCount:H$rowCount")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $rowCount++;

        // ดึงข้อมูลการจองสำหรับห้องนี้
        $reservationsQuery = "SELECT t.*, m.studentid, m.name AS student_name, m.surname AS student_surname, m.course AS student_course, m.province, m.phone
                              FROM transaction t
                              JOIN member m ON t.stdid = m.memberid
                              WHERE t.roomid = ? AND t.status = 1 AND (t.years, t.term) IN (SELECT years, term FROM conflict_years)";

        if ($stmt = $conn->prepare($reservationsQuery)) {
            $stmt->bind_param("i", $roomRow['roomid']);
            $stmt->execute();
            $reservationsResult = $stmt->get_result();

            $sequence = 1;
            while ($reservationRow = $reservationsResult->fetch_assoc()) {
                $sheet->setCellValue('A' . $rowCount, $sequence++);
                $sheet->setCellValue('B' . $rowCount, $reservationRow['student_name']);
                $sheet->setCellValue('C' . $rowCount, $reservationRow['student_surname']);
                $sheet->setCellValue('D' . $rowCount, $reservationRow['student_course']);
                $sheet->setCellValue('E' . $rowCount, $reservationRow['province']);
                $sheet->setCellValue('F' . $rowCount, $reservationRow['datecreate']);
                $sheet->setCellValue('G' . $rowCount, $reservationRow['dateupdate']);
                $sheet->setCellValue('H' . $rowCount, $reservationRow['phone']);

                // เพิ่มเส้นขอบสำหรับแถวนี้
                $sheet->getStyle("A$rowCount:H$rowCount")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // ตั้งค่าการจัดวางข้อความในแนวนอนให้ชิดซ้าย
                $sheet->getStyle("A$rowCount:H$rowCount")->getAlignment()->setHorizontal('left');

                $rowCount++;
            }
            $stmt->close();
        }
        $rowCount++; // ข้ามแถวหนึ่งแถวหลังจากจบข้อมูลของแต่ละห้อง
    }
}

// ตรวจสอบข้อมูลการย้ายออก
$checkoutQuery = "SELECT t.*, m.studentid, m.name AS student_name, m.surname AS student_surname, m.course AS student_course, m.province, m.phone
                  FROM transaction t
                  JOIN member m ON t.stdid = m.memberid
                  JOIN room r ON t.roomid = r.roomid
                  WHERE t.status = 0 AND (r.roomid = 0 OR r.gender = 2) AND (t.years, t.term) IN (SELECT years, term FROM conflict_years)";
$checkoutResult = $conn->query($checkoutQuery);

if ($checkoutResult->num_rows > 0) {
    // เพิ่มหัวข้อสำหรับตารางการย้ายออก
    $sheet->setCellValue("A$rowCount", "ตารางการย้ายออก");
    $sheet->mergeCells("A$rowCount:H$rowCount");
    $sheet->getStyle("A$rowCount")->getFont()->setBold(true);
    $sheet->getStyle("A$rowCount:H$rowCount")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $rowCount++;

    // หัวข้อคอลัมน์สำหรับตารางการย้ายออก
    $columns = ['ลำดับ', 'ชื่อ', 'นามสกุล', 'หลักสูตร', 'จังหวัด', 'วันที่จอง', 'วันที่ออก', 'เบอร์โทร'];
    $colIndex = 'A';
    foreach ($columns as $column) {
        $sheet->setCellValue($colIndex . $rowCount, $column);
        $sheet->getStyle($colIndex . $rowCount)->getAlignment()->setHorizontal('center'); // ตั้งค่าการจัดวางข้อความในแนวนอนตรงกลาง
        $sheet->getStyle($colIndex . $rowCount)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); // เพิ่มเส้นขอบ
        $colIndex++;
    }
    $sheet->getStyle("A$rowCount:H$rowCount")->getFont()->setBold(true);
    $sheet->getStyle("A$rowCount:H$rowCount")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); // เพิ่มเส้นขอบ
    $rowCount++;

    // นำเข้าข้อมูลการย้ายออก
    $count = 1;
    while ($row = $checkoutResult->fetch_assoc()) {
        $sheet->setCellValue("A$rowCount", $count++);
        $sheet->setCellValue("B$rowCount", $row['student_name']);
        $sheet->setCellValue("C$rowCount", $row['student_surname']);
        $sheet->setCellValue("D$rowCount", $row['student_course']);
        $sheet->setCellValue("E$rowCount", $row['province']);
        $sheet->setCellValue("F$rowCount", $row['datecreate']);
        $sheet->setCellValue("G$rowCount", $row['dateupdate']);
        $sheet->setCellValue("H$rowCount", $row['phone']);

        // เพิ่มเส้นขอบสำหรับแถวนี้
        $sheet->getStyle("A$rowCount:H$rowCount")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // ตั้งค่าการจัดวางข้อความในแนวนอนให้ชิดซ้าย
        $sheet->getStyle("A$rowCount:H$rowCount")->getAlignment()->setHorizontal('left');

        $rowCount++;
    }
}

// ตั้งค่าขนาดกระดาษ A4
$spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

// ตั้งค่าขอบ
$spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.5);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.5);
$spreadsheet->getActiveSheet()->getPageMargins()->setHeader(0.25);
$spreadsheet->getActiveSheet()->getPageMargins()->setFooter(0.25);

// ตั้งชื่อและบันทึกไฟล์ Excel
$writer = new Xlsx($spreadsheet);
$fileName = 'หอนักศึกษาลีลาวดี(หอพักหญิง).xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
$writer->save('php://output');
$conn->close();
exit;
?>