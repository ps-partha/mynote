<?php
include('conf.php');
session_name("Session1");
session_start();
if (!isset($_SESSION['Email']) && !isset($_SESSION['Username']) && !isset($_SESSION['Id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Page</title>
  <link rel="stylesheet" href="assets/css/dashboard.css" />
  <link rel="stylesheet" href="assets/css/admin.css" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    td {
      border: 1px solid #dddddd;
      padding: 8px;
      text-align: left;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }

    .edit {
      color: white;
      padding: 5px 40px;
      border-radius: 5px;
      text-decoration: none;
    }

    .edit {
      background-color: rgba(243, 8, 8, 0.912);

    }

    .accept {
      background-color: green;
    }

    .side-nav-content .active {
      color:#e53371;
      animation: ease-in-out 2s;
    }
  </style>
</head>

<body>
  <div class="main-section">
    <div class="sider-nav" id="Side_Nav">
      <span class="close-btn" id="closeBtn" onclick="Close()"><i class="fa fa-times" aria-hidden="true"></i>
      </span>
      <h3>Admin Page</h3>
      <hr />
      <div class="side-nav-content">
        <ul>
          <a href="index.php">
            <li class='active'><i class="fa fa-home" aria-hidden="true"></i> Home</li>
          </a>
          <a href="pending-account.php">
            <li><i class="fa fa-hourglass" aria-hidden="true"></i> Panding Account</li>
          </a>
          <a href="#">
            <li><i class="fa fa-cog" aria-hidden="true"></i> Settings</li>
          </a>
          <a href="admin-logout.php">
            <li>
              <i class="fa fa-sign-out" aria-hidden="true"></i> Sing out
            </li>
          </a>
        </ul>
      </div>
    </div>
    <div class="main-container">
      <div class="nav-header">
         <i class="fa fa-bars" id="ManuButton" onclick="Open()"></i>
         <form method="post">
            <div class="search" id="Search">
              <div class="search-tm">
                <i class="fa fa-search" aria-hidden="true"></i>
              <input type="search" name="search-data" id="search" placeholder="Search.." required />
              </div>
              <button type="submit" class="searchbtn">Search</button>
            </div>
          </form>
       
        <div class="my-profile">
          <div class="profile">
            <div class="profile-img">
              <img src="./assets/images/user.webp" alt="">
            </div>
            <div class="user-info">
              <p class="name"><?php echo $_SESSION['Name']?></p>
              <p class="email"><?php echo $_SESSION['Email']?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="main-contant">
        <div class="admin-contant-tag-cover-card">
          <p>total user</p>
        </div>
        <div class="user-list">
          <table id="Table">
            <?php
              if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $search = $_POST['search-data'];
                $stmt = $conn->prepare("SELECT `id`,`username`,`email` FROM `users` WHERE `username` = ? OR `email` = ? OR `id` = ?");
                $stmt->bind_param("sss", $search, $search, $search);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $username, $email);
                      echo '<tr>
                            <td>Id</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td style="text-align: center;">Opration</td>
                          </tr>';
                    while ($stmt->fetch()) {
                      
                        echo "
                        <tr>
                          <td>". $id."</td>
                          <td>". $username."</td>
                          <td>". $email."</td>
                          <td style='text-align: center;'><a href = 'Update-info.html?$username&$email' onclick='return checkedit()' class='edit'>Edit</td>
                          </tr>";
                    }
                } else {
                    echo "No records found.";
            }
          $stmt->close();
          $conn->close();
        }

?>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  function addObserverIfDesiredNodeAvailable() {
    var composeBox = document.querySelectorAll(".no")[2];
    if (!composeBox) {
      window.setTimeout(addObserverIfDesiredNodeAvailable, 500);
      return;
    }
    var config = { childList: true };
    composeObserver.observe(composeBox, config);
  }
  addObserverIfDesiredNodeAvailable();

</script>

<script src="assets/js/main.js"></script>

</html>