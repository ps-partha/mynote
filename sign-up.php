<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
      rel="stylesheet"
    />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <title>Registration</title>
  </head>

  <body class="bg-gray-200">
    <div class="contanear">
      <div class="login-section">
        <h3>Registration</h3>
        <div class="errorbox">
          <span id="error" class="error"></span>
        </div>
        <form id="registration_form">
          <input
            type="text"
            name="name"
            id="name"
            placeholder="Enter full name"
            required
          />
          <input
            type="text"
            name="username"
            id="user"
            placeholder="Enter username"
            required
          />
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
          <input
            type="password"
            name="re-password"
            id="re-password"
            placeholder="Enter password again"
            required
          />

          <div class="button" style="margin-top: -15px">
            <button class="btn">Create Account</button>
          </div>
          <div class="accCreate">
            <a href="./sign-in.php"
              >I have an account? <span class="signBtn">Sign In</span></a
            >
          </div>
        </form>
      </div>
    </div>

    <script>
      $(document).ready(function () {
        $("#registration_form").on("submit", function (e) {
          e.preventDefault();
          let password = $("#password").val();
          let repassword = $("#re-password").val();
          if (password !== repassword) {
            $("#error").text("Passwords do not match");
            return;
          }
          $.ajax({
            url: "./php/register", // URL to your PHP script
            type: "POST",
            dataType: "json", // Expect JSON response
            data: {
              name: $("#name").val(),
              username: $("#user").val(),
              email: $("#email").val(),
              password: $("#password").val(),
            },
            success: function (response) {
              if (response.status === "success") {
                $("#error").html(response.message);
                setTimeout(function () {
                  window.location.href =
                    "http://localhost/partha-sarker/sign-in"; // Replace with your desired URL
                }, 2000); // 2000 milliseconds = 2 seconds
              } else {
                $("#error").css("color", "green");
                $("#error").html(response.message);
              }
            },
            error: function (xhr, status, error) {
              console.log("Error:", error);
              $("#error").html("An error occurred during registration.");
            },
          });
        });
      });
    </script>
  </body>
</html>
