$(document).ready(function () {
  // Constants
  const SIGN_IN_URL = "./index.php";

  // TODO make the loaders load upon section click.

  // Initial Load
  initialLoad();
  function initialLoad() {
    // detect hashed id in the first load
    if (window.location.hash) {
      const linkName = window.location.hash.substr(1);
      // change the color of the links
      handleLinkFocusChange(linkName);
    }
    // load the page upon first try.
    loadURL("dashboard", $("#main-root"));
  }

  // TODO Refactor later when doing backend
  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function handleLinkFocusChange(pageName) {
    // changes the state of nav links to highlight focus
    const elem = $(`nav li a[data-link="${pageName}"`);
    $("nav li a").removeClass("font-bold bg-accent text-white");
    elem.addClass("font-bold bg-accent text-white");
  }

  // Handles the AJAX request for the section body
  async function getSectionBody(url) {
    try {
      const result = await fetch(url, {
        method: "GET",
        headers: {
          cache: "no-cache",
        },
      });
      const htmlBody = await result.text();
      return htmlBody;
    } catch (error) {
      // return the error
      return error;
    }
  }

  // Transistion the section appearance
  function transistionSectionAppearance(container, htmlBody) {
    container
      .css({
        opacity: "0.0",
      })
      .html(htmlBody)
      .delay(50)
      .animate(
        {
          opacity: "1.0",
        },
        300
      );
  }

  async function loadURL(url, container, title = "") {
    // change the root elem to page
    const base_url = `./${url}/`;
    title = title.length === 0 ? url : title;

    // set the title of the page
    if (title != "") document.title = capitalizeFirstLetter(title);

    // Change Div to loading animation while waiting
    // container.html("<h1>Loading...</h1>");
    container.html(`  <!-- loading screen -->
    <div id="loadingScreen" class="post modal fixed inset-0 flex flex-col justify-center items-center p-3 z-50 hidden">
      <span class="loader w-36 h-36"></span>
      <span class="text-lg font-bold text-white my-2 italic">"We promise it's worth the wait!"</span>
    </div>`);

    // remove the loading animation
    if (container[0] === $(".main-root")[0]) {
      $("html").animate(
        {
          scrollTop: 0,
        },
        "fast"
      );
    }

    try {
      const htmlBody = await getSectionBody(base_url + url + ".php");

      transistionSectionAppearance(container, htmlBody);
      console.log("Loaded", title);
    } catch (error) {
      alert(error);
      // No Page Found
      container.load("php/missing-page.php");
    }
  }

  // * Event Handlers

  // Handles the navigation links
  $("nav li a").on("click", function () {
    const link = $(this).attr("data-link");
    handleLinkFocusChange(link);
    loadURL(link, $("#main-root"));
  });

  // Handles Sidebar collapse using the hamburger menu
  $("#toggleSidebarIcon").on("click", function () {
    $("#sidebar").toggleClass("is-collapsed");
  });

  /**
   * Signs out the user and redirects to the sign in page
   */
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

  // Handles the signout prompt
  $("#signOutPromptBtn").on("click", function () {
    $("#sign-out-prompt").removeClass("hidden");
  });

  // Handles the cancel signout prompt
  $("#cancelSignoutBtn").on("click", function () {
    $("#sign-out-prompt").addClass("hidden");
  });
});
