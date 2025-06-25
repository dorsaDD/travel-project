<?php

$host = 'mysql';
$user = 'root';
$password = 'root';
$db = 'travel';
$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    echo "connection error --> " . mysqli_connect_error();
}

?>