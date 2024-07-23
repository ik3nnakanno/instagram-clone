<?php
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;
header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => 'Invalid request'
];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $post_id = $_POST['post_id'] ?? '';
        $comment = $_POST['comment'] ?? '';

        // Debugging: Log received data
        error_log("Received user_id: " . $userid);
        error_log("Received post_id: " . $post_id);
        error_log("Received comment: " . $comment);

        if (!empty($userid) && !empty($post_id) && !empty($comment)) {
            // Insert comment into the database
            $stmt = $conn->prepare("INSERT INTO comments (post_id, users_id, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $post_id, $userid, $comment);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Comment added successfully';
            } else {
                $response['message'] = 'Database error: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['message'] = 'All fields are required';
        }
    }
} catch (Exception $e) {
    $response['message'] = 'Exception: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>
