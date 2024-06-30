<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Home</title>
  </head>
  <body>
    <section class="header-section">
      <div class="nav-section">
        <div class="logo">
          <img src="assets/images/logo.jpg" alt="logo" />
        </div>
        <div class="manu side-nav-content" id="Manu">
          <ul>
            <li><a href="#" style="color: rgb(67, 20, 236)">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">contact</a></li>
            <li>
              <div class="dropwown-list" id="popupItm">
                <ul class="ListUl">
                  <li><a href="#" id="userlogin">User Login</a></li>
                  <li><a href="#" id="adminlogin">Admin Login</a></li>
                </ul>
              </div>
              <a href="#" id="loginBtn">Login</a>
            </li>
          </ul>
        </div>
        <span class="M-manu" id="Mnu"><i class="fa fa-bars"></i></span>
      </div>
    </section>
    <section class="main-section"></section>
    <section class="footer-section"></section>
  </body>
  <script>
    $(document).ready(function () {
      var popup = $("#popupItm");
      var btn = $("#loginBtn");
      $("#userlogin").on("click", function () {
        popup.hide();
        window.location.href =
          "http://localhost/partha-sarker/sign-in.php?status=userlogin";
      });
      $("#adminlogin").on("click", function () {
        popup.hide();
        window.location.href =
          "http://localhost/partha-sarker/sign-in.php?status=adminlogin";
      });
      btn.on("click", function () {
        popup.show();
      });
      $(document).on("click", function (event) {
        if (
          !$(event.target).closest("#popupItm").length &&
          !$(event.target).is("#loginBtn")
        ) {
          popup.hide();
        }
      });
    });
    function ShowManu() {
      document.getElementById("Side_Nav").style.display = "block";
    }
    var x = document.getElementById("Side_Nav");
    function Close() {
      x.style.display = "none";
    }
    function Open() {
      x.style.display = "block";
    }
  </script>
  <script src="assets/js/main.js"></script>
</html>
