<div class="container-vp">
    <div class="information-vp">
        <div class="avata-vp">
            <img src="<?php echo $image_user?>" alt="">
        </div>

        <div class="text-vp">
            <div class="row-vp row1-vp">

                <p class=" nick-name-vp"><?php echo $user["username"]?> </p>
                <?php if ($isEdit == false): ?>
                <div class="unfollow-vp">
                    <button type="submit-vp"><?php echo $isFollowing?></button>
                </div>
                <?php endif?>
            </div>
            <?php if ($isEdit == true): ?>
            <div class=" row-vp edit-profile-vp">
                <a href="/users/edit">Edit Profile</a>
            </div>
            <?php endif?>
            <div class="row-vp number-vp ">
                <div class="post_number-vp text_vp">
                    <p><?php echo count($posts)?> posts</p>
                </div>
                <div class="followers-vp text_vp">
                    <p><?php echo count($followers)?> followers</p>
                </div>
                <div class="following-vp text_vp">
                    <a href="#"><?php echo count($followings)?> following</a>
                </div>
            </div>
            <div class="row-vp des-nick-vp"><b><?php echo $user["profile_title"]?></b></div>
            <div class="row-vp des-decrip-vp">
                <a href="https://www.facebook.com/profile.php?id=100015874368540"><?php echo $user["profile_url"]?></a>
            </div>
            <div class="row-vp description-vp">

                <p><?php echo $user["profile_description"]?></p>

            </div>
        </div>
        <?php if ($isEdit == true): ?>
        <div class="post-vp">
            <a href="/posts/add">Add New Post</a>
        </div>
        <?php endif?>
    </div>
    <div class="image-vp">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post):?>
                <div class="image-child-vp ">
                    <img src="<?php echo $post["Post"]["image"]?>" />
                    <div class="after-vp"></div>
                </div>
            <?php endforeach?>
<!--            <div class="image-child-vp ">-->
<!--                <img src="/public/img/lisa5.jpg" />-->
<!--                <div class="after-vp"></div>-->
<!--            </div>-->
        <?php endif?>
    </div>
</div>