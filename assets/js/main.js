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
// --------------------------------

const sortableList = document.querySelector(".sortable-list");
const items = sortableList.querySelectorAll(".item");
items.forEach((item) => {
  item.addEventListener("dragstart", () => {
    // Adding dragging class to item after a delay
    setTimeout(() => item.classList.add("dragging"), 0);
  });
  // Removing dragging class from item on dragend event
  item.addEventListener("dragend", () => item.classList.remove("dragging"));
});
const initSortableList = (e) => {
  e.preventDefault();
  const draggingItem = document.querySelector(".dragging");
  // Getting all items except currently dragging and making array of them
  let siblings = [...sortableList.querySelectorAll(".item:not(.dragging)")];
  // Finding the sibling after which the dragging item should be placed
  let nextSibling = siblings.find((sibling) => {
    return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
  });
  // Inserting the dragging item before the found sibling
  sortableList.insertBefore(draggingItem, nextSibling);
};
sortableList.addEventListener("dragover", initSortableList);
sortableList.addEventListener("dragenter", (e) => e.preventDefault());

// --------------------------

$(document).ready(function () {
  $("#ShowNoteData").on("input", function () {
    $(this).css("height", "auto");
    let newHeight = Math.min(this.scrollHeight, 300);
    $(this).css("height", newHeight + "px");
  });
  $("#Messages").on("input", function () {
    $(this).css("height", "auto");
    let newHeight = Math.min(this.scrollHeight, 300);
    $(this).css("height", newHeight + "px");
  });
  $("#CloseShowNote").click(function () {
    $("title").text("Dashboard");
    $("#blur_div").removeClass("blur_div-active").fadeOut();
    $("#Show_data_div").removeClass("div-popup-active").fadeOut();
  });

  var popup = $("#popupItm");
  var btn = $("#AddNote");
  var closebtn = $(".popupCloseButton");
  btn.click(function () {
    $("#blur_div").toggleClass("blur_div-active").fadeIn();
    popup.show();
  });
  closebtn.click(function () {
    popup.hide();
    $("#blur_div").removeClass("blur_div-active").fadeIn();
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
          popup.hide();
          $("#blur_div").removeClass("blur_div-active").fadeIn();
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
  // Handle form submission
  $("#Update_").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission
    var formData = $(this).serialize();
    $.ajax({
      type: "POST",
      url: "update-note.php",
      data: formData,
      success: function (response) {},
      error: function (error) {
        // Handle any errors
        alert("An error occurred. Please try again.");
        console.log(error);
      },
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
        $("title").text(data.titles);
        $("#NoteTitle").text(data.titles);
        $("#ShowNoteData").val(data.messages);
        $("#ID").val(id);
        popup.toggleClass("div-popup-active").fadeIn();
        $("#blur_div").toggleClass("blur_div-active").fadeIn();
      } else {
        $(".error").html("<p>Error parsing JSON.</p>");
      }
    },
  });
}
