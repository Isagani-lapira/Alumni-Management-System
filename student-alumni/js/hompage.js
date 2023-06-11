$(document).ready(function() {
  $("#search-input").focus(function() {
    $("#search-icon").addClass("text-black");
  });

  $("#search-input").blur(function() {
    $("#search-icon").removeClass("text-black");
  });
});