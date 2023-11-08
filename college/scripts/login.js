// constant variables
const successLogin = "./index.php";
const SIGN_IN_URL = "./php/userData.php";

function toggleInvalidClass(element) {
  element.removeClass("valid");
  element.addClass("invalid");
}
function toggleValidClass(element) {
  element.removeClass("invalid");
  element.addClass("valid");
}

function toggleInputClassValid(input) {
  input.addClass("valid-input");
  input.removeClass("invalid-input");
}

function toggleInputClassInvalid(input) {
  input.addClass("invalid-input");
  input.removeClass("valid-input");
}

$(document).ready(function () {
  // Animation
  const loginPanel = $("#loginPanel");
  const stillConnected = $("#stillConnected");
  // for large screen
  loginPanel.removeClass("lg:-translate-x-1/2");
  stillConnected.removeClass("lg:translate-x-1/2");

  // for mobile
  stillConnected.removeClass("translate-y-1/2");
  loginPanel.removeClass("-translate-y-1/2");

  let usernameAvailable = true;
  let passwordReqFulFill = true;

  const validHTMLContent = `<i class="fa-solid fa-check"></i> Valid`;
  const invalidHTMLContent = `<i class="fa-solid fa-xmark"></i> Invalid: This value must not be null`;
  // On Blur
  $(".logInput").on("blur", function (event) {
    const elem = $(this);
    const messageElem = $(this).siblings(".input-msg");

    if (elem.val() === "") {
      toggleInvalidClass(messageElem);
      toggleInputClassInvalid(elem);
      messageElem.html(invalidHTMLContent);
    } else {
      toggleValidClass(messageElem);
      toggleInputClassValid(elem);
      messageElem.html(validHTMLContent);
    }
  });

  //login
  $("#loginForm").on("submit", (e) => {
    e.preventDefault();

    let allHaveVal = true;

    //traverse all the input available
    $(".logInput").each(function () {
      console.log("test");
      let val = $(this).val();
      let field;
      field =
        $(this).attr("name") === "password" ? $(".pass_details") : $(this); //look for password field

      //check if there's a value on a specific input
      if (val === "") {
        field.addClass("border-accent").removeClass("border-gray-400"); //add red border
        allHaveVal = false;
      } else field.removeClass("border-accent").addClass("border-gray-400");
    });

    //check if the the input have all value
    if (allHaveVal) {
      let formData = $("#loginForm")[0]; //get the form
      let data = new FormData(formData); //the form we will send to the php file

      //action will be using
      let action = {
        action: "read",
        query: true,
      };
      data.append("action", JSON.stringify(action));
      //perform ajax operation
      $.ajax({
        type: "POST",
        url: SIGN_IN_URL,
        data: data,
        processData: false,
        contentType: false,
        success: (response) => {
          if (response == "successful") {
            $("#errorMsg").hide();
            // Go to specified URL
            window.location.href = successLogin;
          } else $("#errorMsg").show();
        },
        error: (error) => console.log(error),
      });
    }
  });

  //go registration button
  $("#registerBtn").on("click", function () {
    $("#registrationPanel").show();
    $("#loginPanel").hide();
    $("#graduateLogo").removeClass("relative").addClass("absolute bottom-0");
  });
  //go back to user login
  $("#registerBtnBack").on("click", function () {
    $("#registrationPanel").hide();
    $("#loginPanel").show();
    $("#graduateLogo").addClass("relative").removeClass("absolute bottom-0");
  });

  //go back to personal info
  $("#backToPersonInfo").on("click", function () {
    $("#userAccountPanel").hide();
    $("#personalInfoPanel").show();
    $("#graduateLogo").removeClass("relative").addClass("absolute bottom-0");
  });

  //go next panel
  $("#registerBtnNext").on("click", function () {
    $("#loginPanel").hide();
    if (checkFields(".personalInput")) {
      $("#personalInfoPanel").hide();
      $("#userAccountPanel").show();
      $("#graduateLogo").addClass("relative").removeClass("absolute bottom-0");
    }
  });

  //register
  $("#registerForm").on("submit", (e) => {
    //prevent from submitting
    e.preventDefault();

    //check if all field are complete before sending
    let inputFields = "#registerForm input";

    if (checkFields(inputFields)) {
      //check if both password and confirm pass is the same
      let password = $("#password").val();
      let confirmPass = $("#confirmpassword").val();

      if (password === confirmPass) {
        $("#passwordWarning").hide();
        if (usernameAvailable && passwordReqFulFill) {
          let formData = $("#registerForm")[0];
          let form = new FormData(formData);
          let action = {
            action: "create",
          };
          form.append("action", JSON.stringify(action));

          //perform the registration
          $.ajax({
            url: "../PHP_process/userData.php",
            type: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: (response) => {
              if (response == "Success") {
                // shows the prompt
                $("#promptMessage").removeClass("hidden");
                $("#promptMessage").addClass("flex");

                $("#insertionMsg").html("Success! 🎉 Registration complete");
              } else {
                $("#promptMessage").removeClass("hidden");
                $("#insertionMsg")
                  .html(
                    "We're sorry to inform you that the registration was not successful. "
                  )
                  .addClass("text-xs");
              }
            },
            error: (error) => {
              console.log(error);
            },
          });
        }
      } else {
        $("#passwordWarning").show();
      }
    }
  });

  $("#usernameField").on("change", function () {
    checkUsername();
  });

  //checking username
  function checkUsername() {
    let username = $("#usernameField").val();

    let form = new FormData();
    var data = {
      action: "read",
      query: false,
    };
    form.append("username", username);
    form.append("action", JSON.stringify(data));
    $.ajax({
      url: "../PHP_process/userData.php",
      type: "POST",
      data: form,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == "exist") {
          $("#usernameField")
            .addClass("border-accent")
            .removeClass("border-gray-400");
          $("#usernameWarning").show();
          usernameAvailable = false;
        } else {
          $("#usernameField")
            .removeClass("border-accent")
            .addClass("border-gray-400");
          $("#usernameWarning").hide();
          usernameAvailable = true;
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  //checking field
  function checkFields(element) {
    let allComplete = true;

    //check if all fields are complete
    $(element).each(function () {
      let inputVal = $(this).val();

      //check if the particular field is empty or not
      if (inputVal == "") {
        $(this).addClass("border border-accent").removeClass("border-gray-400");
        allComplete = false;
      } else $(this).removeClass("border border-accent").addClass("border border-gray-400");
    });

    return allComplete;
  }

  //check if the password has the requirement
  $("#password").on("input", function () {
    let passwordVal = $(this).val();

    let hasUpperCase = false;
    let hasNumber = false;
    let hasLowerCase = false;
    let hasMinChar = false;

    for (let i = 0; i < passwordVal.length; i++) {
      let character = passwordVal[i];

      // Check if it has a capital letter
      if (character == character.toUpperCase()) {
        hasUpperCase = true;
      }

      // Check if it has a number
      if (!isNaN(character)) {
        hasNumber = true;
      }

      // Check if it has a lowercase letter
      if (character == character.toLowerCase()) {
        hasLowerCase = true;
      }

      // Check if it has a minimum length of 8 characters
      if (passwordVal.length >= 8) {
        hasMinChar = true;
      }
    }

    // Update the requirement indicators
    if (hasUpperCase) $("#addUpperCase").addClass("text-green-500");
    else $("#addUpperCase").removeClass("text-green-500");

    if (hasNumber) $("#addNumber").addClass("text-green-500");
    else $("#addNumber").removeClass("text-green-500");

    if (hasLowerCase) $("#addLowerCase").addClass("text-green-500");
    else $("#addLowerCase").removeClass("text-green-500");

    if (hasMinChar) $("#minChar").addClass("text-green-500");
    else $("#minChar").removeClass("text-green-500");

    passwordReqFulFill =
      hasUpperCase && hasLowerCase && hasNumber && hasNumber ? true : false;
  });
});
