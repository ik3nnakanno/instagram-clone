<?php
session_start();
$userid = $_SESSION['id'] ?? 0;
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

header('Content-Type: application/json');
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $caption = isset($_POST['caption']) ? validate($_POST['caption']) : '';
    $img_name = $_FILES['photo']['name'];
    $img_size = $_FILES['photo']['size'];
    $tmp_name = $_FILES['photo']['tmp_name'];
    $error = $_FILES['photo']['error'];

    if ($error !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error during file upload.';
    } elseif ($img_size < 2048 || $img_size > 10485760) {
        $response['message'] = 'File size must be between 2KB and 10MB';
    } else {
        $img_ex = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        $allowed_exs = ['jpg', 'png', 'jpeg'];
        if (in_array($img_ex, $allowed_exs)) {
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

            // Check if the current photo is already '1.jpg'
            if ($pic === '1.jpg') {
                $response['success'] = false;
            }else{

            // Generate a new name for the old image
            $img_ex = pathinfo($pic, PATHINFO_EXTENSION);
            $del_img_name = uniqid("del-instagram-", true) . '.' . $img_ex;
            $del_img_path = 'profile/' . $del_img_name;

            // Rename the old image
            $old_img_path = 'profile/' . $pic;
            if (file_exists($old_img_path)) {
                rename($old_img_path, $del_img_path);
            }
        }
            // Upload the new image
            $new_img_name = uniqid("instagram-", true) . '.' . $img_ex;
            $img_upload_path = 'profile/' . $new_img_name;
            if (move_uploaded_file($tmp_name, $img_upload_path)) {
                $stmt = $conn->prepare("UPDATE users SET pic = ? WHERE users_id = ?");
                if ($stmt === false) {
                    $response['success'] = false;
                    $response['message'] = 'Statement preparation failed: ' . $conn->error;
                    echo json_encode($response);
                    exit();
                }

                $stmt->bind_param("si", $new_img_name, $userid);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Photo has been updated';
                } else {
                    $response['success'] = false;
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
