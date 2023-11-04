import {
  getJSONFromURL,
  postJSONFromURL,
  animateOpactityTransitionOnContainer,
} from "../scripts/utils.js";

$(document).ready(function () {
  // bind handlers when the anchor link is clicked
  // Add onchange whenever the dashboard is clicked

  const URL_LINK = "./settings/apiSettings.php";

  // Define custom methods for password validation
  jQuery.validator.addMethod(
    "hasUppercase",
    function (value, element) {
      // Check if the value contains at least one uppercase letter
      return /[A-Z]/.test(value);
    },
    "Password must contain at least one uppercase letter."
  );

  jQuery.validator.addMethod(
    "hasLowercase",
    function (value, element) {
      // Check if the value contains at least one lowercase letter
      return /[a-z]/.test(value);
    },
    "Password must contain at least one lowercase letter."
  );

  jQuery.validator.addMethod(
    "hasNumber",
    function (value, element) {
      // Check if the value contains at least one number
      return /\d/.test(value);
    },
    "Password must contain at least one number."
  );

  jQuery.validator.addMethod(
    "hasSymbol",
    function (value, element) {
      // Check if the value contains at least one symbol
      return /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(value);
    },
    "Password must contain at least one symbol."
  );

  $('a[href="#settings"]').on("click", function () {
    setTimeout(function () {
      bindHandlers();
    }, 500);
  });

  bindHandlers();

  function togglePasswordVisibility(jqIcon, jqInput) {
    if (jqInput.attr("type") === "password") {
      jqInput.attr("type", "text");
      jqIcon.removeClass("fa-eye");
      jqIcon.addClass("fa-eye-slash");
    } else {
      jqInput.attr("type", "password");
      jqIcon.removeClass("fa-eye-slash");
      jqIcon.addClass("fa-eye");
    }
  }

  function bindHandlers() {
    // preview the image after changing the input

    console.log("binded");

    // on Click handlers for toggling password visibility

    $("#toggle-oldPass").on("click", function () {
      togglePasswordVisibility($(this), $("#old-password"));
    });

    $("#toggle-newPass").on("click", function () {
      togglePasswordVisibility($(this), $("#password"));
    });

    $("#toggle-confirmPass").on("click", function () {
      togglePasswordVisibility($(this), $("#confirmPassword"));
    });

    $("#update-password-form").validate({
      rules: {
        ["old-password"]: {
          required: true,
          minlength: 8, // Minimum password length
          hasUppercase: true,
          hasLowercase: true,
          // hasNumber: true,
          // hasSymbol: true,
        },
        password: {
          required: true,
          minlength: 8, // Minimum password length
          hasUppercase: true,
          hasLowercase: true,
          hasNumber: true,
          hasSymbol: true,
        },
        confirmPassword: {
          required: true,
          equalTo: "#password", // Check if it's equal to the password field
        },
      },
      messages: {
        password: {
          required: "Please enter a password.",
          minlength: "Password must be at least 8 characters long.",
        },
        confirmPassword: {
          required: "Please confirm your password.",
          equalTo: "Passwords do not match.",
        },
      },
      errorPlacement: function (error, element) {
        if (element.attr("name") == "password") {
          error.insertAfter("#newPassErrMsg");
        } else if (element.attr("name") == "confirmPassword") {
          error.insertAfter("#confirmPassErrMsg");
        } else if (element.attr("name") == "old-password") {
          error.insertAfter("#oldPassErrMsg");
        }
      },
      highlight: function (element, errorClass) {
        $(element).addClass("border-red-200"); // Add a class to change the border color
      },
      unhighlight: function (element, errorClass) {
        $(element).removeClass("border-red-200"); // Remove the class to reset the border color
      },
      errorClass: "daisy-label-text-alt text-red-500",
      // invalidHandler: function (form, validator) {
      //   // The form is invalid, disable the submit button
      //   $("#update-password-btn").addClass("daisy-btn-disabled");
      //   $("#update-password-btn").prop("disabled", true);
      // },
      // success: function (label) {
      //   // An input is valid, check if all inputs are now valid
      //   if ($("#update-password-form").valid()) {
      //     $("#update-password-btn").removeClass("daisy-btn-disabled");
      //     $("#update-password-btn").prop("disabled", false); // Enable the submit button
      //   }
      // },
      submitHandler: function (form) {
        // Form is valid, you can submit it

        handleSubmitChangePass();
      },
    });

    async function handleSubmitChangePass() {
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

        // check message if it says 'Passwords does not match

        if (response.message === "Passwords does not match") {
          Swal.fire("Passwords does not match", "", "error");
          return;
        }
        if (response.message === "Password does not match the old password") {
          Swal.fire("Your old password is incorrect", "", "error");
          return;
        }

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
          Swal.fire("Unable to update your password", "", "error");
        }
      } else if (confirm.isDenied) {
        Swal.fire("Change Password Cancelled.", "", "info");

        $("#update-password-form")[0].reset();
        $("#changePassModal").prop("checked", false);
      }
    }
    // // // Update Password Handler update-password-btn
    $("#update-password-form").submit(async function (e) {
      e.preventDefault();
    });

    // End Update Password Handler

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

    // cancel-edit-profile-btn hanlder
    $("#cancel-edit-profile-btn").on("click", function () {
      const container = $("#content-container");
      animateOpactityTransitionOnContainer(
        container,
        $("#login-security-container"),
        $("#edit-profile-container")
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
          // return to the login security container

          animateOpactityTransitionOnContainer(
            container,
            $("#login-security-container"),
            $("#edit-profile-container")
          );
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
      animateOpactityTransitionOnContainer(
        container,
        $("#login-security-container"),
        $("#edit-profile-container")
      );
    });
  }
});
