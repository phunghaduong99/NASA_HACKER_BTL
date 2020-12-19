<div class="container-vp">
    <div class="information-vp">
        <div class="avata-vp">
            <img src="<?php echo $image_user ?>" alt="">
        </div>

        <div class="text-vp">
            <div class="row-vp row1-vp">

                <p class=" nick-name-vp"><?php echo $user["username"] ?> </p>
                <?php if ($isEdit == false): ?>
                    <div class="unfollow-vp">
                        <button type="submit-vp"><?php echo $isFollowing ?></button>
                    </div>
                <?php endif ?>
            </div>
            <?php if ($isEdit == true): ?>
                <div class=" row-vp edit-profile-vp">
                    <a href="/users/edit">Edit Profile</a>
                </div>
            <?php endif ?>
            <div class="row-vp number-vp ">
                <div class="post_number-vp text_vp">
                    <p><?php echo count($posts) ?> posts</p>
                </div>
                <div class="followers-vp text_vp">
                    <p><?php echo count($followers) ?> followers</p>
                </div>
                <div class="following-vp text_vp">
                    <a href="#"><?php echo count($followings) ?> following</a>
                </div>
            </div>
            <div class="row-vp des-nick-vp"><b><?php echo $user["profile_title"] ?></b></div>
            <div class="row-vp des-decrip-vp">
                <a href="https://www.facebook.com/profile.php?id=100015874368540"><?php echo $user["profile_url"] ?></a>
            </div>
            <div class="row-vp description-vp">

                <p><?php echo $user["profile_description"] ?></p>

            </div>
        </div>
        <?php if ($isEdit == true): ?>
            <div class="post-vp">
                <a href="/posts/add">Add New Post</a>
            </div>
        <?php endif ?>
    </div>
    <div class="image-vp" id="posts-container">
        <!--            <div class="image-child-vp ">-->
        <!--                <img src="/public/img/lisa5.jpg" />-->
        <!--                <div class="after-vp"></div>-->
        <!--            </div>-->
    </div>
</div>

<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="model" id="myModel">
            <div class="model-content">
                <div class="model-image">
                    <img src="images/post2.jpg" alt="">
                </div>
                <div class="model-infor">
                    <div class="model-caption">
                        <div class="avata">
                            <img src="images/avata1.jpg" alt="">
                        </div>
                        <div class="name">
                            <p>TARA VILLA</p>
                        </div>
                    </div>
                    <div class="caption-content">
                        <p>Tôi sẽ đi đến cuối con đường và chờ bạn ở đó. Hãy chờ tôi đừng để tôi phải tìm bạn</p>
                    </div>
                    <div class="model-action">
                        <p class="number_heart">6</p>
                        <img class="heart" src="images/icons8-heart-26.png" alt="">

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    window.onload = function() {
        sessionStorage.setItem("user_profile_post_page", 0)
    }
    window.onscroll = function(ev) {
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
            getPosts(parseInt(sessionStorage.getItem("user_profile_post_page"))+1)
        }
    };
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }


    function getPosts(page=1, limit = 9)
    {
        if (page<= parseInt(sessionStorage.getItem("user_profile_post_page"))) {
            return;
        }
        let xhr = new XMLHttpRequest();

        xhr.open('GET', "/v1/users/<?php echo $user['id']?>/posts?limit="+limit+"&page="+page);

        // request state change event
        xhr.onreadystatechange = function() {

            // request completed?
            if (xhr.readyState !== 4) return;

            if (xhr.status === 200) {
                // request successful - show response
                let data = JSON.parse(xhr.responseText);
                sessionStorage.setItem("user_profile_post_page", page)
                // console.log(data)
                let postsContainer = document.getElementById("posts-container")
                if (data.posts) {
                    for (let postIndex in data.posts){
                        postsContainer.innerHTML+=
                            '<div id="myBtn" class="image-child-vp ">'
                            + '<img src="'
                            + data.posts[postIndex].image
                            + '" />'
                            + '<div class="after-vp"></div>'
                            + '<div class="after-vp-tym">'
                            + '<p>'
                            + 8
                            +'</p>'
                            + '<img src="images/icons8-heart-26.png" alt="">'
                            + '</div>'
                            + ' </div>'
                    }
                }
            }
            else {
                // request error
                console.log('HTTP error', xhr.status, xhr.statusText);
            }
        };

// start request
        xhr.send();
    }
</script>
