<?php
session_start();
include("conf.php");
if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="assets/css/dashboard.css"/>
    <link rel="stylesheet" href="assets/css/admin.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <style>
.sortable-list .item {
  list-style: none;
  cursor: move;
}
.item .details {
  display: flex;
  align-items: center;
}
textarea{
user-select: none;
cursor: pointer;
padding-bottom:30px ;
}
.pin {
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.5s, visibility 0.5s;
}
.pinned-note {
  visibility: visible;
  opacity: 1;
}
.showData {
  min-height: 300px;
  max-height: 450px;
  visibility: hidden;
  transition: 1s;
}
.text-area{
    padding-bottom:30px ;
    cursor: text;
    min-height: 300px;
  max-height: 450px;
}
.blur-div{
    filter:blur(10px);
    pointer-events:none;
    user-select:none;
}
#Show_data_div.div-popup-active{
    visibility: visible;
    opacity: 1;
    transition: 0.5s;
}
  </style>
</head>
<body>
<div class="showData" id="Show_data_div">
    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_SESSION['user_name'];
    $stmt = $conn->prepare("SELECT `titles`, `messages` FROM `user_notes` WHERE `id` = ? AND `username` = ?");
    $stmt->bind_param("is", $id, $username); // 'i' for integer and 's' for string
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($titles, $messages);
        while ($stmt->fetch()) {
            echo "
            <form action='update.php?status=update&id=$id' method='POST'>
                <textarea class='text-area' name='updatenote'>$messages</textarea>
                <button type='submit'>Update Note</button>
                <button type='button' style='float: right;' id='Close'>Close</button>
            </form>
";
        }
    } else {
        echo "<br/>No notes available";
    }
    $stmt->close();
}else{
    echo "Sorry";
}
    ?>
</div>
 <form class="add-notes" id="popupItm">
        <div class="popupCloseButton">&times;</div>
        <textarea name="title" id="Title" placeholder="Title" required></textarea>
        <textarea name="messages" id="Messages" placeholder="Take a note..." required></textarea><br/>
        <button type="submit" class="savebtn">Save Note</button>
    </form>
<div class="main-section" id="blur">
    <div class="sider-nav" id="Side_Nav">
        <span class="close-btn" id="closeBtn" onclick="Close()">
            <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <h3>Dashboard</h3>
        <hr/>
        <div class="side-nav-content">
            <ul>
                <a href="index.php"><li><i class="fa fa-home" aria-hidden="true"></i> Home</li></a>
                <a href="#"><li class="active"><i class="fa fa-user" aria-hidden="true"></i> Dashboard</li></a>
                <a href="#"><li><i class="fa fa-cog" aria-hidden="true"></i> Settings</li></a>
                <a href="logout.php"><li><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</li></a>
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
                        <input type="search" name="search-data" id="search" placeholder="Search.." required/>
                    </div>
                    <button type="submit" class="searchbtn">Search</button>
                </div>
            </form>
            <p class="error"></p>
            <div class="my-profile">
                <div class="profile">
                    <div class="profile-img">
                        <img src="./assets/images/user.webp" alt=""/>
                    </div>
                    <div class="user-info">
                        <p class="name"><?php echo $_SESSION['user_name']?></p>
                        <p class="email"><?php echo $_SESSION['user_email']?></p>
                    </div>
                </div>
            </div>
        </div>
        <hr style="margin-top: -20px"/>
        <div class="main-contant">
            <div class="search-and-add-card">
                <div class="search-card">
                    <h3>Notes List</h3>
                </div>
                <div class="add-card">
                    <button id="AddNote" class="savebtn">Add Notes</button>
                </div>
            </div>
            <div class="card-list" id="CardListPinned">
                <p class="pinned">PINNED</p>
                <ul class="sortable-list">
                <div class="row">
                     <?php
                    $status = "pinned";
                    $username = $_SESSION['user_name'];
                    $stmt = $conn->prepare("SELECT `id`, `titles`, `messages` FROM `user_notes` WHERE `status` = ? AND `username` = ?");
                    $stmt->bind_param("ss", $status, $username);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $titles, $messages);
                        while ($stmt->fetch()) {
                            echo "
                            <li class='item' draggable='true' id='NoteList'>
                            <div class='column'>
                                <div class='container' data-id='$id' onclick='showDetails(this)'>
                                    <div class='main-containt'>
                                        <div class='title'>
                                            <p class='title_text'>$titles</p>
                                            <a href='update.php?id=$id&status=unpinned'><span><i class='fa fa-thumb-tack' aria-hidden='true'></i></span></a>
                                        </div>
                                        <textarea style='user-select: none;' readonly>$messages</textarea>
                                    </div>
                                </div>
                            </div>
                            </li>";
                        }
                    } else {
                        echo " ";
                    }
                    $stmt->close();
                    ?>
                </div>
                </ul>
            </div>
            <div class="card-list" id="CardListOthers">
                <p class="pinned">OTHERS</p>
                <div class="row" id="Row">
                    <ul class="sortable-list">
                    <?php
                    $status = "unpinned";
                    $username = $_SESSION['user_name'];
                    $stmt = $conn->prepare("SELECT `id`, `titles`, `messages` FROM `user_notes` WHERE `status` = ? AND `username` = ?");
                    $stmt->bind_param("ss", $status, $username);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $titles, $messages);
                        while ($stmt->fetch()) {
                            echo "
                            <li class='item' draggable='true' id='NoteList'>
                            <div class='column'>
                                <div class='container' data-id='$id' onclick='showDetails(this)'>
                                    <div class='main-containt'>
                                        <div class='title'>
                                            <p class='title_text'>$titles</p>
                                            <a href='update.php?id=$id&status=pinned'><span class='pin'><i class='fa fa-thumb-tack' aria-hidden='true'></i></span></a>
                                        </div>
                                        <textarea style='user-select: none;' readonly>$messages</textarea>
                                    </div>
                                </div>
                            </div>
                            </li>";
                        }
                    } else {
                        echo "<br/>There are currently no unpinned notes available";
                    }
                    $stmt->close();
                    ?>
                </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/main.js"></script>

</body>
</html>
