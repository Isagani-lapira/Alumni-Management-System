$(document).ready(function () {
  let usernameAvailable = true;
  let personalEmailAvailable = true;
  async function postJSONFromURL(url, formData = null) {
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    return result;
  }
  const today = new Date().toISOString().split("T")[0];
  $('input[type="date"]').attr("max", today);

  //login
  $("#loginPanel").on("submit", function (e) {
    e.preventDefault();
    let formData = $("#loginForm")[0]; //get the form
    let data = new FormData(formData); //the form we will send to the php file
    //action will be using
    let action = {
      action: "read",
      query: true,
    };
    data.append("action", JSON.stringify(action));

    $.ajax({
      type: "POST",
      url: "../PHP_process/userData.php",
      data: data,
      contentType: false,
      processData: false,
      success: (response) => {
        if (response == "unsuccessful") $("#errorMsg").show();
        else {
          $("#errorMsg").hide();
          window.location.href = "../student-alumni/homepage.php";
        }
      },
      error: (error) => console.log(error),
    });
  });

  // $("#registrationForm").on("submit", function (e) {
  //   e.preventDefault();

  //   let action = {
  //     action: "create",
  //     account: "User",
  //   };
  //   let formData = new FormData(this);
  //   formData.append("action", JSON.stringify(action));

  //   console.log(FormData);

  //   // show the loading screen
  //   $("#loadingScreen").removeClass("hidden");
  //   // hide the registration form
  //   $("#registrationForm").addClass("hidden");
  //   // show the email-code-container
  //   $("#email-code-container").removeClass("hidden");

  //   return;
  //   //register the person
  //   // $.ajax({
  //   //   type: "POST",
  //   //   url: "../PHP_process/userData.php",
  //   //   data: formData,
  //   //   processData: false,
  //   //   contentType: false,
  //   //   success: (response) => {
  //   //     console.log(response);
  //   //   },
  //   //   error: (error) => {
  //   //     console.log(error);
  //   //   },
  //   // });
  // });

  //check if the username already existing
  $("#usernameReg").on("change", function () {
    checkUsername();
  });

  //checking username
  function checkUsername() {
    let username = $("#usernameReg").val();

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
          $("#usernameReg")
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

  //check if the email already existing
  $("#personalEmail").on("change", function () {
    checkEmailAddress();
  });

  function checkEmailAddress() {
    let personalEmail = $("#personalEmail").val();

    let form = new FormData();
    var data = {
      action: "read",
    };
    form.append("personalEmail", personalEmail);
    form.append("action", JSON.stringify(data));
    $.ajax({
      url: "../PHP_process/person.php",
      type: "POST",
      data: form,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == "Exist") {
          $("#personalEmail")
            .addClass("border-accent")
            .removeClass("border-gray-400");
          $("#emailExist").show();
          personalEmailAvailable = false;
        } else {
          $("#personalEmail")
            .removeClass("border-accent")
            .addClass("border-gray-400");
          $("#emailExist").hide();
          personalEmailAvailable = true;
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  // for registration
  // email-code-container
  // verify-email-text

  const acceptButton = $("#acceptButton");

  const checkbox = $("#privacyPolicyCheckbox");
  checkbox.change(function () {
    acceptButton.prop("disabled", !checkbox.prop("checked"));

    // if (checkbox.prop("checked")) {
    //   acceptButton
    //     .removeClass("bg-gray-400")
    //     .addClass("bg-green-500 hover:bg-green-700 text-white");
    // } else {
    //   acceptButton
    //     .removeClass("bg-green-500 hover:bg-green-700 text-white")
    //     .addClass("bg-gray-400 text-black");
    // }
  });

  // accpet button on terms modal
  $("#acceptButton").on("click", function () {
    // get the data-selected
    const selected = $(this).attr("data-selected");
    console.log("selected", selected);

    // toggle the #terms-modal checkbox
    $("#terms-modal").prop("checked", false);

    // check if it is alumni
    if (selected === "alumni") {
      // hide the selection status
      $(".selectionStatus").addClass("hidden");
      // show the alumni form
      $("#alumniForm").removeClass("hidden");
    } else if (selected === "student") {
      // hide the selection status
      $(".selectionStatus").addClass("hidden");
      // show the student form
      $("#studentForm").removeClass("hidden");
    }
    // hide the

    // $('.emailExistingMsg').addClass('hidden')
    $(".selectionStatus").addClass("hidden");
    // $('#alumniForm').removeClass('hidden')

    // $(".emailExistingMsg").addClass("hidden");
    // $(".selectionStatus").addClass("hidden");
    // $("#studentForm").removeClass("hidden");
  });
  // alumni is selected

  $("#alumniStatus").on("click", function () {
    // set the data-selected of acceptBtn
    $("#acceptButton").attr("data-selected", "alumni");
  });

  $("#studentStatus").on("click", function () {
    // set the data-selected of acceptBtn
    $("#acceptButton").attr("data-selected", "student");
  });
  // cancel registration
  $(".cancelBtnReg").on("click", function () {
    $(".selectionStatus").removeClass("hidden");
    $(".studExistingMsg").addClass("hidden");
    $(".fieldFormReg").addClass("hidden");
    $(".emailInvalidMsg ").addClass("hidden");
    //restart forms
    $("#alumniForm")[0].reset();
    $("#studentForm")[0].reset();
  });

  // password eye
  $("#alumniPassEye").on("click", function () {
    const icon = "#alumniPassEye";
    const password = "#accountPass";
    togglePassword(icon, password);
  });

  // confirm password eye
  $("#alumniConfirmPassEye").on("click", function () {
    const icon = "#alumniConfirmPassEye";
    const password = "#confirmPass";
    togglePassword(icon, password);
  });

  function togglePassword(icon, password) {
    let eyeCurrentState = $(icon).attr("icon");

    if (eyeCurrentState == "bi:eye-fill") {
      // open the password
      $(icon).attr("icon", "el:eye-close");
      $(password).attr("type", "text");
    } else {
      // close the password
      $(icon).attr("icon", "bi:eye-fill");
      $(password).attr("type", "password");
    }
  }

  $("#nextAlumni").on("click", function () {
    // check first if all the input fields are complete
    if (checkInputField(".requiredAlumni")) {
      // check if the email is already existing or not
      const emailAdd = $("#personalEmail").val();
      const column = "personal_email";
      const studNo = $("#studNo").val();
      checkStudentNo(studNo).then((value) => {
        console.log(value);
        if (value === "Available") {
          $(".studExistingMsg").addClass("hidden");
          // check if the email ends with proper gmail
          if (emailAdd.endsWith("@gmail.com")) {
            $(".emailInvalidMsg").addClass("hidden");

            // check if the email is already existing
            checkEmailAddress(emailAdd, column).then((resolve) => {
              if (resolve !== "Existing") {
                //proceed to the next
                $(".emailExistingMsg").addClass("hidden");
                $(".personalInfo").addClass("hidden");
                $("#accountInfoAlumni").removeClass("hidden");
              } else $(".emailExistingMsg").removeClass("hidden");
            });
          } else $(".emailInvalidMsg").removeClass("hidden");
        } else $(".studExistingMsg").removeClass("hidden");
      });
    }
  });

  // check if the input email is already existing
  function checkEmailAddress(emailAdd, column) {
    const action = { action: "checkPersonEmail" };
    const formData = new FormData();
    formData.append("action", JSON.stringify(action));
    formData.append("email", emailAdd);
    formData.append("column", column);

    return new Promise((resolve) => {
      $.ajax({
        url: "../PHP_process/userData.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: (response) => {
          resolve(response);
        },
      });
    });
  }

  function checkStudentNo(studNo) {
    const action = { action: "checkStudNo" };
    const formData = new FormData();
    formData.append("action", JSON.stringify(action));
    formData.append("studNo", studNo);

    return new Promise((resolve) => {
      $.ajax({
        url: "../PHP_process/userData.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: (response) => resolve(response),
        error: (error) => {
          console.log(error);
        },
      });
    });
  }
  // back to personal information field
  $("#backAlumni").on("click", function () {
    $(".personalInfo").removeClass("hidden");
    $("#accountInfoAlumni").addClass("hidden");
  });

  let isUsernameValid = false;
  let isPasswordStrong = false;

  // check alumni username is available
  $("#username").on("change", function () {
    let usernameVal = $(this).val();
    isUsernameAvailable(usernameVal).then((response) => {
      // user name is already exist
      if (response == "exist") {
        $(".usernameMsg").removeClass("hidden");
        isUsernameValid = false;
      } else {
        // valid
        $(".usernameMsg").addClass("hidden");
        isUsernameValid = true;
      }
    });
  });

  function checkInputField(className) {
    let isCompleted = true;
    $(className).each(function () {
      let element = $(this);
      let elementVal = element.val().trim();

      if (elementVal === "") {
        isCompleted = false;
        element.removeClass("border-gray-400").addClass("border-red-500");
      } else {
        element.addClass("border-gray-400").removeClass("border-red-500");
      }
    });

    return isCompleted;
  }

  // username availability checker
  function isUsernameAvailable(username) {
    const action = {
      action: "read",
      query: 0,
    };

    const formData = new FormData();
    formData.append("action", JSON.stringify(action));
    formData.append("username", username);

    // return the response to be use in other function
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../PHP_process/userData.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: (response) => {
          resolve(response);
        },
        error: (error) => {
          reject(error);
        },
      });
    });
  }

  //add batch option
  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const lastYear = 1904;

  for (let i = currentYear; i > lastYear; i--) {
    const option = $("<option>").val(i).text(i);
    $("#batchAlumni").append(option);
  }

  $("#college").on("change", function () {
    const college = $(this).val();
    const container = "#courses";
    addOptionCourse(college, container);
  });
  $("#studCollege").on("change", function () {
    const college = $(this).val();
    const container = "#courseStudent";
    addOptionCourse(college, container);
  });

  function addOptionCourse(college, container) {
    let data = {
      action: "courses",
      query: true,
    };

    const formData = new FormData();
    formData.append("colCode", college);
    formData.append("data", JSON.stringify(data));

    $.ajax({
      url: "../PHP_process/collegeDB.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: (response) => {
        if (response.response === "Success") {
          $(container).find("option:not(:first)").remove();
          let length = response.courseName.length;

          // add the college in the option
          for (let i = 0; i < length; i++) {
            const courseID = response.courseID[i];
            const courseName = response.courseName[i];
            const option = $("<option>").val(courseID).text(courseName);
            $(container).append(option);
          }
        }
      },
    });
  }
  // student form process

  $("#nextStudent").on("click", function () {
    // check first if all the input fields are complete
    if (checkInputField(".requiredStudenField")) {
      //to verify if the email set as valid bulsu email
      let isBulSUEmailValid = false;
      isBulSUEmailValid = $("#studbulsuEmail").val().endsWith("@bulsu.edu.ph");

      // check if the email is existing or not
      const studPersonEmail = $("#studPersonalEmail").val();
      const column = "personal_email";
      const studstudNo = $("#studstudNo").val();
      // check first the student number
      checkStudentNo(studstudNo).then((value) => {
        console.log(value);
        if (value === "Available") {
          $(".studExistingMsg").addClass("hidden");

          // validate if the email is correct format
          if (studPersonEmail.endsWith("@gmail.com")) {
            $(".emailInvalidMsg").addClass("hidden");
            checkEmailAddress(studPersonEmail, column).then((resolve) => {
              if (resolve !== "Existing") {
                if (isBulSUEmailValid) {
                  // check if the bulsu email is not already existing
                  let bulsuEmail = $("#studbulsuEmail").val();
                  let columnBulsu = "bulsu_email";
                  $(".emailExistingMsg").addClass("hidden");

                  checkEmailAddress(bulsuEmail, columnBulsu).then((resolve) => {
                    if (resolve !== "Existing") {
                      //proceed to the next
                      $(".emailExistingMsgBulsu").addClass("hidden");
                      $("#bulsuEmailError").addClass("hidden");
                      $(".personalInfo").addClass("hidden");
                      $("#accountInfoStudent").removeClass("hidden");
                    } else $(".emailExistingMsgBulsu").removeClass("hidden");
                  });
                } else $("#bulsuEmailError").removeClass("hidden");
              } else $(".emailExistingMsg").removeClass("hidden");
            });
          } else $(".emailInvalidMsg").removeClass("hidden");
        } else $(".studExistingMsg").removeClass("hidden");
      });
    }
  });

  // back to personal information
  $("#backStudent").on("click", function () {
    $(".personalInfo").removeClass("hidden");
    $("#accountInfoStudent").addClass("hidden");
  });

  // check password if meets the requirement of strong password
  $("#accountPass").on("input", function () {
    let passwordVal = $(this).val();
    // special characters, numbers, lower and upper case
    const strongpassReq =
      /[A-Z]/.test(passwordVal) &&
      /[a-z]/.test(passwordVal) &&
      /[^A-Za-z0-9]/.test(passwordVal) &&
      /[0-9]/.test(passwordVal);
    const requiredPassLength = 8;

    if (strongpassReq && passwordVal.length >= requiredPassLength) {
      //strong password
      $(".passwordStatus")
        .text("Strong password")
        .removeClass("text-red-500")
        .addClass("text-blue-500");
      isPasswordStrong = true;
    } else {
      $(".passwordStatus")
        .text("Weak password")
        .addClass("text-red-500")
        .removeClass("text-blue-500");
      isPasswordStrong = false;
    }
  });

  // password eye
  $("#studentPassEye").on("click", function () {
    const icon = "#studentPassEye";
    const password = "#studAccountPass";
    togglePassword(icon, password);
  });

  // confirm password eye
  $("#studentConfirmPassEye").on("click", function () {
    const icon = "#studentConfirmPassEye";
    const password = "#studConfirmPass";
    togglePassword(icon, password);
  });

  // check student username is available
  $("#studUsername").on("change", function () {
    let usernameVal = $(this).val();
    isUsernameAvailable(usernameVal).then((response) => {
      // user name is already exist
      if (response == "exist") {
        $(".usernameMsg").removeClass("hidden");
        isUsernameValid = false;
      } else {
        // valid
        $(".usernameMsg").addClass("hidden");
        isUsernameValid = true;
      }
    });
  });

  // check password if meets the requirement of strong password
  $("#studAccountPass").on("input", function () {
    let passwordVal = $(this).val();
    const strongpassReq =
      /[A-Z]/.test(passwordVal) &&
      /[a-z]/.test(passwordVal) &&
      /[^A-Za-z0-9]/.test(passwordVal) &&
      /[0-9]/.test(passwordVal);
    const requiredPassLength = 8;

    if (strongpassReq && passwordVal.length >= requiredPassLength) {
      //strong password
      $(".passwordStatus")
        .text("Strong password")
        .removeClass("text-red-500")
        .addClass("text-blue-500");
      isPasswordStrong = true;
    } else {
      $(".passwordStatus")
        .text("Weak password")
        .addClass("text-red-500")
        .removeClass("text-blue-500");
      isPasswordStrong = false;
    }
  });

  $("#studentForm").on("submit", async function (e) {
    e.preventDefault();

    // check first if all the input are complete
    if (checkInputField(".requiredStudent2")) {
      // check if the username is valid
      if (isUsernameValid && isPasswordStrong) {
        const studAccountPass = $("#studAccountPass").val();
        const studConfirmPass = $("#studConfirmPass").val();

        // check if password matches
        console.log("strong pass");
        if (studAccountPass == studConfirmPass) {
          $(".errorPassNotMatch").addClass("hidden");

          // register new account
          let formData = $("#studentForm")[0];
          let action = {
            action: "create",
            account: "User",
          };

          let data = new FormData(formData);
          data.append("action", JSON.stringify(action));
          data.append("status", "Student");

          console.log(data);

          //  show loading screen
          $("#loadingScreen").removeClass("hidden");
          // check email

          handleEmailVerification(data, $("#studentForm"));

          // send the data to the server

          // $.ajax({
          //   url: "../PHP_process/userData.php",
          //   method: "POST",
          //   data: data,
          //   processData: false,
          //   contentType: false,
          //   success: (response) => {
          //     if (response === "Success")
          //       $("#successJobModal").removeClass("hidden");
          //   },
          //   error: (error) => {
          //     console.log(error);
          //   },
          // });
        } else {
          $(".errorPassNotMatch").removeClass("hidden");
          console.log("weak pass pass");
        }
      }
    } else {
      console.log("not completed yet.");
    }
  });

  $("#alumniForm").on("submit", function (e) {
    e.preventDefault();

    if (checkInputField(".requiredAlumni2")) {
      let accountPass = $("#accountPass").val().trim();
      let confirmPass = $("#confirmPass").val().trim();

      if (isUsernameValid) {
        // check account password if match
        if (accountPass === confirmPass) {
          // register new account
          let formData = $("#alumniForm")[0];
          let action = {
            action: "create",
            account: "User",
          };

          let data = new FormData(formData);
          data.append("action", JSON.stringify(action));
          data.append("status", "Alumni");
          data.append("bulsuEmail", "");
          $("#loadingScreen").removeClass("hidden");

          handleEmailVerification(data, $("#alumniForm"));
        } else $(".errorPassNotMatch").removeClass("hidden");
      }
    }
  });

  // handle verify code form
  $("#verify-code-btn").on("click", async function () {
    // #code value
    const code = $("#code").val();

    // get the email address
    const email = $("#verify-email-text").text();

    // get the data-selected for the register btn
    const selected = $("#acceptButton").attr("data-selected");
    console.log("selected", selected);

    // get the form data
    const formData = new FormData();
    formData.append("verification_code", code);
    formData.append("email_address", email);
    formData.append("action", "verify_code");

    try {
      // check if the code is correct
      const response = await postJSONFromURL(
        "../PHP_process/emailVerif.php",
        formData
      );

      console.log(response);
      const errorMsg = response.message;

      if (response.success === true) {
        // user data

        // // hide the email-code-container
        // $("#email-code-container").addClass("hidden");

        // // show the loading screen
        // $("#loadingScreen").removeClass("hidden");

        // // hide the loading screen after 3 seconds
        // setTimeout(() => {
        //   $("#loadingScreen").addClass("hidden");
        //   $("#successJobModal").removeClass("hidden");
        // }, 3000);

        // check if it is alumni or student in acceptButton
        // get the data-selected for the register btn
        const selected = $("#acceptButton").attr("data-selected");
        console.log("selected", selected);
        // get the form data if it is alummi
        let formData = new FormData();
        if (selected === "alumni") {
          formData = new FormData($("#alumniForm")[0]);
        } else if (selected === "student") {
          formData = new FormData($("#studentForm")[0]);
        }

        const action = {
          action: "create",
          account: "User",
        };
        formData.append("action", JSON.stringify(action));

        $.ajax({
          url: "../PHP_process/userData.php",
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: (response) => {
            if (response === "Success") {
              $(".errorPassNotMatch").addClass("hidden");

              $("#email-code-container").removeClass("hidden");
              $("#successJobModal").removeClass("hidden");
            }
            console.log(response);
          },
        });

        // show the success modal
      } else {
        // send sweet alert
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: errorMsg,
        });
      }
    } catch (error) {
      console.log(error);
      const errorMsg = "Something went wrong. Please try again later.";
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: errorMsg,
      });
      return;
    }
  });

  // back-registration-btn
  $("#back-registration-btn").on("click", function () {
    // get the data-selected for the register btn
    const selected = $("#acceptButton").attr("data-selected");
    console.log("selected", selected);

    // check if it is alumni
    if (selected === "alumni") {
      // hide the selection status
      // show the alumni form
      $("#alumniForm").removClass("hidden");
    } else if (selected === "student") {
      // hide the selection status
      // show the student form
      $("#studentForm").removeClass("hidden");
    }
    //
    $("#email-code-container").removeClass("hidden");
  });

  // handle resend code

  $("#resend-code-btn").on("click", function () {
    // get the email address
    const email = $("#verify-email-text").text();

    // get the form data
    const formData = new FormData();
    formData.append("personalEmail", email);
    formData.append("action", "send_otp");

    handleResendEmailVerification(formData, $("#email-code-container"));

    // show the loading screen
    $("#loadingScreen").removeClass("hidden");

    // hide the loading screen after 3 seconds
    setTimeout(() => {
      $("#loadingScreen").addClass("hidden");
    }, 3000);
    $("#email-code-container").removeClass("hidden");

    // hide the email-code-container
  });

  async function handleResendEmailVerification(formData, container) {
    console.log(formData);

    const emailData = new FormData();
    emailData.append("action", "resend_otp");
    emailData.append("email_address", formData.get("personalEmail"));

    $("#verify-email-text").text(formData.get("personalEmail"));

    try {
      // send post request to the server
      const response = await postJSONFromURL(
        "../PHP_process/emailVerif.php",
        emailData
      );

      console.log(response);

      if (response.success === true) {
        console.log("ok");

        container.addClass("hidden");
        $("#loadingScreen").addClass("hidden");
        // check if it is alumni or student
        // show the email-code-container
        $("#email-code-container").removeClass("hidden");

        // $.ajax({
        //   url: "../PHP_process/userData.php",
        //   method: "POST",
        //   data: data,
        //   processData: false,
        //   contentType: false,
        //   success: (response) => {
        //     if (response === "Success") {
        //       $(".errorPassNotMatch").addClass("hidden");

        //       $("#email-code-container").removeClass("hidden");
        //       $("#successJobModal").removeClass("hidden");
        //     }
        //   },
        // });
      } else {
        console.log("not ok. did not return success");
        $("#loadingScreen").addClass("hidden");
        container.removeClass("hidden");
        // add error text
        $("#email-error-text").text(response.error);
        // add sweet alert with message
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: response.message,
        });
      }
    } catch (error) {
      console.log(error);
      $("#loadingScreen").remove("hidden");
      container.removeClass("hidden");
      // add error text
      $("#email-error-text").text("Something went wrong. Please try again.");
    }
  }

  async function handleEmailVerification(formData, container) {
    console.log(formData);

    const emailData = new FormData();
    emailData.append("action", "send_otp");
    emailData.append("email_address", formData.get("personalEmail"));

    $("#verify-email-text").text(formData.get("personalEmail"));

    try {
      // send post request to the server
      const response = await postJSONFromURL(
        "../PHP_process/emailVerif.php",
        emailData
      );

      console.log(response);

      if (response.success === true) {
        console.log("ok");

        container.addClass("hidden");
        $("#loadingScreen").addClass("hidden");
        // check if it is alumni or student
        // show the email-code-container
        $("#email-code-container").removeClass("hidden");

        // $.ajax({
        //   url: "../PHP_process/userData.php",
        //   method: "POST",
        //   data: data,
        //   processData: false,
        //   contentType: false,
        //   success: (response) => {
        //     if (response === "Success") {
        //       $(".errorPassNotMatch").addClass("hidden");

        //       $("#email-code-container").removeClass("hidden");
        //       $("#successJobModal").removeClass("hidden");
        //     }
        //   },
        // });
      } else {
        console.log("not ok. did not return success");
        $("#loadingScreen").addClass("hidden");
        container.removeClass("hidden");
        // add error text
        $("#email-error-text").text(response.error);
        // add sweet alert with message
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: response.message,
        });
      }
    } catch (error) {
      console.log(error);
      $("#loadingScreen").remove("hidden");
      container.removeClass("hidden");
      // add error text
      $("#email-error-text").text("Something went wrong. Please try again.");
    }
  }
});

$(document).ready(function () {
  $("#toggleLoginPassword").on("click", function () {
    var passwordField = $("#password");
    var passwordIcon = $("#toggleLoginPassword");

    if (passwordField.attr("type") === "password") {
      passwordField.attr("type", "text");
      passwordIcon.removeClass("fa-eye-slash").addClass("fa-eye");
    } else {
      passwordField.attr("type", "password");
      passwordIcon.removeClass("fa-eye").addClass("fa-eye-slash");
    }
  });
});
