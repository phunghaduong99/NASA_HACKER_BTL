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
                        <button type="button" id="follow" onclick="followClick()"><?php echo $isFollowing ?></button>
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

        <div class="model" id="myModel">

            <div class="model-content">
                <div class="model-image" id="avaPost">
                    <img src="images/post2.jpg" alt="">
                </div>
                <div class="model-infor">
                    <span class="close">&times;</span>
                    <div class="model-caption">
                        <div class="avata" id="">
                            <img src="<?php echo $image_user ?>" alt="">
                        </div>
                        <div class="name">
                            <p><?php echo $user["username"] ?></p>
                        </div>
                    </div>
                    <div class="caption-content" id="post_description">
                        <p>Tôi sẽ đi đến cuối con đường và chờ bạn ở đó. Hãy chờ tôi đừng để tôi phải tìm bạn</p>
                    </div>
                    <div class="model-action">
                        <p class="number_heart">6</p>
                        <img class="heart" style="width: 25px; height: 25px; " src="/public/img/hearted" alt="">
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    window.onload = function (){
        busy = true;
        getPosts(() =>{busy = false})
    }
    var busy = false;
    var page =1;
    var limit=9;
    window.onscroll = function(ev) {
        if(((window.innerHeight + window.scrollY) >= document.body.offsetHeight))
        {
            if(busy)
                return;
            busy = true;
            // getPosts(()=>{busy=false});
            setTimeout( ()=>  getPosts(()=>{busy=false}), 500);


        }
        return false;
    };

    function getPosts(callback=()=>{})
    {
        let xhr = new XMLHttpRequest();

        xhr.open('GET', "/v1/users/<?php echo $user['id']?>/posts?limit="+limit+"&page="+page);

        // request state change event
        xhr.onreadystatechange = function() {

            // request completed?
            if (xhr.readyState !== 4) return;

            if (xhr.status === 200) {
                // request successful - show response
                let data = JSON.parse(xhr.responseText);
                if (data.posts.length == 0) {
                    setTimeout(callback, 5000)
                } else {
                    page++
                    callback()
                }
                let postsContainer = document.getElementById("posts-container")
                if (data.posts) {
                    for (let postIndex in data.posts){
                        postsContainer.innerHTML+=
                            '<div id="myBtn" onclick="showPost(\'' + data.posts[postIndex].image  + '\', '
                            + '\'' + data.posts[postIndex].post_description  + '\'' +
                            '' +
                            ')"   class="image-child-vp ">'
                            + '<img src="'
                            + data.posts[postIndex].image
                            + '" />'
                            + '<div class="after-vp"></div>'
                            + '<div class="after-vp-tym">'
                            + '<p style="margin-right: 4px;"> '
                            + 8
                            +'</p>'
                            + '<img src="/public/img/hearted" alt="">'
                            + '</div>'
                            + ' </div>'
                    }
                }
            }
            else {
                // request errord
                console.log('HTTP error', xhr.status, xhr.statusText);
            }
        };

// start request
        xhr.send();
    }


    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];


    // Get button when click follow
    var follow = document.getElementById("follow");

    function followClick(){

        let xhr = new XMLHttpRequest();


        xhr.onreadystatechange = function() {
            // request completed?
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                document.getElementById("follow").innerHTML = data.follow;
            }

        }
        xhr.open('GET', "/v1/users/follow/<?php echo $user['id']?>" );
        // start request
        xhr.send();
    }
    // When the user clicks the button, open the modal

    function showPost(image, post_description) {
        document.getElementById("avaPost").innerHTML =
            '<img src="'
            + image
            + '" />';
        document.getElementById("post_description").innerHTML =
            '<p>'+ post_description +'</p>';

        modal.style.display = "block";
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function  () {
        modal.style.display = "none";
    }



</script>
