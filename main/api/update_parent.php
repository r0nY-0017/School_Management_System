<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'];
$name = $input['name'];
$email = $input['email'];
$student_id = $input['student_id'];
$password = !empty($input['password']) ? password_hash($input['password'], PASSWORD_DEFAULT) : null;

$sql = $password ?
    "UPDATE parents SET name = ?, email = ?, student_id = ?, password = ? WHERE id = ?" :
    "UPDATE parents SET name = ?, email = ?, student_id = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($password) {
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $student_id, $password, $id);
} else {
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $student_id, $id);
}

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>