// TODO Refactor later when doing backend
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

$(document).ready(function () {
  // Constants
  const SIGN_IN_URL = "./index.php";

  // load the page upon first try.
  loadURL("dashboard", $("#main-root"));

  // detect hashed id in the first load
  if (window.location.hash) {
    const linkName = window.location.hash.substr(1);
    // change the color of the links
    handleLinkFocusChange(linkName);
    // change the url
    loadURL(linkName, $("#main-root"));
  }
  // manages link clicks
  $("nav li a").click(function () {
    const link = $(this).attr("data-link");
    handleLinkFocusChange(link);
    loadURL(link, $("#main-root"));
  });

  function handleLinkFocusChange(pageName) {
    // changes the state of nav links to highlight focus
    const elem = $(`nav li a[data-link="${pageName}"`);
    $("nav li a").removeClass("font-bold bg-accent text-white");
    elem.addClass("font-bold bg-accent text-white");
  }

  function loadURL(url, container, title = "") {
    // change the root elem to page

    const base_url = `./${url}/`;
    title = title.length === 0 ? url : title;

    // set the title of the page
    document.title = capitalizeFirstLetter(title);
    $.ajax({
      type: "GET",
      url: base_url + url + ".php",
      async: true,
      dataType: "html",
      // TODO check later if cache is good in changing data
      cache: true,
      beforeSend: function () {
        // add loader while waiting
        // TODO make better loading screen
        container.html("<h1>Loading...</h1>");

        // scroll to top
        if (container[0] === $(".main-root")[0]) {
          $("html").animate(
            {
              scrollTop: 0,
            },
            "fast"
          );
        }
      },
      complete: function (res) {
        //
        if (title != "") document.title = capitalizeFirstLetter(title);
      },
      success: function (data) {
        // animate a bit
        container
          .css({
            opacity: "0.0",
          })
          .html(data)
          .delay(50)
          .animate(
            {
              opacity: "1.0",
            },
            300
          );
      },
      error: function (xhr, ajaxOptions, thrownError) {
        // No Page Found
        container.load("php/missing-page.php");
      },
    });
  }

  // Sign Out functionality
  (function () {
    //sign out
    $("#signOutPromptBtn").on("click", function () {
      $("#sign-out-prompt").removeClass("hidden");

      // remove signout prompt
      $("#cancelSignoutBtn").on("click", function () {
        $("#sign-out-prompt").addClass("hidden");
      });
      // $("#sign-out-prompt").on("click", function () {

      //   $(this).addClass("hidden");
      // });

      // Signout
      $("#signoutBtn").on("click", function () {
        $.ajax({
          url: "./php/signout.php",
          type: "GET",
          success: (response) => {
            window.location.href = SIGN_IN_URL;
          },
          error: (error) => {
            console.log(error);
          },
        });
      });
      //end
    });
  })();
  // End
});
