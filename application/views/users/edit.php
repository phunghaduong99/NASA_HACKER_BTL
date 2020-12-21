<div class="container-edit ">
    <div class="form-edit">
        <div class="logo-edit">
            <div class="img-edit">
                <img class="edit" src="/public/img/logo.png" alt="">
            </div>

            <h1 class="title-edit">NasaGram</h1>
        </div>

        <form id="login-form" method="post" action="/v1/users/edit" onsubmit="return submitForm(this)" class="edit-edit">
            <div class="title-edit ">
                <label class="title-edit">Title</label> <br>
                <input class="form-input-edit" type="text" name="profile_title" value="<?php echo  $cur_user["profile_title"] ?>" id="profile_title" ><br>
            </div>
            <div class="description-edit">
                <label class="title-edit">Description</label> <br>
                <input class="form-input-edit" type="text" name="profile_description" value="<?php echo  $cur_user["profile_description"] ?>" id="profile_description"><br>
            </div>
            <div class="url-edit">
                <label class="title-edit">URL</label> <br>
                <input class="form-input-edit" type="url" name="profile_url" value="<?php echo  $cur_user["profile_url"] ?>" id="profile_url"><br>
            </div>
            <div class="image-edit">
                <label class="title-edit">Profile Image</label> <br>

            </div>

            <div class="check-edit title-edit">
                <input type="file" id="image" name="image"><br>
            </div>
            <div class="">
                <button class="btn-hover-edit color-1-edit" type="submit" value="Login">Save Profile
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
        window.location.href = "/users/view_profile";
        console.log("** An error occurred during the transaction");
    }

</script>