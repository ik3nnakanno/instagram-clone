<?php
include 'config.php';

session_start();

if(isset($_POST['delete'])) {
    $userid = $_POST['userid'];

    $deleteUser = mysqli_query($conn, "DELETE FROM users WHERE users_id = '$userid'");
    $deletePosts = mysqli_query($conn, "DELETE FROM posts WHERE users_id = '$userid'");
    
    if($deleteUser && $deletePosts) {
        session_destroy();
        echo "success";
    } else {
        echo "error";
    }
}
?>

