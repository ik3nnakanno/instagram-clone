<?php
session_start();
$userid = $_SESSION['id'] ?? 0;
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

header('Content-Type: application/json');
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $bio = htmlspecialchars(stripslashes(trim($_POST['bio'])));

    if (strlen($bio) > 100) {
        $response['success'] = false;
        $response['message'] = 'Bio must be 100 characters or less.';
    } else {
        $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE users_id = ?");
        $stmt->bind_param("si", $bio, $userid);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Bio updated successfully';
        } else {
            $response['success'] = false;
            $response['message'] = 'Database error: ' . $stmt->error;
        }

        $stmt->close();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request';
}

$conn->close();
echo json_encode($response);
?>
