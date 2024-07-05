$(document).ready(function () {
  function isMobile() {
    return $(window).width() < 750;
  }
  $("#ManuButtosn").click(function () {
    if (isMobile()) {
      $("#Side_Nav").slideToggle(500);
    } else {
      $(".side-nav-content").slideToggle(500);
    }
  });
});
