
<div class="container">
    <div class="logo">
        <div class="img">
            <img src="/public/img/logo.png" alt="">
        </div>

        <h1 class="title">NasaGram</h1>
    </div>
    <form method="post" onsubmit="return submitForm(this)" action="/v1/users/register">
        <div class="name ">
            <label class="title">Name</label> <br>
            <input class="form-input" type="text" name="" id=""><br>
        </div>
        <div class="email">
            <label class="title">E-mail Address</label> <br>
            <input class="form-input" type="email" name="email" required="re"><br>
            <span id="register-email-error" class="validate-error"></span><br>
        </div>
        <div class="username">
            <label class="title">Username</label> <br>
            <input class="form-input" type="text" name="username" id=""><br>
        </div>
        <div class="password">
            <label class="title">Password</label> <br>
            <input class="form-input" type="password" name="password" id=""><br>
            <span id="register-password-error" class="validate-error"></span><br>
        </div>
        <div class="confirm">
            <label class="title">Confirm Password</label> <br>
            <input class="form-input" type="password" name="" required="re" id=""><br>
        </div>
        <span id="register-error" class="validate-error"></span><br>
        <div>
            <button class="btn-hover color-1  " type="submit" value="Login">Register
        </div>
    </form>
</div>

<script type="text/javascript">
    function submitForm(oFormElement)
    {
        resetErrors();
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
                if (responseData.Authorization) {
                    document.cookie = "Authorization=" + responseData.Authorization + "; path=/"
                    window.location.href = "/users/login";
                } else {
                    alert("Bad response (no token)");
                }
                break;
            case 403:
                responseData = JSON.parse(xhr.responseText);
                for ( i in responseData.validateError){
                    document.getElementById("register-" + i + "-error").textContent=responseData.validateError[i]
                }
                break;
            case 401:
                responseData = JSON.parse(xhr.responseText);
                document.getElementById("register-error").textContent=responseData.registerError
                break;
        }
    }

    function resetErrors() {
        var errorIds = ["register-error", "register-email-error", "register-password-error"]
        var index;
        for (index in errorIds) {
            document.getElementById(errorIds[index]).textContent = ""
        }
    }

    function onError(resp){
        console.log("** An error occurred during the transaction");
    }
    document.getElementById



</script>
