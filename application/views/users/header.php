<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/header_user.css">
      <link rel="stylesheet" href="/public/css/view_profile.css">
    <link rel="stylesheet" href="/public/css/view_post.css">
    <link rel="stylesheet" href="/public/css/edit.css">
    <script src="/public/js/header.js"></script>
    <link rel="icon" type="image/png" href="/public/img/logo.png">
    <title></title>

    <script>
        function showResult(str) {
            if (str.length==0) {
                document.getElementById("livesearch").innerHTML="";
                document.getElementById("livesearch").style.border="0px";
                return;
            }
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    document.getElementById("livesearch").innerHTML=JSON.parse(this.responseText).response;
                    document.getElementById("livesearch").style.border="1px solid #A5ACB2";
                    document.getElementById("livesearch").style.borderRadius="5px";
                }
            }
            xmlhttp.open("GET","/v1/users/search?q="+str,true);
            xmlhttp.send();
        }
    </script>
</head>

<body>
<div class="header">
    <div class="header-container">
        <div class="left">
            <div class="img"> <img class="img" src="/public/img/logo.png" alt=""></div>
            <div class="center">
                <p>|</p>
            </div>
            <a class="home btn btn-2 color-green" href="/users/view">NasaGram</a>
        </div>
        <div class="center-search">
            <div class="search">
                <input type="text" size="30" onautocomplete="false" placeholder="Search..."  onkeyup="showResult(this.value)">
                <div id="livesearch"></div>
            </div>
        </div>
        <div class="right">
            <div class=" right dropdown">
                <div class="dropbtn">
                    <p onclick="goToProfile()" style="margin-right: 20px"><?php echo $username ?></p>
                    <img src="/public/img/icon.jpg" alt="">
                </div>
                <div class="dropdown-content">
                    <a href="#" onclick="logout()">Log Out</a>
                </div>
            </div>
        </div>
    </div>

</div>
