<?php
$conn = mysqli_connect('localhost', 'test', '1234', 'instagram');

if (!$conn) {
    echo "connection failed";
}
$userid = $_SESSION['id'] ?? 0;