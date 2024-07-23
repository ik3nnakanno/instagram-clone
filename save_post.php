<?php
header('Content-Type: application/json');
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;
$postid = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;

if ($postid === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit();
}

try {
    $stmt = null;
    $status = '';

    // Check if the post is already saved
    $check_sql = "SELECT * FROM saved WHERE post_id = ? AND users_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $postid, $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $is_saved = $result->num_rows > 0;
    $stmt->close();

    if ($is_saved) {
        // Delete the saved post
        $delete_sql = "DELETE FROM saved WHERE post_id = ? AND users_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("ii", $postid, $userid);
        $status = 'unsaved';
    } else {
        // Insert the saved post
        $insert_sql = "INSERT INTO saved (post_id, users_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ii", $postid, $userid);
        $status = 'saved';
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => $status, 'like_count' => 0]); // Assuming 0 for now
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();

?>