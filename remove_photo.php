<?php
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;
header('Content-Type: application/json');
$response = array();

if ($userid === 0) {
    $response['success'] = false;
    $response['message'] = 'User not authenticated';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'removePhoto') {
    // Retrieve current image name from the database
    $del = mysqli_query($conn, "SELECT pic FROM users WHERE users_id = $userid");
    if (!$del) {
        $response['success'] = false;
        $response['message'] = 'Database error: ' . mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    $chedel = mysqli_fetch_array($del);
    $pic = $chedel['pic'];

    // Check if the current image is already '1.jpg'
    if ($pic === '1.jpg') {
        $response['success'] = false;
        $response['message'] = 'lol';
        echo json_encode($response);
        exit();
    }else{

    $old_img_path = 'profile/' . $pic;

    // Generate a new name for the old image
    $img_ex = pathinfo($pic, PATHINFO_EXTENSION);
    $del_img_name = uniqid("del-instagram-", true) . '.' . $img_ex;
    $del_img_path = 'profile/' . $del_img_name;

    // Rename the old image
    if (file_exists($old_img_path)) {
        rename($old_img_path, $del_img_path);
    }
}
    // Update database to set default photo
    $default_photo = '1.jpg';
    $stmt = $conn->prepare("UPDATE users SET pic = ? WHERE users_id = ?");
    if ($stmt === false) {
        $response['success'] = false;
        $response['message'] = 'Statement preparation failed: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("si", $default_photo, $userid);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Photo has been removed';
    } else {
        $response['success'] = false;
        $response['message'] = 'Database error: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request';
}

$conn->close();
echo json_encode($response);
?>
