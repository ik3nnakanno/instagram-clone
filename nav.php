<?php
include 'config.php';
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 5);

$session_lifetime = 60 * 60 * 24 * 5;
session_set_cookie_params($session_lifetime);
session_start();
$userid = $_SESSION['id'] ?? 0;
if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="view.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="post.css">
    <link rel="stylesheet" href="search.css">
    <link rel="stylesheet" href="edit.css">
    <script src="search.js" defer></script>
    <link rel="icon" href="images/icons8-instagram-480.png">
    <script src="upload.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="follow.js" defer></script>
    <script src="vview.js" defer></script>
    <script src="like.js"></script>
    <script src="mlikes.js"></script>
    <script src="main.js" defer></script>
    <script src="update.js"></script>
    <script src="comment.js"></script>
    <script src="book.js"></script>
    <script src="theme.js" defer></script>
    <!-- <script src="load.js"></script> -->

</head>
<body><div class="upnav">
<a href="index.php">
        <img class="dark" src="images/instagram-line (1).png">
        <img class="light" src="images/instagram-line.png"></a>
         <div class="left">
         <a href="coming.soon.php">
                <img class="light" src="images/heart-line.png" alt="">
                <img class="dark" src="images/heart-line (1).png" alt="">
                <h3>Notifications</h3>
            </a>
         <a href="coming.soon.php">
                <img class="light" src="images/messenger-line.png" alt="">
                <img class="dark" src="images/messenger-line (1).png" alt="">
                <h3>Messages</h3>
            </a>
         </div>
</div>
<div class="container">
    <div class="navi">
        <div class="ig">
            <h1><a href="index.php">Instagram</a></h1>
        </div>
        <nav>
            <a href="index.php">
                <img class="light" src="images/home-5-fill.png" alt="">
                <img class="dark" src="images/home-5-fill (1).png" alt="">
                <h3>Home</h3>
            </a>
            <a class="search">
                <img class="light" src="images/search-line.png" alt="">
                <img class="dark" src="images/search-line (1).png" alt="">
                <h3>Search</h3>
                <div class="searchbox"></div>
            </a>
            <a href="coming.soon.php">
                <img class="light" src="images/compass-3-line.png" alt="">
                <img class="dark" src="images/compass-3-line (1).png" alt="">
                <h3>Explore</h3>
            </a><a href="coming.soon.php">
                <img class="light" src="images/clapperboard-line.png" alt="">
                <img class="dark" src="images/clapperboard-line (1).png" alt="">
                <h3>Reels</h3>
            </a><a href="coming.soon.php" class="nulll">
                <img class="light" src="images/messenger-line.png" alt="">
                <img class="dark" src="images/messenger-line (1).png" alt="">
                <h3>Messages</h3>
            </a><a href="coming.soon.php" class="nulll">
                <img class="light" src="images/heart-line.png" alt="">
                <img class="dark" src="images/heart-line (1).png" alt="">
                <h3>Notifications</h3>
            </a><a class="create" href="#">
                <img class="light" src="images/add-box-line.png" alt="">
                <img class="dark" src="images/add-box-line (1).png" alt="">
                <h3>Create</h3>
</a><a href="profile.php">
            <?php
                $pic = "SELECT * FROM users WHERE users_id = '$userid'";
                $ppics = mysqli_query($conn, $pic);
                while ($pp = mysqli_fetch_array($ppics)){
                ?>
                <img class="pro" src="profile/<?= $pp['pic'];?>" onerror="this.onerror=null; this.src='profile/1.jpg';">
                <h3>Profile</h3>
            </a>
            <label class="nulll">
                <img class="light" src="images/menu-line1.png" alt="">
                <img class="dark" src="images/menu-line.png" alt="">
                <h3>More</h3>
            </label>
        </nav>
    </div>
    <div class="searchnav hidden">
        <nav><a href="index.php"><img class="dark" src="images/instagram-line (1).png"></a>
        <a href="index.php"><img class="light" src="images/instagram-line.png"></a>
            <a href="index.php">
                <img src="images/home-5-fill.png" alt="">
                <img class="dark" src="images/home-5-fill (1).png" alt="">
            </a>
            <a class="searchh">
                <img src="images/search-line.png" alt="">
                <img class="dark" src="images/search-line (1).png" alt="">
            </a>
            <a href="coming.soon.php">
                <img src="images/compass-3-line.png" alt="">
                <img class="dark" src="images/compass-3-line (1).png" alt="">
            </a><a href="coming.soon.php">
                <img src="images/clapperboard-line.png" alt="">
                <img class="dark" src="images/clapperboard-line (1).png" alt="">
            </a><a href="coming.soon.php">
                <img src="images/messenger-line.png" alt="">
                <img class="dark" src="images/messenger-line (1).png" alt="">
            </a><a href="coming.soon.php">
                <img src="images/heart-line.png" alt="">
                <img class="dark" src="images/heart-line (1).png" alt="">
            </a><a class="create" href="#">
                <img src="images/add-box-line.png" alt="">
                <img class="dark" src="images/add-box-line (1).png" alt="">
            </a><a href="profile.php">
            
                <img class="pro" src="profile/<?= $pp['pic'];?>" onerror="this.onerror=null; this.src='profile/1.jpg';">
            <?php
            } 
            ?>
            </a>
            <a href="">
                <img src="images/menu-line1.png" alt="">
                <img class="dark" src="images/menu-line.png" alt="">
            </a>
        </nav>
        <div id="searchbox">
            <h2><b>Search</b></h2>
            <input type="text" name="search" placeholder="search" id="search">
            <br>
            <div id="searchview">
                    
                </div>
        </div>
    </div>
</div>
<div id="createbox">
            <img class="xx light" id="xx" src="images/close-line.png" alt="">
            <img class="xxs dark" id="xxs" src="images/close-line (1).png" alt="">
            <div class="drag" id="drop">
    <div class="head">
        <label>Create new post</label>
    </div>
    <div class="drop-zone body" id="drop-zone">
        <img src="images/image-2-line.png" alt="">
        <label>Drag photos here</label><br>
        <button>Select from device</button>
    </div>
</div>
<input type="file" id="file-input" name="image" style="display:none;" multiple>

<div class="share" id="share">
    <div class="head">
        <label for="">Create a new post</label>
        <button id="submit">Share</button>
    </div>
    <div class="pppp">
        <img id="preview" src="" alt="Image Preview">
        <div class="txt">
            <textarea name="caption" id="caption" placeholder="Write a caption..."></textarea>
        </div>
    </div>
</div>
        </div>
<label id="notification" class="noti"></label>

