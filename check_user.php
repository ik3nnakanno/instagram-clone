<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    $username = trim($_GET['username']);

    if (empty($username)) {
        echo 'exists';
        exit;
    }

    if (strlen($username) < 3) {
        echo 'short';
        exit;
    }

    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'available';
    }
} else {
    echo 'Invalid request';
}
?>