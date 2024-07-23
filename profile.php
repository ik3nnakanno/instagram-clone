<?php
include 'nav.php';
$userid = $_SESSION['id'] ?? 0;
if(isset($_POST['verify'])) {
    $userid = $_POST['userid'];
    mysqli_query($conn, "UPDATE users SET verified = 1 WHERE users_id = '$userid'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>
<main>      
<section class="profile">
            <div class="top">
                <?php 
            $query = "SELECT * FROM users WHERE users_id = '$userid'";
            $results = mysqli_query($conn, $query);
            while ($rows = mysqli_fetch_array($results)) {
                $pic = $rows['pic'];
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
                    <div class="butts">
                        <a href="edit.php"><button>Edit profile</button></a>
                        <button id="viewSaved">View archive</button>
                        <img class="light setbtn" src="images/settings-3-fill.png" alt="">
                        <img class="dark setbtn" src="images/settings-3-fill (1).png" alt="">
                    </div>
                    <div class="records">
                        <div class="tabs">
                        <?php
                        $postcount = mysqli_query($conn, "SELECT * FROM posts WHERE users_id = '$userid'");
                        $cc = mysqli_num_rows($postcount);
                        ?>    
                        <label><?= $cc ?> Posts</label></div>
                        <?php
                        $folls = mysqli_query($conn, "SELECT * FROM followers where follower_id = '$userid'");
                        $following = mysqli_num_rows($folls);
                        $foll = mysqli_query($conn, "SELECT * FROM followers where followee_id = '$userid'");
                        $followers = mysqli_num_rows($foll);
                        ?>
                        <div class="tabs"><label><?= $followers ?> followers</label></div>
                        <div class="tabs"><label><?= $following ?> following</label></div>
                    </div>
                    <div class="spread"><label><?=$rows['name']; ?></label><br>
                        <p><?= $rows['bio']; ?></p>
                    </div>
                </div>
                <?php
}
?>
                <div class="highlights">
                </div>
            </div><br>
            <hr>
            <div class="minnav">
                <ul>
                    <li><a href="#" id="posts">Posts</a></li>
                    <li><a href="#" id="saved">Saved</a></li>
                </ul>
            </div>
            
           

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
    </div>

    
   <div id="settings" style="display: none;">
   <?php
$vv = mysqli_query($conn, "SELECT verified from users where users_id = '$userid'");
$vvv = mysqli_fetch_array($vv);
if ($vvv['verified'] == 0) {
    echo '<div class="set verify" id="verify">Verify me</div>
            <hr>';
} else {
}
    ?>
            
            <div class="set switch-appearance">Switch Appearance</div>
            <hr>
            <div class="set del" id="deleteBtn">Delete Account</div>
            <hr>
            <a href="logout.php"><div class="set logout">Log Out</div></a>
            <hr>
            <div class="set cancc">Cancel</div>
        </div>
        <div class="theme" style="display: none;">
    <h3>Change Appearance</h3><br>
    <small>Dark mode</small>&nbsp;&nbsp;&nbsp;
    <label class="switch">
      <input type="checkbox" id="theme-toggle">
      <span class="slider"></span>
    </label>
  </div>
        </section>
    </main>
    <script>


    // Settings button functionality
    const setBtnLight = document.querySelector('.setbtn.light');
    const setBtnDark = document.querySelector('.setbtn.dark');
    const settings = document.getElementById('settings');
    const cancelBtn = document.querySelector('.cancc');

    const showSettings = (event) => {
        settings.style.display = 'block';
        event.stopPropagation();
    };

    const hideSettings = () => {
        settings.style.display = 'none';
    };

    setBtnLight?.addEventListener('click', showSettings);
    setBtnDark?.addEventListener('click', showSettings);

    document.addEventListener('click', (event) => {
        if (!settings.contains(event.target) && event.target !== setBtnLight && event.target !== setBtnDark) {
            hideSettings();
        }
    });

    cancelBtn?.addEventListener('click', hideSettings);

    // Verification functionality
    $('#verify').click(function() {
        var userid = '<?php echo $userid; ?>';
        $.ajax({
            type: 'POST',
            url: '',
            data: { verify: 'true', userid: userid },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Show notification
    function showNotification(message) {
        var notification = $('#notification');
        notification.text(message);
        notification.show();
        setTimeout(function() {
            notification.hide();
        }, 5000);
    }

    // Delete account functionality
    $('#deleteBtn').click(function() {
        var userid = '<?php echo $userid; ?>';
        $.ajax({
            type: 'POST',
            url: 'account_actions.php',
            data: { delete: 'true', userid: userid },
            success: function(response) {
                if(response.trim() === 'success') {
                    showNotification('Account and associated posts have been successfully deleted.');
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 5000);
                } else {
                    showNotification('An error occurred while deleting the account.');
                }
            },
            error: function(xhr, status, error) {
                showNotification('An error occurred: ' + error);
            }
        });
    });
    </script>