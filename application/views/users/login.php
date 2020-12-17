<?php include ("")?>
<div class="container-login">
    <div class="form-login">
        <div class="logo-login">
            <div class="img-login">
                <img src="/public/img/logo.png" alt="">
            </div>

            <h1 class="title-login">OutSta</h1>
        </div>
        <form id="login-form" method="post" action="/v1/users/login" onsubmit="return submitForm(this)">
            <div class="email-login ">
                <label class="title-login">E-mail Address</label> <br>
                <input class="form-input-login" type="email" name="email" required="re"><br>
                <span id="login-email-error" class="validate-error"></span><br>
            </div>
            <div class="pass-login">
                <label class="title-login">Password</label> <br>
                <input class="form-input-login" type="password" name="password"><br>
                <span id="login-password-error" class="validate-error"></span><br>
            </div>
            <div class="check-login title-login">
                <input class="check-login" type="checkbox" name="" value=""> Rememeber me <br>
            </div>

            <span id="login-error" class="validate-error"></span><br>
            <div>
                <button class="btn-hover-login color-1-login  " type="submit" value="Login">Login
            </div>
            <div class="forgot-login title-login">
                <a class="forgot-login" href="">Forgot Your Password</a>
            </div>

        </form>
    </div>
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
                        window.location.href = "/users/view";
                    } else {
                        alert("Bad response (no token)");
                    }
                    break;
                case 403:
                    responseData = JSON.parse(xhr.responseText);
                    for ( i in responseData.validateError){
                        document.getElementById("login-" + i + "-error").textContent=responseData.validateError[i]
                    }
                    break;
                case 401:
                    responseData = JSON.parse(xhr.responseText);
                    document.getElementById("login-error").textContent=responseData.loginError
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
            console.log("** An error occurred during the transaction");
        }
        document.getElementById



    </script>


    </body>
    </html>