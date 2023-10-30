$(document).ready(function () {
  const URL_LINK = "./profile/apiProfile.php";

  bindHandlers();
  $('a[href="#profile"]').on("click", function () {
    setTimeout(function () {
      bindHandlers();
    }, 500);
  });

  function bindHandlers() {
    console.log("i was binded");

    // Show the edit college form when edit-college-profile-btn is clicked
    $("#edit-college-profile-btn").on("click", function () {
      console.log("edit college profile btn clicked");
      const container = $("#college-profile-container");
      container.css({
        opacity: "0.0",
      });
      $("#edit-college-profile").removeClass("hidden");
      $("#view-college-profile").addClass("hidden");
      // animate the container to show the new element
      container.delay(50).animate(
        {
          opacity: "1.0",
        },
        300
      );
    });

    // remove the edit college form when cancel-edit-college-profile-btn is clicked
    $("#cancel-edit-college-profile-btn").on("click", function () {
      const container = $("#college-profile-container");

      container.css({
        opacity: "0.0",
      });
      $("#edit-college-profile").addClass("hidden");
      $("#view-college-profile").removeClass("hidden");

      // animate the container to show the new element
      container.delay(50).animate(
        {
          opacity: "1.0",
        },
        300
      );
    });

    // Submit College Profile
    $("#submit-update-college-form").click(async function (e) {
      e.preventDefault();
      const form = $("#update-college-form");
      const formData = new FormData(form[0]);
      console.log(formData);
      const response = await postJSONFromURL(URL_LINK, formData);
      console.log(response);
    });

    // Show the preview of the image after changing the input

    $("#colLogoInput").on("change", function () {
      console.log("i rannn");
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#colLogoPreview").attr("src", e.target.result);
        console.log("changed the input");
      };
      reader.readAsDataURL(this.files[0]);
    });

    // Change the active tab when clicked
    $("#profile-tab-container a.daisy-menu-item").click(function () {
      // get the value of the href
      const url = $(this).attr("href");
      const container = $("#content-container");

      container.css({
        opacity: "0.0",
      });

      console.log(url);
      // remove all the active classes
      $("#profile-tab-container .daisy-menu-item").removeClass("daisy-active");
      $(this).addClass("daisy-active");

      // get the linked element
      const elem = $(url);
      // hide all the other elements from the container
      elem.siblings().addClass("hidden");
      //  remove the hide class
      elem.removeClass("hidden");

      // animate the container to show the new element
      container.delay(50).animate(
        {
          opacity: "1.0",
        },
        300
      );
    });
  }
});
