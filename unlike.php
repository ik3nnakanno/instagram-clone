<?php
session_start();
$userid = $_SESSION['id'] ?? 0;
header('Content-Type: application/json');
include 'config.php';

$postid = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if ($postid == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
    exit;
}

$sql = "DELETE FROM likes WHERE users_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userid, $postid);
if ($stmt->execute()) {
    $status = 'unliked';
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to remove like']);
    exit;
}
$stmt->close();
$conn->close();

?>