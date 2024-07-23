<?php
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;

if ($userid == 0) {
    echo 'No saved post yet';
    exit;
}

$saveQuery = "SELECT * FROM saved WHERE users_id = ?";
$saveStmt = $conn->prepare($saveQuery);
$saveStmt->bind_param("i", $userid);
$saveStmt->execute();
$saveResult = $saveStmt->get_result();

if ($saveResult->num_rows == 0) {
    echo 'No saved post yet';
} else {
    while ($ss = $saveResult->fetch_assoc()) {
        $savedPost = $ss['post_id'];

        $postQuery = "SELECT * FROM posts WHERE post_id = ? ORDER BY post_id DESC";
        $postStmt = $conn->prepare($postQuery);
        $postStmt->bind_param("i", $savedPost);
        $postStmt->execute();
        $postResult = $postStmt->get_result();

        while ($row = $postResult->fetch_assoc()) {
            $poster_id = $row['users_id'];

            $userQuery = "SELECT * FROM users WHERE users_id = ?";
            $userStmt = $conn->prepare($userQuery);
            $userStmt->bind_param("i", $poster_id);
            $userStmt->execute();
            $userResult = $userStmt->get_result();

            while ($user = $userResult->fetch_assoc()) {
                ?>

                <div class="dashes" data-view-id="<?= $row['post_id']; ?>">
                    <img src="uploads/<?= $row['photo']; ?>" onerror="this.onerror=null; this.src='images/nopic.jpg';">
                    <div class="stat">
                        <?php
                        $likesQuery = "SELECT * FROM likes WHERE post_id = ?";
                        $likesStmt = $conn->prepare($likesQuery);
                        $likesStmt->bind_param("i", $row['post_id']);
                        $likesStmt->execute();
                        $likesResult = $likesStmt->get_result();
                        $likes = $likesResult->num_rows;

                        $commentsQuery = "SELECT * FROM comments WHERE post_id = ?";
                        $commentsStmt = $conn->prepare($commentsQuery);
                        $commentsStmt->bind_param("i", $row['post_id']);
                        $commentsStmt->execute();
                        $commentsResult = $commentsStmt->get_result();
                        $comments = $commentsResult->num_rows;
                        ?>
                        <img src="images/heart-3-fill1.png" alt="">
                        <label><?= $likes ?></label>&nbsp;&nbsp;
                        <img src="images/chat-3-fill.png" alt="">
                        <label><?= $comments ?></label>
                    </div>
                    <div class="vview" data-view-id="<?= $row['post_id']; ?>">
                        <img class="x" src="images/close-line.png" alt="">
                        <div class="views diss">
                            <img class="vpost" src="uploads/<?= $row['photo']; ?>" onerror="this.onerror=null; this.src='images/nopic.jpg';">
                            <div class="vliked"><img src="images/heart-3-fill.png" alt=""></div>
                        </div>
                        <div class="views">
                            <div class="top">
                                <div class="details">
                                    <img class="vpro" src="profile/<?= $user['pic']; ?>">
                                    <label><b><?= $user['username']; ?></b></label>
                                </div>
                                <img class="more" src="images/more-fill.png" alt="">
                            </div>
                            <div class="comments">
                                <div class="comment">
                                    <img class="comimg" src="profile/<?= $user['pic']; ?>">
                                    <label><b><?= $user['username']; ?></b>&nbsp; <?= $row['caption']; ?></label>
                                </div>
                                <?php
                                $commentQuery = "SELECT * FROM comments WHERE post_id = ?";
                                $commentStmt = $conn->prepare($commentQuery);
                                $commentStmt->bind_param("i", $row['post_id']);
                                $commentStmt->execute();
                                $commentResult = $commentStmt->get_result();

                                while ($comment = $commentResult->fetch_assoc()) {
                                    $commenterQuery = "SELECT * FROM users WHERE users_id = ?";
                                    $commenterStmt = $conn->prepare($commenterQuery);
                                    $commenterStmt->bind_param("i", $comment['users_id']);
                                    $commenterStmt->execute();
                                    $commenterResult = $commenterStmt->get_result();

                                    while ($commenter = $commenterResult->fetch_assoc()) {
                                        ?>
                                        <div class="comment">
                                            <img class="comimg" src="profile/<?= $commenter['pic']; ?>" onerror="this.onerror=null; this.src='profile/1.jpg';">
                                            <label><b><a href="user.php?p=<?= $commenter['username']; ?>"><?= $commenter['username']; ?></a></b>&nbsp; <?= $comment['comment']; ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="react">
                                <div class="inter">
                                    <span>
                                        <img class="show" data-view-id="<?= $row['post_id']; ?>" src="images/heart-line.png" alt="">
                                        <img class="hide" data-view-id="<?= $row['post_id']; ?>" src="images/heart-fill.png" alt="">
                                        <img src="images/chat-3-line (1).png" alt="">
                                        <img src="images/send-plane-fill.png" alt="">
                                    </span>
                                </div>
                                <div class="save">
                                    <img src="images/bookmark-line.png" alt="">
                                </div>
                            </div>
                            <div class="statz">
                                <label>
                                    <span class="count" data-view-id="<?= $row['post_id']; ?>"><?= $likes ?> likes</span>
                                </label><br>
                                <label>14 hours ago</label>
                            </div>
                            <div class="add">
                                <img src="images/emoji-sticker-line (1).png" alt="emoji" id="emojiImage">
                                <div class="p">
                                    <textarea data-post-id="<?= $row['post_id']; ?>" placeholder="Add a comment..."></textarea>
                                    <button data-post-id="<?= $row['post_id']; ?>" disabled>post</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}
// Assuming $conn is your database connection
$savedPost = mysqli_real_escape_string($conn, $savedPost); // Sanitize input to prevent SQL injection

$postcount = mysqli_query($conn, "SELECT * FROM posts WHERE post_id = '$savedPost'");

if (mysqli_num_rows($postcount) == 0) { // Check if there are no saved posts
    echo '<label>No saved post yet</label>';
} else {
    // Handle the case where the post exists (optional)
}

?>
