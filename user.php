<?php
$userid = $_SESSION['id'] ?? 0;

include 'config.php';

// Function to validate and sanitize input
function validate($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$get_id = validate($_GET['p'] ?? '');

if ($userid && $get_id) {
    $stmt = $conn->prepare("SELECT username FROM users WHERE users_id = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->bind_result($username);
    if ($stmt->fetch() && $get_id === $username) {
        header("Location: profile.php");
        exit();
    }
    $stmt->close();
}

include 'nav.php';
?>

<main>      
<section class="profile">
    <div class="top">
        <?php 
        $get_id = validate($_GET['p'] ?? '');
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $get_id);
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results->num_rows < 1) {
            echo "User not found";
        }else{}
        while ($rows = $results->fetch_assoc()) {
                $pic = $rows['pic'];
                $usersid = $rows['users_id'];
            if ($pic == null) {
                    ?>
                    <img class="ppro" src="profile/1.jpg" alt="">
                    <?php
                } else {
                    ?>
                <img class="ppro" src="profile/<?=$pic = $rows['pic']; ?>" onerror="this.onerror=null; this.src='profile/1.jpg';">
                <?php
                }
                ?>
                <div class="you">
                <?php
                $follows = mysqli_query($conn, "SELECT * FROM followers WHERE followee_id = '$usersid' AND follower_id = '$userid'");
                $followerz = mysqli_num_rows($follows);
                if ($followerz == 1){
                ?>
                <button class="unfollow" data-user-id="<?php echo $usersid; ?>">following</button>
                <?php
                }else{
                ?>
                <button class="follow" data-user-id="<?php echo $usersid; ?>">follow</button>
                <?php
                }
                ?>
                    <label><?=$rows['username']; ?>
                    <?php
                if ($rows['verified'] == 1) {
                    ?>
                    &nbsp;<img class="verif" src="images/verified.png"   alt="">
                    <?php
                } else {
                }
                ?></label>
                    <div class="records">
                        <div class="tabs">
                        <?php
                        $postcount = mysqli_query($conn, "SELECT * FROM posts WHERE users_id = '$usersid'");
                        $cc = mysqli_num_rows($postcount);
                        ?>    
                        <label><?= $cc ?> Posts</label></div>
                        <?php
                        $folls = mysqli_query($conn, "SELECT * FROM followers where follower_id = '$usersid'");
                        $following = mysqli_num_rows($folls);
                        $foll = mysqli_query($conn, "SELECT * FROM followers where followee_id = '$usersid'");
                        $followers = mysqli_num_rows($foll);
                        ?>
                        <div class="tabs"><label class="fcount"><?= $followers ?> followers</label></div>
                        <div class="tabs"><label><?= $following ?> following</label></div>
                    </div>
                    <div class="spread"><label><?=$rows['name']; ?></label><br>
                        <p><?= $rows['bio']; ?></p>
                    </div>
                </div>
         
                <div class="highlights">

                </div>
            </div><br>
            <hr>
            <div class="minnav">
                <ul>
                    <li>Posts</li>
                </ul>
            </div>
            <div class="dash">
            <?php
            $view = "SELECT * FROM posts WHERE users_id = '$usersid' ORDER BY post_id DESC";
            $result = mysqli_query($conn, $view);
            while ($row = mysqli_fetch_array($result)){
                $poster_id = $row['users_id'];
                $postid = $row['post_id'];
                $querys = "SELECT * FROM users WHERE users_id = '$poster_id'";
                $results = mysqli_query($conn, $querys);
                while ($rows = mysqli_fetch_array($results)) {
                
?>
            
                <div class="dashes" data-view-id="<?= $postid; ?>">
                    <img src="uploads/<?= $row['photo']; ?>"  onerror="this.onerror=null; this.src='images/nopic.jpg';">
                    <div class="stat">
                    <?php
                    $likescount = mysqli_query($conn, "SELECT * FROM likes WHERE post_id = '$postid'");
                    $likes = mysqli_num_rows($likescount);
                    $commentscount = mysqli_query($conn, "SELECT * FROM comments WHERE post_id = '$postid'");
                    $comments = mysqli_num_rows($commentscount);
                    ?>
                        <img src="images/heart-3-fill1.png" alt="">
                        <label><?= $likes ?></label>&nbsp;&nbsp;
                        <img src="images/chat-3-fill.png" alt="">
                        <label><?= $comments ?></label>
                    </div>
                    <div class="vview" data-view-id="<?= $postid; ?>">
                        <img class="x" src="images/close-line.png" alt="">
                        <img class="xs dark" src="images/close-line (1).png" alt="">
                        <div class="views diss"><img class="vpost" src="uploads/<?= $row['photo']; ?>"  onerror="this.onerror=null; this.src='images/nopic.jpg';">
                            <div class="vliked"><img src="images/heart-3-fill.png" alt=""></div>
                        </div>
                        <div class="views">
                            <div class="top">
                                <div class="details">
                                    <img class="vpro" src="profile/<?=$rows['pic'];?>">
                                    <label><b><?= $rows['username']; ?></b></label>
                                </div>
                                <img class="more" src="images/more-fill.png" alt="">
                            </div>
                            <div class="comments">
                            <div class="comment">
                                    <img class="comimg" src="profile/<?=$rows['pic'];?>">
                                    <label><b><?=$rows['username'];?></b>&nbsp; <?=$row['caption'];?></label>
                                </div>
                            <?php
                        $cum = "SELECT * FROM comments WHERE post_id = '$postid'";
                        $cums = mysqli_query($conn, $cum);
                        while ($c = mysqli_fetch_array($cums)){
                        $commmenter = $c['users_id'];
                        $vc = "SELECT * FROM users WHERE users_id = '$commmenter'";
                        $vcc = mysqli_query($conn, $vc);
                        while ($vccc = mysqli_fetch_array($vcc)){
                        ?>
                                <div class="comment">
                        <img class="comimg" src="profile/<?= $vccc['pic']; ?>" onerror="this.onerror=null; this.src='profile/1.jpg';">
                            <label><b><a href="user.php?p=<?= $vccc['username']; ?>"><?= $vccc['username']; ?></a></b>&nbsp; <?= $c['comment'];?></label>
                        </div>
                                <?php
                        } }
                        ?>
                            </div>
                            <div class="react">
                                <div class="inter">
                                    <span>
                                    <div class="lkbtn">
                                        <img class="show dark" data-post-id="<?= $row['post_id']; ?>" src="images/heart-line (1).png" alt="light image">
                                    <img class="show light" data-post-id="<?= $row['post_id']; ?>" src="images/heart-line.png" alt="dark image">
                                        <img class="hide" data-view-id="<?= $postid; ?>" src="images/heart-fill.png" alt=""></div>
                                        <img class="cha light" data-post-id="<?= $row['post_id']; ?>" src="images/chat-3-line (1).png" alt="">
                            <img class="cha dark" data-post-id="<?= $row['post_id']; ?>" src="images/chat-3-line (2).png" alt="">
                            <img class="light" src="images/send-plane-fill.png" alt="">
                            <img class="dark" src="images/send-plane-fill (1).png" alt=""></span>
                                </div>
                                <div class="save">
                    <img class="book dark" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-line (1).png" alt="Bookmark">
                    <img class="book light" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-line.png" alt="Bookmark">
                    <img class="unbook dark" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-fill.png" alt="Bookmark">
                    <img class="unbook light" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-fill (1).png" alt="Bookmark">
                    </div>
                            </div>
                            <div class="statz"><label>
                                 <?php
                    $likescount = mysqli_query($conn, "SELECT * FROM likes WHERE post_id = '$postid'");
                    $likes = mysqli_num_rows($likescount);
                ?>
                <span class="count" data-view-id="<?= $postid; ?>"><?= $likes ?> likes</span>
                            </label><br>
                            
                                <label>14 hours ago</label>
                            </div>
                            <div class="add">
                                <div class="p"><textarea class="ttcom" data-post-id="<?= $row['post_id']; ?>" placeholder="Add a comment..."></textarea>
                                    <button  class="dbut" data-post-id="<?= $row['post_id']; ?>" disabled>post</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } }
            $postcount = mysqli_query($conn, "SELECT * FROM posts WHERE users_id = '$usersid'");
            if (mysqli_num_rows($postcount) == 0) {
                echo 'No post yet';
            }else{}
            ?>
            </div>
            <?php
} 
?>
        </section>
    </main>
    