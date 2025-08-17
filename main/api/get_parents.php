<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$sql = "SELECT id, name, student_id, email FROM parents";
$result = mysqli_query($conn, $sql);
$parents = [];
while ($row = mysqli_fetch_assoc($result)) {
    $parents[] = $row;
}
echo json_encode($parents);
mysqli_close($conn);
?>