<?php
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;

if ($userid == 0) {
    echo 'No post yet';
    exit;
}
    $view = "SELECT * FROM posts WHERE users_id = '$userid' ORDER BY post_id DESC";
                $result = mysqli_query($conn, $view);
                while ($row = mysqli_fetch_array($result)){
                    $poster_id = $row['users_id'];
                    $postid = $row['post_id'];
                    $querys = "SELECT * FROM users WHERE users_id = '$poster_id'";
                    $results = mysqli_query($conn, $querys);
                    while ($rows = mysqli_fetch_array($results)) {
                    
    ?>
                
                    <div class="dashes pdashes" data-post-id="<?= $row['post_id']; ?>">
                        <img src="uploads/<?= $row['photo']; ?>"  onerror="this.onerror=null; this.src='images/nopic.jpg';">
                        <div class="stat">
                        <?php
                        $likescount = mysqli_query($conn, "SELECT * FROM likes WHERE post_id = '$postid'");
                        $likes = mysqli_num_rows($likescount);
                        $commentscount = mysqli_query($conn, "SELECT * FROM comments WHERE post_id = '$postid'");
                        $comments = mysqli_num_rows($commentscount);
                        ?>
                            <img src="images/heart-3-fill1.png" alt="">
                            <label color="white"><?= $likes ?></label>&nbsp;&nbsp;
                            <img src="images/chat-3-fill.png" alt="">
                            <label color="white"><?= $comments ?></label>
                        </div>
                        
                    </div>
                    <?php
                } }
                $postcount = mysqli_query($conn, "SELECT * FROM posts WHERE users_id = '$userid'");
                if (mysqli_num_rows($postcount) == 0) {
                    echo '<label>No post yet</label>';
                }else{}
                ?>