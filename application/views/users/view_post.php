<div class="container">
    <div class="container-post" id="container-post">
    </div>
    <div class="profile-parent">
        <div class="profile ">
            <div class="row-avata">
                <div class="img-avata">
                    <img src="<?php echo $myUser["Image"]["content"] ?>" alt="">
                </div>
                <div class="name-avata"> <?php echo $myUser["User"]["username"] ?> </div>
                <div class="transfer" >
                    <a href="/users/view_profile/<?php echo $myUser["User"]["id"] ?>">View</a>
                </div>
            </div>
            <div class="suggest-add">
                <div class="suggest-text">
                    <div class="text-1">Goi y ket ban</div>
                    <a href="#" class="text-all">Xem tat ca</a>
                </div>
                <?php if (!empty($followers)): ?>
                    <?php foreach ($followers as $follower):?>
                    <div class="add">
                        <div class="img-avata">
                            <img src="<?php echo $follower["Follow"]["image"] ?>" alt="">
                        </div>
                        <div class="name-avata"><?php echo $follower["Follow"]["username"] ?></div>
                        <div class="transfer">
                            <a href="/users/view_profile/<?php echo $follower["Follow"]["user_id"] ?>">View</a>
                        </div>
                    </div>
                    <?php endforeach?>
                <?php endif ?>
<!--                <div class="add">-->
<!--                    <div class="img-avata">-->
<!--                        <img src="/public/img/avata.jpg" alt="">-->
<!--                    </div>-->
<!--                    <div class="name-avata">Lalalisa</div>-->
<!--                    <div class="transfer">-->
<!--                        <a href="#">Chuyen</a>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    window.onload = function () {
        busy = true;
        getPosts(() => {
            busy = false
        })
    }
    var busy = false;
    var page = 1;
    var limit = 2;
    window.onscroll = function (ev) {
        if (((window.innerHeight + window.scrollY) >= document.body.offsetHeight)) {
            if (busy)
                return;
            busy = true;
            // getPosts(()=>{busy=false});
            setTimeout(() => getPosts(() => {
                busy = false
            }), 1000);


        }
        return false;
    };

    function getPosts(callback = () => {
    }) {
        let xhr = new XMLHttpRequest();

        xhr.open('GET', "/v1/posts/list?limit="+limit+"&page="+page);

        // request state change event
        xhr.onreadystatechange = function () {

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
                let postsContainer = document.getElementById("container-post")
                if (data.posts) {
                    for (let postIndex in data.posts) {
                        let img;
                        if(data.posts[postIndex].post.isReact == false  ){
                            img=  '<img id="heart_img'+ data.posts[postIndex].post.id + '"onclick="reactClick(\'' + data.posts[postIndex].post.id  + '\')" class="heart" style="width: 25px; height: 25px; " src="/public/img/heart.svg" alt=""> ';
                        } else if(data.posts[postIndex].post.isReact == true){
                            img = '<img id="heart_img'+ data.posts[postIndex].post.id + '" onclick="reactClick(\'' + data.posts[postIndex].post.id   + '\')" class="heart" style="width: 25px; height: 25px; " src="/public/img/hearted.svg" alt=""> ';
                        }
                        postsContainer.innerHTML +=
                            '<div class="post">'
                            + '<div class="header-container">'
                            + '<div class="avata">'
                            + '<img style="width: 40px; height: 40px; border-radius: 50%;" src="'
                            + data.posts[postIndex].user.avatar
                            + '" alt="">'
                            + '</div>'
                            + '<div class="descrip">'
                            + '<a style="color: #000000; font-weight: bold;" href="/users/view_profile/'+ data.posts[postIndex].user.id +'">'
                            + data.posts[postIndex].user.username
                            +'</a>'
                            + '</div>'
                            + '</div>'
                            + '<div class="container-img">'
                            + '<div class="img">'
                            + '<img class="post-img" src="'
                            + data.posts[postIndex].post.image
                            + '" alt="">'
                            + '</div>'
                            + '<div class="eye"><img src="/public/img/eye-xl.png" alt=""></div>'
                            + '<div class="after">'
                            + '</div>'
                            + '</div>'
                            + '<div class="context">'
                            + '<div class="caption">'
                            + '<p>'
                            + data.posts[postIndex].post.post_description
                            + '</p>'
                            + '</div>'
                            + '<div class="action">'
                            + '<p class="number_heart" id="number_React'+data.posts[postIndex].post.id+ '">'
                            + data.posts[postIndex].post.number_react +
                            '</p>' + img  + '</div>'
                    }
                }
            } else {
                // request errord
                console.log('HTTP error', xhr.status, xhr.statusText);
            }
        };

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
            }

        }
        xhr.open('GET', "/v1/users/follow/<?php echo $user['id']?>" );
        // start request
        xhr.send();
    }

    function reactClick(id){
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            // request completed?
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                let number_React = "number_React" + id;
                let heart_img = "heart_img" + id;
                let data = JSON.parse(xhr.responseText);
                document.getElementById(number_React).innerHTML = data.count;
                if(data.isReact == false){
                    document.getElementById(heart_img).src ="/public/img/heart.svg";
                }
                else if(data.isReact == true){
                    document.getElementById(heart_img).src ="/public/img/hearted.svg";
                }
            }

        }
        xhr.open('GET', "/v1/users/react/"+ id );
        // start request
        xhr.send();
    }


</script>