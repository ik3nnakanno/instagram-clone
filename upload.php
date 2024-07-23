<?php
include 'config.php';
session_start();

$response = array('success' => false, 'message' => '');

$userid = $_SESSION['id'] ?? 0;
if ($userid == 0) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post']) && isset($_FILES['photo'])) {

    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $caption = validate($_POST['caption']);
    $img_name = $_FILES['photo']['name'];
    $img_size = $_FILES['photo']['size'];
    $tmp_name = $_FILES['photo']['tmp_name'];
    $error = $_FILES['photo']['error'];

    if ($error !== UPLOAD_ERR_OK) {
        $response['message'] = 'File upload error';
    } elseif ($img_size > 10 * 1024 * 1024) {
        $response['message'] = 'File too large';
    } else {
        $img_ex = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_exs = ['jpg', 'png', 'jpeg', 'webp'];

        if (in_array($img_ex, $allowed_exs)) {
            $new_img_name = uniqid("instagram-", true) . '.' . $img_ex;
            $img_upload_path = 'uploads/' . $new_img_name;
            if (move_uploaded_file($tmp_name, $img_upload_path)) {
                $stmt = $conn->prepare("INSERT INTO posts (users_id, caption, photo) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $userid, $caption, $new_img_name);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Image uploaded successfully';
                } else {
                    $response['message'] = 'Database error: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = 'Error uploading file.';
            }
        } else {
            $response['message'] = 'Invalid image format';
        }
    }
} else {
    $response['message'] = 'Invalid request';
}

$conn->close();
echo json_encode($response);
?>
