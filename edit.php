<?php
include 'config.php';
include 'nav.php';
$edt ="SELECT * FROM users WHERE users_id = '$userid'";
$edtt = mysqli_query($conn, $edt);
while ($row = mysqli_fetch_array($edtt)){
    
?>
        <section class="edit">
            <div class="cphoto">
                <div class="dttt"><img src="profile/<?=$row['pic'];?>"  onerror="this.onerror=null; this.src='profile/1.jpg';">
                    <label><?= $row['username'];?></label>
                </div>
                <button id="chp">Change Photo</button>
            </div>
            <div class="bio">
                <h3>Bio</h3>
                <textarea name="bio" id="txt" placeholder="<?= htmlspecialchars($row['bio']); ?>" maxlength="100"></textarea>
                <small>0/100</small>
            </div><br><br>
            <form id="bioForm">
            <button type="button" class="edtbtn">Submit</button>
            <div class="chgpht">
        <div class="chg head">Change Profile Photo</div>
        <hr>
        <div class="chg uploadn">Upload Photo</div>
        <hr>
        <div class="chg remove">Remove Current Photo</div>
        <hr>
        <div class="chg canc">Cancel</div>
    </div>
        </section>
        <?php
}
        ?>
        