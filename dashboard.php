<?php
session_start();

if (!isset($_SESSION['email']) && !isset($_SESSION['username']) ) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
  </head>
  <body>
    <div class="main-section">
      <div class="sider-nav" id="Side_Nav">
        <span class="close-btn" id="closeBtn" onclick="Close()"
          ><i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <h3>Dashboard</h3>
        <hr />
        <div class="side-nav-content">
          <ul>
            <a href="#"
              ><li><i class="fa fa-home" aria-hidden="true"></i> Home</li></a
            >
            <a href="#"
              ><li><i class="fa fa-user" aria-hidden="true"></i> Pages</li></a
            >
            <a href="#"
              ><li><i class="fa fa-cog" aria-hidden="true"></i> Settings</li></a
            >
            <a href="logout.php"
              ><li>
                <i class="fa fa-sign-out" aria-hidden="true"></i> Sing out
              </li></a
            >
          </ul>
        </div>
      </div>
      <div class="main-container">
        <div class="nav-header">
          <ul>
            <li>Pages</li>
            <li>/</li>
            <li class="active-list">Dashboard</li>
          </ul>
          <div class="navbar-icons">
            <i
              class="fa fa-user-circle-o note"
              aria-hidden="true"
              style="cursor: pointer"
            ></i>
            <i class="fa fa-bars" id="ManuButton" onclick="Open()"></i>
          </div>
        </div>
        <div class="main-contant">
          <div class="main-contant-tag-card">
            
            <p>Hey Mr. <?php echo htmlspecialchars($_SESSION['username']); ?>. Welcome to Your Parsonal Dashboard</p>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script>
    function ShowManu() {
      document.getElementById("Side_Nav").style.display = "block";
    }
  </script>
  <script src="assets/js/main.js"></script>
</html>
