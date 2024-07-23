<?php
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $followee_id = $_POST['followee_id'];
    $action = $_POST['action'];

    if ($action == 'follow') {
        $checkQuery = "SELECT * FROM followers WHERE followee_id = '$followee_id' AND follower_id = '$userid'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) == 0) {
            $query = "INSERT INTO followers (followee_id, follower_id) VALUES ('$followee_id', '$userid')";
            if (mysqli_query($conn, $query)) {
                echo 'followed';
            } else {
                echo 'error';
            }
        } else {
            echo 'already_following';
        }
    } elseif ($action == 'unfollow') {
        $query = "DELETE FROM followers WHERE followee_id = '$followee_id' AND follower_id = '$userid'";
        if (mysqli_query($conn, $query)) {
            echo 'unfollowed';
        } else {
            echo 'error';
        }
    }
}
?>
