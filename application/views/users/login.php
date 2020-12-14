<?php include ("")?>
<div class="container-login">
    <div class="form-login">
        <div class="logo-login">
            <div class="img-login">
                <img src="/public/img/logo.png" alt="">
            </div>

            <h1 class="title-login">OutSta</h1>
        </div>
        <form>
            <div class="email-login ">
                <label class="title-login">E-mail Address</label> <br>
                <input class="form-input-login" type="text" name="" required="re"><br>
            </div>
            <div class="pass-login">
                <label class="title-login">Password</label> <br>
                <input class="form-input-login" type="password" name="" id=""><br>
            </div>
            <div class="check-login title-login">
                <input class="check-login" type="checkbox" name="" value=""> Rememeber me <br>
            </div>
            <div class="">
                <button class="btn-hover-login color-1-login  " type="submit" value="Login">Login
            </div>
            <div class="forgot-login title-login">
                <a class="forgot-login" href="">Forgot Your Password</a>
            </div>

        </form>
    </div>
</div>

    <script type="text/javascript">
        // const log = document.getElementById('log');
        document.getElementById('form').onsubmit = function (event) {
            event.preventDefault();
            var xhttp = new XMLHttpRequest();
            var xhttp_self = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // document.getElementById("log").innerHTML = this.responseText;
                    console.log( this.responseText);
                }
            };
            var   myURL ="/users/register";
            xhttp.open("POST", myURL, true);
            xhttp.send();
        }

    </script>


    </body>
    </html>