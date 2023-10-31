import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(function () {
  // bind handlers when the anchor link is clicked
  // Add onchange whenever the dashboard is clicked

  const URL_LINK = "./settings/apiSettings.php";

  $('a[href="#settings"]').on("click", function () {
    setTimeout(function () {
      bindHandlers();
    }, 500);
  });

  bindHandlers();

  function bindHandlers() {
    // preview the image after changing the input

    console.log("binded");

    // Update Password Handler update-password-btn
    $("#update-password-form").submit(async function (e) {
      e.preventDefault();
      // add a sweet alert dialog
      const confirm = await Swal.fire({
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonText: "Yes",
        customClass: {
          actions: "my-actions",
          cancelButton: "order-1 right-gap",
          confirmButton: "order-2",
          denyButton: "order-3",
        },
      });

      if (confirm.isConfirmed) {
        const form = $("#update-password-form");
        const data = new FormData(form[0]);
        console.log(data);

        const response = await postJSONFromURL(URL_LINK, data);
        console.log(response);
        if (response.status === true) {
          Swal.fire(
            "You've successfully updated your password.",
            "",
            "success"
          );
          // reset the form
          $("#update-password-form")[0].reset();
          // hide the modal
          $("#changePassModal").prop("checked", false);
        } else {
          Swal.fire("Error. Unable to update the password", "", "error");
        }
      } else if (confirm.isDenied) {
        Swal.fire("Change Password Cancelled.", "", "info");

        $("#update-password-form")[0].reset();
        $("#changePassModal").prop("checked", false);
      }
    });

    // End Update Password Handler

    $("#setting-tab-container .daisy-tab").click(function () {
      // get the value of the href
      const url = $(this).attr("href");
      const container = $("#content-container");

      container.css({
        opacity: "0.0",
      });

      console.log(url);
      // remove all the active classes
      $("#setting-tab-container .daisy-tab").removeClass("daisy-tab-active");
      $(this).addClass("daisy-tab-active");

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

    // Edit Profile Binders
    $("#edit-profile-btn").on("click", function () {
      console.log("edit  profile btn clicked");
      const container = $("#content-container");
      container.css({
        opacity: "0.0",
      });
      $("#edit-profile-container").removeClass("hidden");
      $("#login-security-container").addClass("hidden");
      // animate the container to show the new element
      container.delay(50).animate(
        {
          opacity: "1.0",
        },
        300
      );
    });

    // General Settings Submit Handler
    $("#general-settings-form").submit(async function (e) {
      e.preventDefault();
      // add a sweet alert dialog
      const confirm = await Swal.fire({
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonText: "Yes",
        customClass: {
          actions: "my-actions",
          cancelButton: "order-1 right-gap",
          confirmButton: "order-2",
          denyButton: "order-3",
        },
      });

      if (confirm.isConfirmed) {
        const form = $("#general-settings-form");
        const data = new FormData(form[0]);
        console.log(data);

        const response = await postJSONFromURL(URL_LINK, data);
        console.log(response);
        if (response.status === true) {
          Swal.fire("Changes are saved", "", "success");
        } else {
          Swal.fire("Changes are not saved", "", "error");
        }
      } else if (confirm.isDenied) {
        Swal.fire("Changes are not saved", "", "info");
      }
    });

    // Handle the form submission
    $("#personal-info-form").submit(async function (e) {
      e.preventDefault();
      // add a sweet alert dialog
      const confirm = await Swal.fire({
        title: "Do you want to save the changes?",
        showCancelButton: true,
        confirmButtonText: "Yes",
        customClass: {
          actions: "my-actions",
          cancelButton: "order-1 right-gap",
          confirmButton: "order-2",
          denyButton: "order-3",
        },
      });

      if (confirm.isConfirmed) {
        const form = $("#personal-info-form");
        const data = new FormData(form[0]);
        console.log(data);

        const response = await postJSONFromURL(URL_LINK, data);
        console.log(response);
        if (response.status === true) {
          Swal.fire("Changes are saved", "", "success");
        } else {
          Swal.fire("Changes are not saved", "", "error");
        }
      } else if (confirm.isDenied) {
        Swal.fire("Changes are not saved", "", "info");
      }
    });

    $("#cover-img").on("change", function () {
      console.log("i rannn");
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#cover-img-preview").attr("src", e.target.result);
        console.log("i ran");
      };
      reader.readAsDataURL(this.files[0]);
    });
    $("#personal-img-pic").on("change", function () {
      console.log("i rannn");
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#personal-img-preview").attr("src", e.target.result);
        console.log("i ran");
      };
      reader.readAsDataURL(this.files[0]);
    });

    $("#cancel-edit-profile-btn").on("click", function () {
      console.log("edit  profile btn clicked");
      const container = $("#content-container");
      container.css({
        opacity: "0.0",
      });
      $("#login-security-container").removeClass("hidden");
      $("#edit-profile-container").addClass("hidden");
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
