<?php
include('conf.php');
function updateUserStatus($userId, $newStatus) {
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("si", $newStatus, $userId);
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
session_name("Session1");
session_start();
if (!isset($_SESSION['Email']) && !isset($_SESSION['Username']) && !isset($_SESSION['Id'])) {
    header("Location: index.php");
    exit;
}else{

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pending Account</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" />
    <link rel="stylesheet" href="assets/css/admin.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
td{
  border: 1px solid #dddddd;
  padding: 8px;
  text-align: left;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
.Reject,.accept {
  color: white;
  padding: 5px 40px;
  border-radius: 5px;
  text-decoration: none;
}
.Reject {
  background-color: rgba(243, 8, 8, 0.912);
  
}
.accept {
  background-color: green;
}
.side-nav-content .active {
  animation: ease-in-out 2s;
  color:#e53371;
}
</style>
  </head>
  <body>
    <div class="main-section">
      <div class="sider-nav" id="Side_Nav">
        <span class="close-btn" id="closeBtn" onclick="Close()"
          ><i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <h3>Admin Page</h3>
        <hr />
        <div class="side-nav-content">
          <ul>
            <a href="Admin.php"
              ><li><i class="fa fa-home" aria-hidden="true"></i> Admin</li></a
            >
            <a href="#"
              ><li class='active'><i class="fa fa-user" aria-hidden="true"></i> Panding Account</li></a
            >
            <a href="#"
              ><li><i class="fa fa-cog" aria-hidden="true"></i> Settings</li></a
            >
            <a href="admin-logout.php"
              ><li>
                <i class="fa fa-sign-out" aria-hidden="true"></i> Sing out
              </li></a
            >
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
          </div>

          <div class="user-list">
<hr style="opacity: 0.2;">
            <table>
              <?php
                $status = "inactive";
                $stmt = $conn->prepare("SELECT `id`,`username`,`email`,`status` FROM `users` WHERE `status` = ?");
                $stmt->bind_param("s", $status);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $username, $email,$status);
                      echo '<tr>
                            <td>Id</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td style="text-align: center;" colspan="2">Pending</td>
                          </tr>';
                    while ($stmt->fetch()) {
                        echo "
                        <tr>
                          <td>". $id."</td>
                          <td>". $username."</td>
                          <td>". $email."</td>
                          <td style='text-align: center;'><a href = 'update.php?id=$id&status=active' class='accept'>Accept</td>
                          <td style='text-align: center;'><a href = 'update.php?id=$id&status=reject' class='Reject'>Reject</td>
                          </tr>";
                    }
                } else {
                    echo "<br>There are currently no pending accounts available";
                }
$stmt->close();
$conn->close();
?>

            </table>
          </div>
        </div>
      </div>
    </div>
  </body>
<script src="assets/js/main.js"></script>
</html>
<?php
}?>