$(document).ready(function() {
  $("#search-input").focus(function() {
    $("#search-icon").addClass("text-black");
  });

  $("#search-input").blur(function() {
    $("#search-icon").removeClass("text-black");
  });

  // Set the desired width of the sub navbar (e.g., 80% of the parent container's width)
  //var desiredWidth = '50%';

  // Set the width of the sub navbar
  //$('.sub-navbar').css('width', desiredWidth);
});