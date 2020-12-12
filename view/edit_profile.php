<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit Profile</title>
</head>

<body>
<div class="header">
    <div class="left">
        <div class="img"> <img class="img" src="images/logo.png" alt=""></div>
        <a class="home" href=""> | OUTstagram</a>
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
                <img src="images/logo.png" alt="">
            </div>

            <h1 class="title">OutSta</h1>
        </div>

        <form>
            <div class="title ">
                <label class="title">Title</label> <br>
                <input class="form-input" type="text" name="" id=""><br>
            </div>
            <div class="description">
                <label class="title">Description</label> <br>
                <input class="form-input" type="text" name="" id=""><br>
            </div>
            <div class="url">
                <label class="title">URL</label> <br>
                <input class="form-input" type="url" name="" id=""><br>
            </div>
            <div class="image">
                <label class="title">Profile Image</label> <br>

            </div>

            <div class="check title">
                <input type="file" id="myfile" name="" ><br>
            </div>
            <div class="">
                <button class="btn-hover color-1  " type="submit" value="Login">Save Profile
            </div>


        </form>
    </div>

</div>


</body>

</html>