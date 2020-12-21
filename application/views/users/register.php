
<div class="container">
    <div class="logo">
        <div class="img">
            <img src="/public/img/logo.png" alt="">
        </div>

        <h1 class="title">NasaGram</h1>
    </div>
    <form method="post" action="/v1/users/register" onsubmit="return submitForm(this)">
        <div class="email">
            <label class="title">E-mail Address</label> <br>
            <input class="form-input" type="text" name="email" required><br>
            <span id="register-email-error" class="validate-error"></span><br>
        </div>
        <div class="username">
            <label class="title">Username</label> <br>
            <input class="form-input" type="text" name="username" required><br>
            <span id="register-username-error" class="validate-error"></span><br>

        </div>
        <div class="password">
            <label class="title">Password</label> <br>
            <input class="form-input" type="password" name="password" id="password" oninput="checkCPassword()" required><br>
            <span id="register-password-error" class="validate-error"></span><br>
        </div>
        <div class="confirm">
            <label class="title">Confirm Password</label> <br>
            <input class="form-input" type="password" name="" id="cpassword" oninput="checkCPassword()" required><br>
            <span id="register-cpassword-error" class="validate-error"></span><br>
        </div>
        <span id="register-error" class="validate-error"></span><br>
        <div>
            <button class="btn-hover color-1  " type="submit" value="Login">Register
        </div>
    </form>
</div>

<script type="text/javascript">
    let submitForm = (oFormElement) =>
    {
        resetErrors()
        if (document.getElementById("register-cpassword-error").textContent.length > 0) {
            oFormElement.action= "/users/register"
            return;
        }
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
        switch (xhr.status) {
            case 200:
                responseData = JSON.parse(xhr.responseText);
                if (responseData.Authorization) {
                    document.cookie = "Authorization=" + responseData.Authorization + "; path=/"
                    window.location.href = "/users/view";
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
        }
    }

    function resetErrors() {
        var errorIds = ["register-error", "register-email-error", "register-password-error", "register-username-error"]
        var index;
        for (index in errorIds) {
            // alert(document.getElementById(errorIds[index]))
            document.getElementById(errorIds[index]).textContent = ""
        }
    }

    function checkCPassword() {
        let passwordInput = document.getElementById("password")
        let cpasswordInput = document.getElementById("cpassword")
        console.log(passwordInput)
        if (passwordInput.value != cpasswordInput.value) {
            document.getElementById("register-cpassword-error").textContent = "Password not match"
        } else {
            document.getElementById("register-cpassword-error").textContent = ""
        }
    }

    function onError(resp){
        console.log("** An error occurred during the transaction");
    }

</script>
