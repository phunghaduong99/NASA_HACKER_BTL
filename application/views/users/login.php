
<div class="container ">
    <div class="form" id="form" >
        <div class="logo">
            <div class="img">
                <img src="/public/img/logo.png" alt="">
            </div>

            <h1 class="title">OutSta</h1>
        </div>
        <form  method="post">
            <div class="email ">
                <label class="title">E-mail Address</label> <br>
                <input class="form-input" type="text" name="email" required="re"><br>
            </div>
            <div class="pass">
                <label class="title">Password</label> <br>
                <input class="form-input" type="password" name="password" id=""><br>
            </div>
            <div class="check title">
                <input class="check" type="checkbox" name="isRemember" value=""> Rememeber me <br>
            </div>
            <div class="">
                <button class="btn-hover color-1  " type="submit"   value="Login">Login
            </div>
            <div class="forgot title">
                <a class="forgot" href="">Forgot Your Password</a>
            </div>

        </form>
        <div id="log"></div>
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


