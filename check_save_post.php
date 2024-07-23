<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

$postid = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;
$userid = $_SESSION['id'] ?? 0;

if ($postid === null || $userid === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit();
}

try {
    $check_sql = "SELECT * FROM saved WHERE post_id = ? AND users_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $postid, $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $is_saved = $result->num_rows > 0;
    $stmt->close();

    echo json_encode(['saved' => $is_saved]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>
