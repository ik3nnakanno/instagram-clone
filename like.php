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

// Check if the like already exists
$sql = "SELECT * FROM likes WHERE users_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userid, $postid);
$stmt->execute();
$result = $stmt->get_result();

$status = '';
if ($result->num_rows == 0) {
    // Insert the like
    $sql = "INSERT INTO likes (users_id, post_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userid, $postid);
    if ($stmt->execute()) {
        $status = 'liked';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert like']);
        exit;
    }
} else {
    // Remove the like
    $sql = "DELETE FROM likes WHERE users_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userid, $postid);
    if ($stmt->execute()) {
        $status = 'unliked';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove like']);
        exit;
    }
}

// Get the updated like count
$sql = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$like_count = $row['like_count'];

echo json_encode(['status' => $status, 'like_count' => $like_count]);

$stmt->close();
$conn->close();
?>
