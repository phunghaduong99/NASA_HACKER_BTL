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
                    <p id="numberOfFollowers"><?php echo count($followers) ?> followers</p>
                </div>
                <div class="following-vp text_vp">
                    <p href="#"><?php echo count($followings) ?> following</p>
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
                        <p class="number_heart" id="number_heart">6</p>

                        <div id="isReact" style="height: 25px; ">
                            <img class="heart" style="width: 25px; height: 25px; " src="/public/img/hearted" alt="">
                        </div>

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
    var limit=5;
    window.onscroll = function(ev) {
        if(((window.innerHeight + window.scrollY) >= document.body.offsetHeight))
        {
            if(busy)
                return;
            busy = true;
            // getPosts(()=>{busy=false});
            setTimeout( ()=>  getPosts(()=>{busy=false}), 1000);


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
                            '<div id="myBtn" onclick="showPost(\'' + data.posts[postIndex].image  + '\'' +
                            ', ' + '\'' + data.posts[postIndex].post_description  + '\'' +
                            ', ' + '\'' + data.posts[postIndex].number_react  + '\'' +
                            ', ' + '\'' + data.posts[postIndex].isReact  + '\'' +
                            ', ' + '\'' + data.posts[postIndex].id  + '\'' +
                            ')"   class="image-child-vp ">'
                            + '<img class="post-img" src="'
                            + data.posts[postIndex].image
                            + '" />'
                            + '<div class="after-vp"></div>'
                            + '<div class="after-vp-tym">'
                            + '<p style="margin-right: 4px;" id="number_React'+  data.posts[postIndex].id+'"> '
                            + data.posts[postIndex].number_react
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


    // When the user clicks the button, open the modal

    function showPost(image, post_description, number_react, isReact, id) {
        document.getElementById("avaPost").innerHTML =
            '<img src="'
            + image
            + '" />';
        document.getElementById("post_description").innerHTML =
            '<p>'+ post_description  + '</p>';
        document.getElementById("number_heart").innerHTML =  number_react ;
        if(isReact == "false")
            document.getElementById("isReact").innerHTML =
                '<img id="heart_img" onclick="reactClick(\'' + id  + '\')" class="heart" style="width: 25px; height: 25px; " src="/public/img/heart" alt=""> ';
        else if(isReact == "true"){
            document.getElementById("isReact").innerHTML =
                '<img id="heart_img" onclick="reactClick(\'' + id  + '\')" class="heart" style="width: 25px; height: 25px; " src="/public/img/hearted" alt=""> ';
        }
        modal.style.display = "block";
    }


    function reactClick(id){
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            // request completed?
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                let str = "number_React" + id;
                let data = JSON.parse(xhr.responseText);
                document.getElementById("number_heart").innerHTML = data.count;
                document.getElementById(str).innerHTML = data.count;
                if(data.isReact == false){
                    document.getElementById("heart_img").src ="/public/img/heart";
                }
                else if(data.isReact == true){
                    document.getElementById("heart_img").src ="/public/img/hearted";
                }
            }

        }
        xhr.open('GET', "/v1/users/react/"+ id );
        // start request
        xhr.send();
    }
    function followClick(){

        let xhr = new XMLHttpRequest();


        xhr.onreadystatechange = function() {
            // request completed?
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                document.getElementById("follow").innerHTML = data.follow;
                document.getElementById("numberOfFollowers").innerHTML = data.count + " followers";
            }

        }
        xhr.open('GET', "/v1/users/follow/<?php echo $user['id']?>" );
        // start request
        xhr.send();
    }
    span.onclick = function  () {
        modal.style.display = "none";
    }



</script>
