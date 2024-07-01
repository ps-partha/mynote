
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
      rel="stylesheet"
    />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <title>Login</title>
  </head>
  <body class="bg-gray-200">
    <div class="contanear">
      <div class="login-section" id="UserLoginPage">
        <h3>User Login</h3>
        <form id="UserLoginForm">
          <div class="errorbox">
                <span id="Error" class="error"></span>
            </div>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            required
          />
          <br />
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter your password"
            required
          />
          <br />
          <div class="rememberME">
            <input type="checkbox" name="remember-me" id="remember-me" value="checked" />
            <label for="checkbox">Remember Me</label>
            <a href="forgot_password.php" class="forgotPass">Forget Password</a>
          </div>
          <div class="button">
            <button type="submit" class="btn">Log In</button>
          </div>
          <div class="accCreate">
            <a href="sign-up.php?status=signup"
              >Don't have an account? <span class="signBtn">Sign up</span></a
            >
          </div>
        </form>
      </div>
      <div class="login-section" id="AdminLoginPage">
        <h3>Admin Login</h3>
        <form id="AdminLoginForm">
          <div class="errorbox">
                <span id="error" class="error"></span>
            </div>
          <input
            type="email"
            name="email"
            id="a-email"
            placeholder="Enter your email"
            required
          />
          <br />
          <input
            type="password"
            name="password"
            id="a-password"
            placeholder="Enter your password"
            required
          />
          <br />
          <div class="rememberME">
            <input type="checkbox" name="remember_me" id="remember_me" value="checked" />
            <label for="checkbox">Remember Me</label>
            <a href="forgot_password.php" class="forgotPass">Forget Password</a>
          </div>
          <div class="button">
            <button type="submit" class="btn">Log In</button>
          </div>
          <div class="accCreate">
            <a href="sign-up.php?status=signup"
              >Don't have an account? <span class="signBtn">Sign up</span></a
            >
          </div>
        </form>
      </div>
    </div>
  </body>
  <script>
    
  $(document).ready(function(){
    $("email").click(function(){
        $(".error").css("display","none");
    });
    $("password").click(function(){
        $(".error").css("display","none");
    });

    var currentUrl = window.location.href;
    var urlParams = getURLParams(currentUrl);
    if(urlParams['status'] === 'userlogin'){
        $("#UserLoginPage").show();
        $("#AdminLoginPage").hide();
        AutouserLogin();
    } else if(urlParams['status'] === 'adminlogin'){
        $("#AdminLoginPage").show();
        $("#UserLoginPage").hide();
        AutoadminLogin();
    } else {
        $("#UserLoginPage").show();
        $("#AdminLoginPage").hide(); 
    }

    FormSubmit();
    LoginFormSubmit();
});

function getURLParams(url) {
    var params = {};
    var urlParts = url.split("?");
    if (urlParts.length > 1) {
        var queryString = urlParts[1];
        var paramPairs = queryString.split("&");
        for (var i = 0; i < paramPairs.length; i++) {
            var pair = paramPairs[i].split("=");
            var key = pair[0];
            var value = pair.length > 1 ? pair[1] : null;
            key = decodeURIComponent(key);
            value = decodeURIComponent(value);
            params[key] = value;
        }
    }
    return params;
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function FormSubmit() {
    $('#AdminLoginForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'AdminLogin.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: $('#a-email').val(),
                password: $('#a-password').val(),
                'remember_me': $('#remember_me').is(':checked') ? 'checked' : ''
            },
            success: function(response) {
                if (response.status === "success") {
                    $('.error').css("color","green");
                    $('.error').html(response.message);
                    if(response.checkbox === "checked") {
                        setCookie("admin_email", $("#a-email").val(), 7);
                        setCookie("admin_password", $("#a-password").val(), 7);
                    }
                    setTimeout(function() {
                        window.location.href = "http://localhost/partha-sarker/admin.php";
                    }, 1000);
                } else {
                    $('.error').css("color","red");
                    $('.error').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);
                $('.error').html('An error occurred during registration.');
            }
        });
    });
}

function LoginFormSubmit() {
    $('#UserLoginForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'login.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: $('#email').val(),
                password: $('#password').val(),
                'remember-me': $('#remember-me').is(':checked') ? 'checked' : ''
            },
            success: function(response) {
                if (response.status === "success") {
                    $('#Error').css("color","green");
                    $('#Error').html(response.message);
                    if(response.checkbox === "checked") {
                        setCookie("user_email", $("#email").val(), 7);
                        setCookie("user_password", $("#password").val(), 7);
                    }
                    setTimeout(function() {
                        window.location.href = "http://localhost/partha-sarker/dashboard.php";
                    }, 1000);
                } else if(response.status === "inactive") {
                    $('#Error').css("color","red");
                    $('#Error').html(response.message);
                } else {
                    $('#Error').css("color","red");
                    $('#Error').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);
                $('#Error').html('An error occurred during registration.');
            }
        });
    });
}

function AutoadminLogin() {
    const adminEmail = getCookie('admin_email');
    const adminPassword = getCookie('admin_password');
    if (adminEmail && adminPassword) {
        $('#a-email').val(adminEmail);
        $('#a-password').val(adminPassword);
        $('#remember_me').prop('checked', true);
        $('#AdminLoginForm').submit();
    }
}

function AutouserLogin() {
    const userEmail = getCookie('user_email');
    const userPassword = getCookie('user_password');
    if (userEmail && userPassword) {
        $('#email').val(userEmail);
        $('#password').val(userPassword);
        $('#remember-me').prop('checked', true);
        $('#UserLoginForm').submit();
    }
}

  </script>
</html>
