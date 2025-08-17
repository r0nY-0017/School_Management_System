<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$sql = "SELECT id, name, subject, email FROM teachers";
$result = mysqli_query($conn, $sql);
$teachers = [];
while ($row = mysqli_fetch_assoc($result)) {
    $teachers[] = $row;
}
echo json_encode($teachers);
mysqli_close($conn);
?>