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
.sortable-list #NoteList {
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

.showData {
  min-height: 300px;
  max-height: 450px;
  visibility: hidden;
  transition: 0.5s;
}
.text-area{
    padding-bottom:30px ;
    cursor: text;
    min-height: 300px;
  max-height: 450px;
  border:none;
}
/* .blur-div{
    filter:blur(10px);
    pointer-events:none;
    user-select:none;
} */
 .div-popup-active{
    visibility: visible;
    opacity: 1;
    transition: 0.5s;
}

#NoteTitle{
    font-size:17px;
    font-weight: bold;
    padding-left:10px;
}
.formBtn {
  padding-left: 10px;
  display: flex;
  justify-content: space-between;
}
#CopyBtn{
    text-decoration: none;
    font-size:14px;
}

#saveBtn,#CopyBtn{
    padding: 5px 20px;
    border:1px solid #3f3d3d59 ;
    border-radius:5px;
}
.button_list{
display: flex;
  justify-content: space-between;
  padding:10px;
 border-radius: 10px;
 opacity: 0;
    visibility: hidden;

}
.button_list a,.title a{
    color:black;
}
  </style>
</head>
<body>
<div class="showData" id="Show_data_div">
<form id="Update_">
      <p id="NoteTitle"></p>
      <textarea id="ShowNoteData" class="text-area" name="messages"></textarea>
      <input type="hidden" name="id" id="ID">
      <div class="formBtn">
        <button type="submit" id="saveBtn">Close</button>
      </div>
</form>
</div>
 <form class="add-notes" id="popupItm">
        <div class="popupCloseButton">&times;</div>
        <textarea name="title" id="Title" placeholder="Title" required></textarea>
        <textarea name="messages" id="Messages" placeholder="Take a note..." required></textarea><br/>
        <button type="submit" class="savebtn">Save Note</button>
    </form>
<div class="main-section">
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
    <div class="main-container" >
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
                                <div class='button_list'>
                                    <a href='update.php?id=$id&status=copy'><span><i class='fa fa-files-o' aria-hidden='true'></i></span></a>
                                    <a href='update.php?id=$id&status=delete'><span><i class='fa fa-trash-o' aria-hidden='true'></i></span></a>
                                    <a href='update.php?id=$id&status=share'><span><i class='fa fa-share' aria-hidden='true'></i></span></a>
                                    <a href='update.php?id=$id&status=hide'><span><i class='fa fa-eye-slash' aria-hidden='true'></i></span></a>
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
                                <div class='button_list'>
                                    <a href='update.php?id=$id&status=unpinned'><span><i class='fa fa-files-o' aria-hidden='true'></i></span></a>
                                    <a href='update.php?id=$id&status=delete'><span><i class='fa fa-trash-o' aria-hidden='true'></i></span></a>
                                    <a href='update.php?id=$id&status=unpinned'><span><i class='fa fa-share' aria-hidden='true'></i></span></a>
                                    <a href='update.php?id=$id&status=unpinned'><span><i class='fa fa-eye-slash' aria-hidden='true'></i></span></a>
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
<script>
    $(document).ready(function () {
  $("textarea")
    .each(function () {
      this.setAttribute(
        "style",
        "height:" + this.scrollHeight + "px;overflow-y:hidden;"
      );
    })
    .on("input", function () {
      this.style.height = "auto";
      this.style.height = this.scrollHeight + "px";
    });

  var popup = $("#popupItm");
  var btn = $("#AddNote");
  var closebtn = $(".popupCloseButton");
  var blur = document.getElementById("blur");
  popup.hide();
  btn.click(function () {
    popup.show();
    blur.classList.toggle("blur-div");
  });
  closebtn.click(function () {
    popup.hide();
    blur.classList.remove("blur-div");
  });

  $("#popupItm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: "save-note.php",
      type: "POST",
      dataType: "json",
      data: {
        Title: $("#Title").val(),
        Messages: $("#Messages").val(),
      },
      success: function (response) {
        if (response.status === "success") {
          $(".error").css("color", "green");
          $(".error").html(response.message);
        } else {
          $(".error").css("color", "red");
          $(".error").html(response.message);
        }
        popup.hide();
        blur.classList.remove("blur-div");
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
        $(".error").html("An error occurred");
      },
    });
  });
    // Handle form submission
  $('#Update_').on('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    var formData = $(this).serialize();
    // Process the form data (e.g., send it via AJAX)
    $.ajax({
      type: 'POST',
      url: 'update-note.php',
      data: formData,
      success: function(response) {
        location.reload();
      },
      error: function(error) {
        // Handle any errors
        alert('An error occurred. Please try again.');
        console.log(error);
      }
    });
  });
});

function showDetails(note) {
    let id = note.getAttribute("data-id");
    $.ajax({
        url: "showNote.php",
        type: "POST",
        data: { id: id },
        success: function (response) {
            var popup = $("#Show_data_div");
            var blur = document.getElementById("blur");
            var data = JSON.parse(response);
            if (data) {
                $("#NoteTitle").text(data.titles);
                $("#ShowNoteData").val(data.messages);
                $("#ID").val(id);
                popup.toggleClass("div-popup-active");
                
            } else {
                $(".error").html("<p>Error parsing JSON.</p>");
            }
        },
    });
}

</script>
</body>
</html>
