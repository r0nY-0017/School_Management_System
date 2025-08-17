<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$sql = "SELECT id, name, class, roll, email FROM students";
$result = mysqli_query($conn, $sql);
$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}
echo json_encode($students);
mysqli_close($conn);
?>