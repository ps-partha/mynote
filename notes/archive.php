<?php
session_start();
include("../php/conf.php");
if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    header("Location: ../index");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Notes</title>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../assets/css/design.css">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
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
        <script src="../assets/js/script.js"></script>
  </head>

  <body>
<div id="blur_div">
      <div class="showData" id="Show_data_div">
        <form id="Update_">
          <div class="show-note-div-title">
            <input
              type="text"
              name="title"
              id="NoteTitle"
              placeholder="Title"
              required
            />
            <span id="pinShowNote"></span>
          </div>
          <textarea
            id="ShowNoteData"
            class="text-area"
            name="messages"
            placeholder="Note"
          ></textarea>
          <input type="hidden" name="id" id="ID" />
          <div class="formBtn">
            
          </div>
          </div>
        </form>
      <form class="add-notes" id="popupItm">
        <input
          type="text"
          name="title"
          id="Title"
          placeholder="Title"
          required
        />
        <textarea
          name="messages"
          id="Messages"
          placeholder="Take a note..."
          required
        ></textarea
        ><br />
        <div class="form-btn">
          <button type="button" id="popupCloseButton" >Close</button>
        <button type="submit" class="save">Save</button>
        </div>
      </form>
    </div>
    <!-- --------- side - nav -------- -->

    <div class="nav-header">
       <i class="fa fa-bars" id="ManuButtosn" onclick="Open()"></i>
          <div class="search" id="Search">
            <!-- <button type="submit"  class="searchbtn">Search</button> -->
          </div>
        <p class="error"></p>
        <div class="my-profile">
          <div class="profile">
            <div class="profile-img">
              <img src="../assets/images/user.webp" alt="" />
            </div>
            <div class="user-info">
              <p class="name">
                <?php echo $_SESSION['name'] ?>
              </p>
              <p class="email">
                <?php echo $_SESSION['user_email'] ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    <div class="main-container">
            <div class="sider-nav" id="Side_Nav">
      <span class="close-btn" id="closeBtn" onclick="Close()"
        ><i class="fa fa-times" aria-hidden="true"></i
      ></span>
      <div class="side-nav-content">
        <ul>
          <a href="../notes/">
            <li>
              <span class="manuIcon"
                ><i class="fa fa-bell-o" aria-hidden="true"></i
              ></span>
              Notes
            </li>
          </a>
         <a href="archive">
            <li id="archiveBtn" class="active">
              <span class="manuIcon" 
                ><i class="bi bi-archive" aria-hidden="true"></i
              ></span>
              Archive
            </li>
          </a>
          <a href="trash">
            <li>
              <span class="manuIcon" id="trashBtn"
                ><i class="fa fa-trash-o" aria-hidden="true"></i> Trash
              </span>
            </li>
          </a>
          <a href="../php/logout">
            <li>
              <span class="manuIcon"
                ><i class="fa fa-sign-out" aria-hidden="true"></i
              ></span>
              Sign out
            </li>
          </a>
        </ul>
      </div>
    </div>
      <div class="main-contant">
        <div class="search-and-add-card">
          <div class="search-tm">
              <i class="fa fa-search" aria-hidden="true"></i>
              <input
                type="search"
                 id="searchQuery"
                placeholder="Search.."
                required
              />
            </div>
          <!-- <button id="AddNote" class="savebtn">Add Notes</button> -->
        </div>
        <div class="card-list">
          <div class="row" id="Row">
            <ul class="sortable-list" id="CardListSearch"></ul>
          </div>
        </div>
        <div class="card-list">
          <div class="row">
            <div class="empty_archive">
                <p><i class='bi bi-archive' aria-hidden='true'></i> Archive is Empty</p>
            </div>
            <p class="pinned pn">PINNED</p>
            <ul class="sortable-list" id="CardListPinned"></ul>
          </div>
        </div>
        <div class="card-list">
          <div class="row" id="Row">
            <p class="pinned unpn">OTHERS</p>
            <ul class="sortable-list" id="CardListOthers"></ul>
          </div>
        </div>
      </div>
    </div>
    <script>
     $(document).ready(function () {
    const apiUrl = "http://localhost/partha-sarker/notes/getNotes.php";
    
    // Fetch and display all notes
    function fetchNotes() {
        $.ajax({
            url: apiUrl,
            method: "GET",
            dataType: "json",
            success: function (data) {
                if (data.status === "success") {
                    displayNotes(data.data);
                } else {
                    console.error("Error:", data.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown);
            },
        });
    }
    fetchNotes();

    function displayNotes(notes) {
        const archiveNotesContainer = $("#CardListOthers");
        archiveNotesContainer.empty();
        notes.forEach((note) => {
            const archiveNoteElement = $(`
                <li class='item' draggable='true'>
                    <div class='column'>
                        <div class='container' data-id='${note.id}' onclick='showDetails(this)'>
                            <div class='main-containt'>
                                <div class='title'>
                                    <p class='title_text'>${note.titles}</p>
                                    <a href='../php/update?id=${note.id}&status=${note.status}'>
                                        <span><i class='fa fa-thumb-tack' aria-hidden='true'></i></span>
                                    </a>
                                </div>
                                <textarea style='user-select: none;' readonly>${note.messages}</textarea>
                            </div>
                        </div>
                        <div class='button_list'>
                            <a href='../php/update?id=${note.id}&status=unarchive'><span class="footer_icon"><i class='bi bi-archive' aria-hidden='true'></i></span></a>
                            <a href='../php/update?id=${note.id}&status=trash'><span class="footer_icon"><i class='fa fa-trash-o' aria-hidden='true'></i></span></a>
                            <a href='../php/update?id=${note.id}&status=share'><span class="footer_icon"><i class='fa fa-share' aria-hidden='true'></i></span></a>
                            </div>
                    </div>
                </li>
            `);
            if (note.status == "archived") {
                $('.ar').show();
                $('.empty_archive').hide();
                archiveNotesContainer.append(archiveNoteElement);
            }
        });
    }
    function displaySearchResults(notes) {
        const pinnedNotesContainer = $("#CardListPinned");
        const unpinnedNotesContainer = $("#CardListOthers");
        pinnedNotesContainer.empty();
        unpinnedNotesContainer.empty();
        const results = $("#CardListSearch");
        results.empty();

        if (notes.length > 0) {
            notes.forEach(note => {
                results.append(
                    `
                    <li class='item' draggable='true'>
                        <div class='column'>
                            <div class='container' data-id='${note.id}' onclick='showDetails(this)'>
                                <div class='main-containt'>
                                    <div class='title'>
                                        <p class='title_text'>${note.titles}</p>
                                        <a href='../php/update?id=${note.id}&status=${note.status}'>
                                            <span><i class='fa fa-thumb-tack' aria-hidden='true'></i></span>
                                        </a>
                                    </div>
                                    <textarea style='user-select: none;' readonly>${note.messages}</textarea>
                                </div>
                            </div>
                            <div class='button_list'>
                                <a href='../php/update?id=${note.id}&status=archive'><span class="footer_icon"><i class='bi bi-archive' aria-hidden='true'></i></span></a>
                                <a href='../php/update?id=${note.id}&status=trash'><span class="footer_icon"><i class='fa fa-trash-o' aria-hidden='true'></i></span></a>
                                <a href='../php/update?id=${note.id}&status='><span class="footer_icon"><i class='fa fa-share' aria-hidden='true'></i></span></a>
                            </div>
                        </div>
                    </li>
                    `
                );
            });
            $('.empty_archive').hide();
        } else {
            results.html("<p>No results found</p>");
        }
    }
    $("#searchQuery").on("keyup", function () {
        $('.empty_archive').show();
        let query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: "../php/search-notes.php",
                type: "POST",
                dataType: "json",
                data: { query: query },
                success: function (response) {
                    if (response.status === "success") {
                        displaySearchResults(response.data);
                        $('.pn').hide();
                        $('.unpn').hide();
                    } else {
                        $("#CardListSearch").html("<p>No results found</p>");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error:", error);
                    $("#CardListSearch").html("<p>An error occurred while searching</p>");
                },
            });
        } else {
            $("#CardListSearch").empty();
            fetchNotes();
        }
    });
    // Update note
    $("#Update_").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "../php/update-note",
            type: "POST",
            dataType: "json",
            data: {
                id: $("#ID").val(),
                title: $("#NoteTitle").val(),
                messages: $("#ShowNoteData").val(),
            },
            success: function (response) {
                if (response.status === "success") {
                    var popup = $("#Show_data_div");
                    popup.removeClass("div-popup-active").fadeOut();
                    $("#blur_div").removeClass("blur_div-active").fadeOut();
                    location.reload();
                } else {
                    $(".error").css("color", "red");
                    $(".error").html(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
                $(".error").html("An error occurred");
            },
        });
    });

    // Dynamic textarea resizing
    $("#ShowNoteData, #Messages").on("input", function () {
        $(this).css("height", "auto");
        let newHeight = Math.min(this.scrollHeight, 300);
        $(this).css("height", newHeight + "px");
    });

    // Close popup
    $("#CloseShowNote").click(function () {
        $("title").text("Dashboard");
        $("#blur_div").removeClass("blur_div-active").fadeOut();
        $("#Show_data_div").removeClass("div-popup-active").fadeOut();
    });
});
    // Show note details
function showDetails(note) {
        $.ajax({
          url: "http://localhost/partha-sarker/notes/getNotes",
          method: "GET",
          dataType: "json",
          success: function (response) {
            function getTitlesAndMessagesById(id) {
              var filteredData = response.data
                .filter(function (note) {
                  return note.id === id;
                })
                .map(function (note) {
                  return {
                    title: note.titles,
                    message: note.messages,
                  };
                });
              return filteredData[0]; // Assuming id is unique and we want the first match
            }
            let id = note.getAttribute("data-id");
            var noteId = getTitlesAndMessagesById(parseInt(id));
            $("#ID").val(id);
            if (noteId) {
              var popup = $("#Show_data_div");
              $("#NoteTitle").val(noteId.title);
              $("#ShowNoteData").val(noteId.message);
              popup.toggleClass("div-popup-active").fadeIn();
              $("#blur_div").toggleClass("blur_div-active").fadeIn();
            }
          },
          error: function (error) {
            console.error("Error:", error);
          },
        });
      }
    </script>
  </body>
</html>
