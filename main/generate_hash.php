<?php
    $password = "admin001";
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "Hashed Password: " . $hash;
?>