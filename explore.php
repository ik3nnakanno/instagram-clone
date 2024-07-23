<?php
            include 'nav.php';
            $view = "SELECT * FROM posts WHERE users_id != '$userid' ORDER BY post_id DESC";
            $result = mysqli_query($conn, $view);
            while ($row = mysqli_fetch_array($result)){
                $poster_id = $row['users_id'];
                $postid = $row['post_id'];
                $querys = "SELECT * FROM users WHERE users_id = '$poster_id'";
                $results = mysqli_query($conn, $querys);
                while ($rows = mysqli_fetch_array($results)) {
                
?><main>
            <section class="explore">
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
                                        <img class="show" data-view-id="<?= $postid; ?>" src="images/heart-line.png" alt="">
                                        <img class="hide" data-view-id="<?= $postid; ?>" src="images/heart-fill.png" alt="">
                                        <img src="images/chat-3-line (1).png" alt="">
                                        <img src="images/send-plane-fill.png" alt=""></span>
                                </div>
                                <div class="save">
                                    <img src="images/bookmark-line.png" alt="">
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
                            <div class="add"><img src="images/emoji-sticker-line (1).png" alt="emoji" id="emojiImage">
                                <div class="p"><textarea data-post-id="<?= $row['post_id']; ?>" placeholder="Add a comment..."></textarea>
                                    <button data-post-id="<?= $row['post_id']; ?>" disabled>post</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } }
            $postcount = mysqli_query($conn, "SELECT * FROM posts WHERE users_id = '$userid'");
            if (mysqli_num_rows($postcount) == 0) {
                echo '<label>Nothing to explore</label>';
            }else{}
            ?></section></main>