<?php
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;

echo $userid;
?>
<div class="dash" id="dash">
    <?php
    $view = "SELECT * FROM posts WHERE users_id = '$userid' ORDER BY post_id DESC";
    $result = mysqli_query($conn, $view);
                while ($row = mysqli_fetch_array($result)){
                    $poster_id = $row['users_id'];
                    $postid = $row['post_id'];
                    $querys = "SELECT * FROM users WHERE users_id = '$poster_id'";
                    $results = mysqli_query($conn, $querys);
                    while ($rows = mysqli_fetch_array($results)) {
        while ($row = $result->fetch_assoc()) {
            $post_id = $row['post_id'];
            $photo = htmlspecialchars($row['photo']);
            $likes = $row['likes'];
            $comments = $row['comments'];
            ?>
            <div class="dashes pdashes" data-post-id="<?= $post_id; ?>">
                <img src="uploads/<?= $photo; ?>" onerror="this.onerror=null; this.src='images/nopic.jpg';">
                <div class="stat">
                    <img src="images/heart-3-fill1.png" alt="">
                    <label><?= $likes ?></label>&nbsp;&nbsp;
                    <img src="images/chat-3-fill.png" alt="">
                    <label><?= $comments ?></label>
                </div>
            </div>
            <?php
        }
    } } 
    ?>
</div>
