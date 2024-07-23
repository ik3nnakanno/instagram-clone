<main>
        <section class="contents">
            <?php
            $sql4 = "SELECT p.*
            FROM posts p
            INNER JOIN followers f ON p.users_id = f.followee_id
            WHERE f.follower_id = '$userid'";
              $result5 = mysqli_query($conn, $sql4);
              if (mysqli_num_rows($result5) < 1) {
                echo "Add to your feed by following people!";
              }else{}
              while ($row = mysqli_fetch_array($result5)) {
                $poster_id = $row['users_id'];
                $postid = $row['post_id'];
                ?>
            <div class="post post-<?= $row['post_id']; ?>" data-post-id="<?= $row['post_id']; ?>">
                <?php
                $querys = "SELECT * FROM users WHERE users_id = '$poster_id'";
                $results = mysqli_query($conn, $querys);
                while ($rows = mysqli_fetch_array($results)) {
                    $check_sql = "SELECT * FROM saved WHERE post_id = ? AND users_id = ?";
                        $stmt = $conn->prepare($check_sql);
                        $stmt->bind_param("ii", $postid, $userid);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $is_saved = $result->num_rows > 0;
                        $stmt->close();
                ?>
                <div class="top">
                    <div class="details">
                    <a href="user.php?p=<?= $rows['username']; ?>">
                    <img class="bvb" src="profile/<?= $rows['pic']; ?>" onerror="this.onerror=null; this.src='profile/1.jpg';"></a>
                        <label><a href="user.php?p=<?= $rows['username']; ?>">
                            <b><?= $rows['username']; ?></b></a>
                            <?php
                if ($rows['verified'] == 1) {
                    ?>
                    &nbsp;<img class="verif" src="images/verified.png"  alt="">
                    <?php
                } else {
                }
                ?>
                        </label>
                        <label><b>&bull;</b>&nbsp;
    <?php
    $data_added_time = $row['date'];
    $current_time = time();
    $data_added_timestamp = strtotime($data_added_time);
    $time_diff = $current_time - $data_added_timestamp;

    if ($time_diff < 60) {
        echo "Just now";
    } elseif ($time_diff < 3600) {
        $minutes = floor($time_diff / 60);
        echo $minutes . " m";
    } elseif ($time_diff < 86400) {
        $hours = floor($time_diff / 3600);
        $minutes = floor(($time_diff % 3600) / 60); // Calculate remaining minutes
        if ($minutes > 0) {
            echo $minutes . " m";
        } else {
            echo $hours . " h";
        }
    } elseif ($time_diff < 172800) { // Between 24 hours and 48 hours
        echo "1 day ago";
    } else {
        $days = floor($time_diff / 86400); // Each day is 86400 seconds
        echo $days . " days ago";
    }
    ?>
</label>

                    </div>
                    <div class="dots"><img src="images/more-fill.png" alt=""></div>
                </div>
                <div class="display" data-post-id="<?= $row['post_id']; ?>">
                    <div class="ppp"><img src="uploads/<?= $row['photo']; ?>"  onerror="this.onerror=null; this.src='images/nopic.jpg';"></div>
                    <div class="mliked"><img src="images/heart-3-fill.png" alt=""></div>
                </div>
                <div class="react">
                    <div class="inter">
                        <span>
                            <?php
                             $likeSql = "SELECT * FROM likes WHERE users_id = ? AND post_id = ?";
                             $stmt = $conn->prepare($likeSql);
                             $stmt->bind_param("ii", $userid, $postid);
                             $stmt->execute();
                             $likeResult = $stmt->get_result();
                             $isLiked = $likeResult->num_rows > 0;
                             ?>
                            <div class="lkbtn">
                                <img class="mshow dark" data-post-id="<?= $row['post_id']; ?>" src="images/heart-line (1).png" alt="light image">
                                <img class="mshow light" data-post-id="<?= $row['post_id']; ?>" src="images/heart-line.png" alt="dark image">
                                <img class="mhide" data-post-id="<?= $row['post_id']; ?>" src="images/heart-fill.png" alt="">
                            </div>
                            <img class="cha light" data-post-id="<?= $row['post_id']; ?>" src="images/chat-3-line (1).png" alt="">
                            <img class="cha dark" data-post-id="<?= $row['post_id']; ?>" src="images/chat-3-line (2).png" alt="">
                            <img class="light" src="images/send-plane-fill.png" alt="">
                            <img class="dark" src="images/send-plane-fill (1).png" alt="">
                        </span>
                    </div>
                    <div class="save">
                    <img class="book dark" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-line (1).png" alt="Bookmark">
                    <img class="book light" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-line.png" alt="Bookmark">
                    <img class="unbook dark" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-fill.png" alt="Bookmark">
                    <img class="unbook light" data-post-id="<?= $row['post_id']; ?>" src="images/bookmark-fill (1).png" alt="Bookmark">
                    </div>
                </div>
                <b><div class="likes">
                    <?php
                    $likescount = mysqli_query($conn, "SELECT * FROM likes WHERE post_id = '$postid'");
                    $likes = mysqli_num_rows($likescount);
                ?>
                <span class="count" data-post-id="<?= $row['post_id']; ?>"><?= $likes ?> likes</span>
                </div></b>
                <div class="text">
                    <label><a href="user.php?p=<?= $rows['username']; ?>"><b><?= $rows['username']; ?></b></a>&nbsp; <?= $row['caption'];?></label>
                </div>
                <div class="comments xve"  data-post-id="<?= $row['post_id']; ?>">
                    <?php
                    $commentscount = mysqli_query($conn, "SELECT * FROM comments WHERE post_id = '$postid'");
                    $comments = mysqli_num_rows($commentscount);
                    ?>
                    <label>
                        <?php
                        if ($comments == 0){
                            echo 'No comment yet';
                        }elseif ($comments == 1){
                            echo 'View 1 comment';
                        }else {
                            ?>
                                View <?= $comments ?> comments
                            <?php
                        }
                    ?>
                    </label>
                </div>
                <div class="add">
                    <div class="p">
                        <textarea class="txtcom" data-post-id="<?= $row['post_id']; ?>" name="comment" placeholder="Add a comment..."></textarea>
                        <button class="cbut" data-post-id="<?= $row['post_id']; ?>" style="display: none;" disabled><label>Post</label></button>
                    </div>
                    <img src="images/emoji-sticker-line (1).png" alt="emoji" id="emojiImage">
                </div>
            
            <div class="view pview" data-view-id="<?= $postid; ?>">
                        <img class="x" data-view-id="<?= $postid; ?>" src="images/close-line.png" alt="">
                        <img class="xs dark" data-view-id="<?= $postid; ?>" src="images/close-line (1).png" alt="">
                        <div class="views diss"><img class="vpost" src="uploads/<?= $row['photo']; ?>"  onerror="this.onerror=null; this.src='images/nopic.jpg';">
                            <div class="mliked"><img src="images/heart-3-fill.png" alt=""></div>
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
                                        <img class="mshow dark" data-post-id="<?= $row['post_id']; ?>" src="images/heart-line (1).png" alt="light image">
                                        <img class="mshow light" data-post-id="<?= $row['post_id']; ?>" src="images/heart-line.png" alt="dark image">
                                        <img class="mhide" data-view-id="<?= $postid; ?>" src="images/heart-fill.png" alt="">
                                        <img class="light" data-post-id="<?= $row['post_id']; ?>" src="images/chat-3-line (1).png" alt="">
                                        <img class="dark" data-post-id="<?= $row['post_id']; ?>" src="images/chat-3-line (2).png" alt="">
                                        <img class="light" src="images/send-plane-fill.png" alt="">
                                        <img class="dark" src="images/send-plane-fill (1).png" alt="">    
                                    </span>
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
                <span class="count" data-post-id="<?= $row['post_id']; ?>"><label><?= $likes ?> likes</label></span>
                            </label><br>
                            
                                <label>
                                <?php
                        $data_added_timestamp = strtotime($row['date']);
                            $time_diff = time() - $data_added_timestamp;

                            if ($time_diff < 60) {
                                echo "Just now";
                            } elseif ($time_diff < 3600) {
                                echo floor($time_diff / 60) . " m";
                            } elseif ($time_diff < 86400) {
                                echo floor($time_diff / 3600) . " h";
                            } elseif ($time_diff < 172800) {
                                echo "1 day ago";
                            } else {
                                echo floor($time_diff / 172800) . " days ago";
                            }?>.
                                </label>
                            </div>
                            <div class="add"><img src="images/emoji-sticker-line (1).png" alt="emoji" id="emojiImage">
                                <div class="p"><textarea class="txtcom" data-post-id="<?= $row['post_id']; ?>" name="comment" placeholder="Add a comment..."></textarea>
                                    <button class="cbut" data-post-id="<?= $row['post_id']; ?>"style="display: none;" disabled>Post</button>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>
            <?php
              } } 
              ?>
              <br><br><br>
        </section>
        <section class="side">
    <label for="">Suggestions for you</label><br><br>
    <div class="suggest">
        <?php
        
        $suggestQuery = "SELECT * FROM users WHERE users_id != $userid ORDER BY RAND() LIMIT 3;";
        $suggestResult = mysqli_query($conn, $suggestQuery);


        while ($userRow = mysqli_fetch_assoc($suggestResult)) {
            $username = htmlspecialchars($userRow['username']);
            $pic = htmlspecialchars($userRow['pic']);
            $verified = $userRow['verified'];
            ?>
            <div class="pep">
                <a href="user.php?p=<?= $username ?>">
                    <img class="pepp" src="profile/<?= $pic ?>" onerror="this.onerror=null; this.src='profile/1.jpg';">
                </a>
                <div class="ttx">
                    <label for=""><a href="user.php?p=<?= $username ?>"><?= $username ?></a></label>
                    <?php if ($verified == 1): ?>
                        &nbsp;<img class="verif" src="images/verified.png" alt="">
                    <?php endif; ?>
                    <br><label for="">Suggested for you</label>
                </div>
            </div><br>
            <?php
        }
        ?>
    </div>
</section>



    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const textElements = document.querySelectorAll('.text');

            textElements.forEach(element => {
                let htmlContent = element.innerHTML;
                const regex = /@(\w+)/g;

                htmlContent = htmlContent.replace(regex, (match, p1) => {
                    return `<a href="user.php?p=${p1}" class="highlight">${match}</a>`;
                });
                
                element.innerHTML = htmlContent;
            });
        });
    </script>
   