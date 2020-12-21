<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8"> -->
    <meta name="viewport" content="width=device-width ,height=device-height, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/add_post.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Add Post</title>
</head>
<body>
<div class="header">
    <div class="left">
        <div class="img"> <img class="img" src="/public/img/logo.png" alt=""></div>
        <a class="home" href=""> | NasaGram</a>
    </div>
    <div class="right">
        <div class=" right dropdown">
            <p class="dropbtn"> DropDown
                <i class="fa fa-caret-down"></i>
            </p>
            <div class="dropdown-content">
                <a href="#">Log Out</a>
            </div>
        </div>
    </div>


</div>
<div class="container ">
    <div class="form">
        <div class="logo">
            <div class="img">
                <img src="/public/img/logo.png" alt="">
            </div>

            <h1 class="title">NasaGram</h1>
        </div>

        <form id="login-form" method="post" action="/v1/posts/add" onsubmit="return submitForm(this)">
            <div class="caption ">
                <label class="title">Post Caption</label> <br>
                <input class="form-input" type="text" name="post_description" id="post_description"><br>
            </div>
            <div class="image">
                <label class="title">Post Image</label> <br>
            </div>
            <div class="check title">
                <input type="file" id="image" name="image" ><br>
            </div>
            <div class="">
                <button class="btn-hover color-1  " type="submit" value="Login">Add New Post
            </div>

        </form>
    </div>

</div>

<script type="text/javascript">
    function submitForm(oFormElement)
    {
        // resetErrors();
        // oFormElement.preventDefault();
        var xhr = new XMLHttpRequest();
        xhr.onload = function(){ onSuccess(xhr); } // success case
        xhr.onerror = function(){ onError(xhr); } // failure case
        xhr.open (oFormElement.method, oFormElement.action, true);
        xhr.send (new FormData (oFormElement));
        return false;
    }

    function onSuccess(xhr)
    {
        console.log(xhr)

        switch (xhr.status) {
            case 200:
                responseData = JSON.parse(xhr.responseText);
                console.log(responseData)
                window.location.href = "/users/view_profile";
                break;
            case 401:
                responseData = JSON.parse(xhr.responseText);
                break;
        }
    }
    function resetErrors() {
        var errorIds = ["login-error", "login-email-error", "login-password-error"]
        var index;
        for (index in errorIds) {
            document.getElementById(errorIds[index]).textContent = ""
        }
    }

    function onError(resp){
        window.location.href = "/users/view";
        console.log("** An error occurred during the transaction");
    }

</script>
</body>

</html>