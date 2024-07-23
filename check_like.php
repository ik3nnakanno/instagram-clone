<?php
session_start();
$userid = $_SESSION['id'] ?? 0;
header('Content-Type: application/json');
include 'config.php';

$postid = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($postid == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
    exit;
}

$sql = "SELECT * FROM likes WHERE users_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userid, $postid);
$stmt->execute();
$result = $stmt->get_result();
$isLiked = $result->num_rows > 0;

echo json_encode(['liked' => $isLiked]);

$stmt->close();
$conn->close();
?>