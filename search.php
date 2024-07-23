<?php
include 'config.php';
session_start();
$userid = $_SESSION['id'] ?? 0;

function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$search = validate($_POST['search']);

$sql = "SELECT * FROM users WHERE name LIKE '%$search%' OR username LIKE '%$search%'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <a href="user.php?p=<?= $row['username']; ?>">
            <div class='searchresult'>
            <a href="user.php?p=<?= $row['username']; ?>">
            <img class="srcpfp" src="profile/<?= $row['pic'] ?>"  onerror="this.onerror=null; this.src='profile/1.jpg';"></a>
                <div class="ttxx"><b>
                <a href="user.php?p=<?= $row['username']; ?>">
                <label><?= $row['username'] ?>
                    <?php if($row['verified'] == 1) { ?>
                        &nbsp;<img src="images/verified.png" width="14px" alt="">
                    <?php } ?>
                </label><br>
                <?php if ($row['users_id'] == $userid) {
                    echo 'You';
                } else { ?>
                    <?= $row['name'] ?>
                <?php } ?>
                </a></b></div>
            </div>
        <?php
    }
} else {
    echo "No data found";
}
?>
