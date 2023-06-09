// TODO Refactor later when doing backend
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

$(document).ready(function () {
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

    const base_url = "pages/";
    title = title.length === 0 ? url : title;
    // set the title of the page
    document.title = capitalizeFirstLetter(title);
    $.ajax({
      type: "GET",
      url: base_url + url + ".php",
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
        container.load("pages/missing-page.php");
      },
      async: false,
    });
  }
});
