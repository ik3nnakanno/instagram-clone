<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['followee_id'])) {
        $followee_id = $_POST['followee_id'];
        $foll = mysqli_query($conn, "SELECT * FROM followers WHERE followee_id = '$followee_id'");
        if ($foll) {
            $followers = mysqli_num_rows($foll);
            echo $followers;
        } else {
            echo 'error';
        }
    } else {
        echo 'error: followee_id not set';
    }
} else {
    echo 'error: invalid request method';
}
?>
