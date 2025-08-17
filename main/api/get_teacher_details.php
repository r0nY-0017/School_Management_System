<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$id = $_GET['id'];
$sql = "SELECT id, name, subject, email FROM teachers WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$teacher = mysqli_fetch_assoc($result);
echo json_encode($teacher ?: []);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>