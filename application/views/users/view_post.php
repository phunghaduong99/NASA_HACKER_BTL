<div class="container">
    <div class="container-post" id="container-post">
    </div>
    <div class="profile-parent">
        <div class="profile ">
            <div class="row-avata">
                <div class="img-avata">
                    <img src="/public/img/avata.jpg" alt="">
                </div>
                <div class="name-avata">Lalalisa</div>
                <div class="transfer">
                    <a href="#">Chuyen</a>
                </div>
            </div>
            <div class="suggest-add">
                <div class="suggest-text">
                    <div class="text-1">Goi y ket ban</div>
                    <a href="#" class="text-all">Xem tat ca</a>
                </div>
                <div class="add">
                    <div class="img-avata">
                        <img src="/public/img/avata.jpg" alt="">
                    </div>
                    <div class="name-avata">Lalalisa</div>
                    <div class="transfer">
                        <a href="#">Chuyen</a>
                    </div>
                </div>
                <div class="add">
                    <div class="img-avata">
                        <img src="/public/img/avata.jpg" alt="">
                    </div>
                    <div class="name-avata">Lalalisa</div>
                    <div class="transfer">
                        <a href="#">Chuyen</a>
                    </div>
                </div>
                <div class="add">
                    <div class="img-avata">
                        <img src="/public/img/avata.jpg" alt="">
                    </div>
                    <div class="name-avata">Lalalisa</div>
                    <div class="transfer">
                        <a href="#">Chuyen</a>
                    </div>
                </div>
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
            }), 500);


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
                        postsContainer.innerHTML +=
                            '<div class="post">'
                            + '<div class="header-container">'
                            + '<div class="avata">'
                            + '<img src="'
                            + data.posts[postIndex].user.avatar
                            + '" alt="">'
                            + '</div>'
                            + '<div class="descrip">'
                            + '<b>'
                            + data.posts[postIndex].user.username
                            +'</b>'
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
                            + '<p class="number_heart">6</p>'
                            + '<img class="heart" src="/public/img/icons8-heart-26.png" alt="">'
                            + '</div>'
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
</script>




<script type="text/javascript">
    window.onload = function (){
        busy = true;
        getPosts(() =>{busy = false})
    }
    var busy = false;
    var page =1;
    var limit=3;
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

        xhr.open('GET', "/v1/users/vGetHomePosts?limit="+limit+"&page="+page);

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




</script>