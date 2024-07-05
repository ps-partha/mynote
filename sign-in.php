<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="./assets/css/style.css" />
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
    <div class="container">
      <div class="login-section" id="UserLoginPage">
        <h3>Login</h3>
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
            <input
              type="checkbox"
              name="remember-me"
              id="remember-me"
              value="checked"
            />
            <label for="remember-me">Remember Me</label>
            <a href="./php/forgot_password" class="forgotPass"
              >Forget Password</a
            >
          </div>
          <div class="button">
            <button type="submit" class="btn">Log In</button>
          </div>
          <div class="accCreate">
            <a href="./sign-up?status=signup"
              >Don't have an account? <span class="signBtn">Sign up</span></a
            >
          </div>
        </form>
      </div>
    </div>
  </body>
  <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    crossorigin="anonymous"
  ></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    crossorigin="anonymous"
  ></script>
  <script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
    crossorigin="anonymous"
  ></script>
  <script>
    $(document).ready(function () {
      function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
        const expires = "expires=" + d.toUTCString();
        document.cookie = `${name}=${value};${expires};path=/`;
      }
      function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(";").shift();
      }

      function displayError(message) {
        $("#Error").css("color", "red").html(message).show();
      }

      $("#UserLoginForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
          url: "./php/login.php",
          type: "POST",
          dataType: "json",
          data: {
            email: $("#email").val(),
            password: $("#password").val(),
            "remember-me": $("#remember-me").is(":checked") ? "checked" : "",
          },
          success: function (response) {
            if (response.status === "success") {
              $("#Error").css("color", "green").html(response.message).show();
              if (response.checkbox === "checked") {
                setCookie("user_email", $("#email").val(), 7);
                setCookie("user_password", $("#password").val(), 7);
              }
              setTimeout(function () {
                window.location.href = "http://localhost/partha-sarker/notes/";
              }, 500);
            } else {
              displayError(response.message);
            }
          },
          error: function (xhr, status, error) {
            console.log("Error:", error);
            displayError("An error occurred during login.");
          },
        });
      });

      $("#email, #password").on("focus", function () {
        $("#Error").hide();
      });

      const userEmail = getCookie("user_email");
      const userPassword = getCookie("user_password");

      if (userEmail && userPassword) {
        $("#email").val(userEmail);
        $("#password").val(userPassword);
        $("#remember-me").prop("checked", true);
        $("#UserLoginForm").submit();
      }
    });
  </script>
</html>
