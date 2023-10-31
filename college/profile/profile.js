import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(function () {
  const URL_LINK = "./profile/apiProfile.php";

  bindHandlers();
  $('a[href="#profile"]').on("click", function () {
    setTimeout(function () {
      bindHandlers();
    }, 500);
  });

  function bindHandlers() {
    console.log("profile events is binded");
    // * used for reseting the image preview into the original image
    const temp_dean_img = $("#deanImgPreview").attr("src");
    const temp_col_logo = $("#colLogoPreview").attr("src");

    $("#submitUpdateProfileBtn").click(async function (e) {});

    // reset the file upload of logo and dean image
    $("#reset-logo").on("click", function () {
      $("#colLogoInput").val("");
      $("#colLogoPreview").attr("src", temp_col_logo);
    });

    $("#reset-dean").on("click", function () {
      $("#deanImgInput").val("");
      $("#deanImgPreview").attr("src", temp_dean_img);
    });

    // form update-college-form
    $("#update-college-form").on("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      console.log(formData);
      postJSONFromURL(URL_LINK, formData);
    });

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

    // account-profile-container

    $("#edit-profile-btn").on("click", function () {
      console.log("edit  profile btn clicked");
      const container = $("#account-profile-container");
      container.css({
        opacity: "0.0",
      });
      $("#edit-profile-container").removeClass("hidden");
      $("#view-profile-container").addClass("hidden");
      // animate the container to show the new element
      container.delay(50).animate(
        {
          opacity: "1.0",
        },
        300
      );
    });

    $("#cancel-edit-profile-btn").on("click", function () {
      console.log("edit  profile btn clicked");
      const container = $("#account-profile-container");
      container.css({
        opacity: "0.0",
      });
      $("#edit-profile-container").addClass("hidden");
      $("#view-profile-container").removeClass("hidden");
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

    $("#deanImgInput").on("change", function () {
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#deanImgPreview").attr("src", e.target.result);
        console.log("changed the dean image");
      };
      reader.readAsDataURL(this.files[0]);
    });

    $("#colLogoInput").on("change", function () {
      console.log("i rannn");
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#colLogoPreview").attr("src", e.target.result);
        console.log("changed the college logo");
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
