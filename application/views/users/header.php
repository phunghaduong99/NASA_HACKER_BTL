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
            <form class="search">
                <input type="text" name="search" placeholder="Search...">
            </form>
        </div>
        <div class="right">
            <div class=" right dropdown">
                <div class="dropbtn">
                    <p onclick="goToProfile()">lalalisa</p>
                    <img src="/public/img/icon.jpg" alt="">
                </div>
                <div class="dropdown-content">
                    <a href="#" onclick="logout()">Log Out</a>
                </div>
            </div>
        </div>
    </div>

</div>

