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

    $("#submitUpdateProfileBtn").click(async function (e) {
      // e.preventDefault();
      // const form = $("#personal-info-form");
      // const formData = new FormData(form[0]);
      // console.log(formData);
      // const response = await postJSONFromURL(URL_LINK, formData);
      // console.log(response);
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

      // const submitBtn = $("#submitUpdateProfileBtn");

      // disable the button
      // submitBtn.attr("disabled", true);
      // submitBtnText.text("Saving...");

      // $.ajax({
      //   url: url,
      //   method: method,
      //   data: data,
      //   processData: false,
      //   contentType: false,
      //   success: function (response) {
      //     // enable the button
      //     submitBtn.attr("disabled", false);
      //     submitBtnText.text("Save");
      //     console.log(response);
      //     // show the message
      //     $("#message").removeClass("hidden");
      //     $("#message").text(response.message);
      //     // hide the message after 3 seconds
      //     setTimeout(function () {
      //       $("#message").addClass("hidden");
      //     }, 3000);
      //   },
      //   error: function (err) {
      //     // enable the button
      //     submitBtn.attr("disabled", false);
      //     submitBtnText.text("Save");
      //     console.log(err);
      //     // show the message
      //     $("#message").removeClass("hidden");
      //     $("#message").text(err.responseJSON.message);
      //     // hide the message after 3 seconds
      //     setTimeout(function () {
      //       $("#message").addClass("hidden");
      //     }, 3000);
      //   },
      // });
    });
  }
});
