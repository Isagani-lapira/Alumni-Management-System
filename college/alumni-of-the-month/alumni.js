$(document).ready(function () {
  // add event handler to the cancel button
  $(".cancelModal").click(function () {
    hideDisplay("#modalAlumni");
  });

  $("#addAlumniBtn").on("click", function () {
    showDisplay("#modalAlumni");
  });
});

function hideDisplay(hide = "") {
  $(hide)
    .css({
      opacity: "1.0",
    })
    .addClass("hidden")
    .delay(50)
    .animate(
      {
        opacity: "0.0",
      },
      300
    );
}

function showDisplay(show = "") {
  // add transition
  $(show)
    .css({
      opacity: "0.0",
    })
    .removeClass("hidden")
    .delay(50)
    .animate(
      {
        opacity: "1.0",
      },
      300
    );
}
