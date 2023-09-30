let usernameAvailable = true;
let personalEmailAvailable = true;
$(document).ready(function () {

  //go registration button
  $('#registerBtn').on('click', function () {
    $('#registrationPanel').show();
    $('#loginPanel').hide();
    $('#graduateLogo').removeClass('relative').addClass('absolute bottom-0')
  })


  //login
  $('#loginPanel').on('submit', function (e) {
    e.preventDefault();
    let formData = $('#loginForm')[0]; //get the form 
    let data = new FormData(formData); //the form we will send to the php file
    //action will be using
    let action = {
      action: 'read',
      query: true,
    }
    data.append('action', JSON.stringify(action));

    $.ajax({
      type: 'POST',
      url: "../PHP_process/userData.php",
      data: data,
      contentType: false,
      processData: false,
      success: (response) => {
        if (response == 'unsuccessful') $('#errorMsg').show();
        else {
          $('#errorMsg').hide()
          window.location.href = "../student-alumni/homepage.php"
        }
      },
      error: (error) => console.log(error)
    })
  })

  $('#registrationForm').on('submit', function (e) {
    e.preventDefault();
    let action = {
      action: 'create',
      account: 'User'
    }
    let formData = new FormData(this)
    formData.append('action', JSON.stringify(action))

    //register the person
    $.ajax({
      type: "POST",
      url: "../PHP_process/userData.php",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => { console.log(response) },
      error: (error) => { console.log(error) }
    })
  })

  //check if the username already existing
  $('#usernameReg').on('change', function () {
    checkUsername()
  })

  //checking username
  function checkUsername() {
    let username = $('#usernameReg').val();

    let form = new FormData();
    var data = {
      action: 'read',
      query: false,
    };
    form.append('username', username);
    form.append('action', JSON.stringify(data))
    $.ajax({
      url: '../PHP_process/userData.php',
      type: 'POST',
      data: form,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == 'exist') {
          $('#usernameReg').addClass('border-accent').removeClass('border-gray-400');
          $('#usernameWarning').show();
          usernameAvailable = false;
        }
        else {
          $('#usernameField').removeClass('border-accent').addClass('border-gray-400');
          $('#usernameWarning').hide();
          usernameAvailable = true;
        }
      },
      error: (error) => {
        console.log(error)
      }
    })

  }

  //check if the email already existing
  $('#personalEmail').on('change', function () {
    checkEmailAddress()
  })

  function checkEmailAddress() {
    let personalEmail = $('#personalEmail').val();

    let form = new FormData();
    var data = {
      action: 'read',
    };
    form.append('personalEmail', personalEmail);
    form.append('action', JSON.stringify(data))
    $.ajax({
      url: '../PHP_process/person.php',
      type: 'POST',
      data: form,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == 'Exist') {
          $('#personalEmail').addClass('border-accent').removeClass('border-gray-400');
          $('#emailExist').show();
          personalEmailAvailable = false;
        }
        else {
          $('#personalEmail').removeClass('border-accent').addClass('border-gray-400');
          $('#emailExist').hide();
          personalEmailAvailable = true;
        }
      },
      error: (error) => {
        console.log(error)
      }
    })
  }
});

// Get the necessary elements
const page1 = document.getElementById("page1");
const page2 = document.getElementById("page2");
const nextButton = document.getElementById("nextButton");
const circle1 = document.getElementById("circle1");
const circle2 = document.getElementById("circle2");
const circle3 = document.getElementById("circle3");
const connector1 = document.getElementById("connector1");
const connector2 = document.getElementById("connector2");


// PAGE 1
nextButton.addEventListener("click", function () {
  // Perform form validation
  const firstName = document.getElementById("firstName");
  const lastName = document.getElementById("lastName");
  const email = document.getElementById("personalEmail");
  const contactNumber = document.getElementById("contactNumber");
  const studentNumber = document.getElementById("studentNumber");
  const birthday = document.getElementById("birthday");
  const genderOptions = document.querySelectorAll('input[name="gender"]');
  const genderLabels = document.querySelectorAll('.gender-label');

  // Check if any field is empty
  let hasError = false;

  if (firstName.value.trim() === "") {
    firstName.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    firstName.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }

  if (lastName.value.trim() === "") {
    lastName.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    lastName.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }

  if (email.value.trim() === "") {
    email.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    email.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }

  if (contactNumber.value.trim() === "") {
    contactNumber.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    contactNumber.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }

  if (studentNumber.value.trim() === "") {
    studentNumber.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    studentNumber.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }

  if (birthday.value.trim() === "") {
    birthday.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    birthday.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }

  let genderSelected = false;
  for (let i = 0; i < genderOptions.length; i++) {
    if (genderOptions[i].checked) {
      genderSelected = true;
      break;
    }
  }

  if (!genderSelected) {
    // If no gender is selected, highlight the text of the radio buttons in red
    for (let i = 0; i < genderLabels.length; i++) {
      genderLabels[i].style.color = "#991B1B";
    }
    hasError = true;
  } else {
    // If a gender is selected, set the default color for the text of the radio buttons
    for (let i = 0; i < genderLabels.length; i++) {
      genderLabels[i].style.color = "#000000";
    }
  }

  // Check if any field is empty
  if (hasError || !personalEmailAvailable) {
    return;
  }

  // Hide Page 1 and show Page 2
  page1.classList.add("hidden");
  page2.classList.remove("hidden");
  circle2.style.backgroundColor = "#991B1B";
  connector1.style.backgroundColor = "#991B1B";
});

// PAGE 1 - Add event listeners for input fields to reset the border color
firstName.addEventListener("input", function () {
  if (firstName.value.trim() === "") {
    firstName.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
  } else {
    firstName.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
  }
});

lastName.addEventListener("input", function () {
  if (lastName.value.trim() === "") {
    lastName.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
  } else {
    lastName.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
  }
});

email.addEventListener("input", function () {
  if (email.value.trim() === "") {
    email.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
  } else {
    email.style.borderColor = "var(--gray-300)"; // Set border color to gray-300
  }
});

contactNumber.addEventListener("input", function () {
  if (contactNumber.value.trim() === "") {
    contactNumber.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
  } else {
    contactNumber.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
  }
});

studentNumber.addEventListener("input", function () {
  if (studentNumber.value.trim() === "") {
    studentNumber.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
  } else {
    studentNumber.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
  }
});

birthday.addEventListener("input", function () {
  if (birthday.value.trim() === "") {
    birthday.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
  } else {
    birthday.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
  }
});


const page3 = document.getElementById("page3");
const nextButtonPage2 = document.getElementById("nextButtonPage2");
const backButton = document.getElementById("backButton");

// PAGE 2
nextButtonPage2.addEventListener("click", function () {
  // Perform form validation
  const college1 = document.getElementById("college");
  const username = document.getElementById("usernameReg");
  const batch = document.getElementById("batch");
  const statusOptions = document.querySelectorAll('input[name="status"]');
  const emailBSU = document.getElementById("email");
  const password1 = document.getElementById("password1");
  const confirmPassword = document.getElementById("confirmPassword");
  const passwordMismatchError = document.getElementById("passwordMismatchError");
  const reqInputAns = document.getElementById("reqInputAns");
  const passDetailsDiv = document.getElementById("passDetailsDiv");
  const confirmPassDetailsDiv = document.getElementById("confirmPassDetailsDiv");
  const employmentStatus = document.getElementById("employment-status");
  const employmentStatusDiv = document.getElementById("employment-status-div");

  let hasError = false;
  if (username.value.trim() === "") {
    username.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    username.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }
  if (college1.value.trim() === "") {
    college1.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    college1.style.borderColor = "#9CA3AF"; // Set default color for filled field
  }

  // Check if the selected status is "Student"
  let isStudent = false;
  for (let i = 0; i < statusOptions.length; i++) {
    if (statusOptions[i].checked && statusOptions[i].value === "Student") {
      isStudent = true;
      break;
    }
  }

  if (!isStudent) {
    // If the selected status is not "Student," perform validation for the employmentStatus field
    if (employmentStatus.value.trim() === "") {
      employmentStatusDiv.classList.add("validation-border"); // Add validation class to show red border
      hasError = true;
    } else {
      employmentStatusDiv.classList.remove("validation-border"); // Remove validation class to reset border
    }
  }

  let statusSelected = false;
  for (let i = 0; i < statusOptions.length; i++) {
    if (statusOptions[i].checked) {
      statusSelected = true;
      break;
    }
  }

  if (!statusSelected) {
    // If no status is selected, highlight the radio buttons' labels in accent color
    const statusLabels = document.querySelectorAll('.status-label');
    for (let i = 0; i < statusLabels.length; i++) {
      statusLabels[i].style.color = "#991B1B";
    }
    hasError = true;
  } else {
    // If a status is selected, set the default color for the radio buttons' labels
    const statusLabels = document.querySelectorAll('.status-label');
    for (let i = 0; i < statusLabels.length; i++) {
      statusLabels[i].style.color = "#000000";
    }
  }

  // Check if password1 field is empty or does not meet the condition
  if (!password1.checkValidity() || !isPasswordValid(password1.value)) {
    passDetailsDiv.style.borderColor = "#991B1B"; // Set accent color for empty field or invalid password
    document.querySelector(".note").style.color = "#991B1B"; // Change note color to accent color
    hasError = true;
  } else {
    passDetailsDiv.style.borderColor = "#9CA3AF"; // Set default color for filled field
    document.querySelector(".note").style.color = "#000000"; // Change note color back to default
  }

  // Check if confirmPassword field is empty or does not match password1 field
  if (confirmPassword.value.trim() === "" || confirmPassword.value !== password1.value) {
    confirmPassDetailsDiv.style.borderColor = "#991B1B"; // Set accent color for empty field or mismatch
    passwordMismatchError.classList.remove("hidden"); // Show password mismatch error message
    hasError = true;
  } else {
    confirmPassDetailsDiv.style.borderColor = "#9CA3AF"; // Set default color for filled field
    passwordMismatchError.classList.add("hidden"); // Hide password mismatch error message
  }

  // Check if any fields have errors
  if (hasError || !usernameAvailable) {
    reqInputAns.classList.remove("hidden"); // Show error message
    return; // Exit the function if there are errors
  } else {
    reqInputAns.classList.add("hidden"); // Hide error message
  }

  // Hide Page 2 and show Page 3
  page2.classList.add("hidden");
  page3.classList.remove("hidden");
  circle3.style.backgroundColor = "#991B1B";
  connector2.style.backgroundColor = "#991B1B";

  // Display summary information
  const fullName = document.getElementById("firstName").value + " " + document.getElementById("lastName").value;
  const college = document.getElementById("college").value;
  const emailPersonal = document.getElementById("personalEmail").value;
  const studentNumber = document.getElementById("studentNumber").value;
  const emailBulsu = document.getElementById("email").value;
  const password = document.getElementById("password1").value;

  document.getElementById("displayFullName").textContent = fullName;
  document.getElementById("displayCollege").textContent = college;
  document.getElementById("displayEmailPersonal").textContent = emailPersonal;
  document.getElementById("displayStudentNumber").textContent = studentNumber;
  document.getElementById("displayEmailBulsu").textContent = emailBulsu;
  document.getElementById("displayPassword").textContent = password;
});

// Function to check if the password meets the condition
function isPasswordValid(password) {
  // Use regular expression to check for 8 or more characters with a mix of letters, numbers, and symbols
  const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
  return passwordRegex.test(password);
}


// Function to show an error message
function showErrorMessage() {
  const requiredFields = accountForm.querySelectorAll(':invalid');
  const reqInputAns = document.getElementById('reqInputAns');

  // Highlight the empty fields with a red border
  requiredFields.forEach(function (field) {
    field.style.borderColor = '#991B1B';
  });

  // Show the error message
  reqInputAns.classList.remove('hidden');
}


backButton.addEventListener("click", function () {
  // Hide Page 2 and show Page 1
  page2.classList.add("hidden");
  page1.classList.remove("hidden");
  circle2.style.backgroundColor = "#FFFFFF";
  connector1.style.backgroundColor = "#FFFFFF";
});

const backButtonPage3 = document.getElementById("backButtonPage3");

backButtonPage3.addEventListener("click", function () {
  // Hide Page 3 and show Page 2
  page3.classList.add("hidden");
  page2.classList.remove("hidden");
  circle3.style.backgroundColor = "#FFFFFF";
  connector2.style.backgroundColor = "#FFFFFF";
});

//Toggle password for Page2
document.getElementById('togglePassword').addEventListener('click', function () {
  var passwordInput = document.getElementById('password1');
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    this.classList.remove('fa-eye-slash');
    this.classList.add('fa-eye');
  } else {
    passwordInput.type = 'password';
    this.classList.remove('fa-eye');
    this.classList.add('fa-eye-slash');
  }
});

//Confirmation Toggle Password for Page2
document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
  var confirmPasswordInput = document.getElementById('confirmPassword');
  if (confirmPasswordInput.type === 'password') {
    confirmPasswordInput.type = 'text';
    this.classList.remove('fa-eye-slash');
    this.classList.add('fa-eye');
  } else {
    confirmPasswordInput.type = 'password';
    this.classList.remove('fa-eye');
    this.classList.add('fa-eye-slash');
  }
});

//Login Toggle Password
document.getElementById('toggleLoginPassword').addEventListener('click', function () {
  var loginPasswordInput = document.getElementById('password');
  if (loginPasswordInput.type === 'password') {
    loginPasswordInput.type = 'text';
    this.classList.remove('fa-eye-slash');
    this.classList.add('fa-eye');
  } else {
    loginPasswordInput.type = 'password';
    this.classList.remove('fa-eye');
    this.classList.add('fa-eye-slash');
  }
});

//Hide and Show Employement Status
// Get the radio buttons and the employment status div
const alumniRadioButton = document.getElementById("alumni");
const studentRadioButton = document.getElementById("student");
const employmentStatusDiv = document.getElementById("employment-status-div");

// Add event listeners to the radio buttons
alumniRadioButton.addEventListener("change", toggleEmploymentStatus);
studentRadioButton.addEventListener("change", toggleEmploymentStatus);

// Function to toggle the display of the employment status div based on the selected radio button
function toggleEmploymentStatus() {
  if (alumniRadioButton.checked) {
    employmentStatusDiv.style.display = "block";
  } else if (studentRadioButton.checked) {
    employmentStatusDiv.style.display = "none";
  }
}
// let usernameAvailable = true;
// let personalEmailAvailable = true;
// $(document).ready(function () {

//   //go registration button
//   $('#registerBtn').on('click', function () {
//     $('#registrationPanel').show();
//     $('#loginPanel').hide();
//     $('#graduateLogo').removeClass('relative').addClass('absolute bottom-0')
//   })


//   //login
//   $('#loginPanel').on('submit', function (e) {
//     e.preventDefault();
//     let formData = $('#loginForm')[0]; //get the form 
//     let data = new FormData(formData); //the form we will send to the php file
//     //action will be using
//     let action = {
//       action: 'read',
//       query: true,
//     }
//     data.append('action', JSON.stringify(action));

//     $.ajax({
//       type: 'POST',
//       url: "../PHP_process/userData.php",
//       data: data,
//       contentType: false,
//       processData: false,
//       success: (response) => {
//         if (response == 'unsuccessful') $('#errorMsg').show();
//         else {
//           $('#errorMsg').hide()
//           window.location.href = "../student-alumni/homepage.php"
//         }
//       },
//       error: (error) => console.log(error)
//     })
//   })

//   $('#registrationForm').on('submit', function (e) {
//     e.preventDefault();
//     let action = {
//       action: 'create',
//       account: 'User'
//     }
//     let formData = new FormData(this)
//     formData.append('action', JSON.stringify(action))

//     //register the person
//     $.ajax({
//       type: "POST",
//       url: "../PHP_process/userData.php",
//       data: formData,
//       processData: false,
//       contentType: false,
//       success: (response) => { console.log(response) },
//       error: (error) => { console.log(error) }
//     })
//   })

//   //check if the username already existing
//   $('#usernameReg').on('change', function () {
//     checkUsername()
//   })

//   //checking username
//   function checkUsername() {
//     let username = $('#usernameReg').val();

//     let form = new FormData();
//     var data = {
//       action: 'read',
//       query: false,
//     };
//     form.append('username', username);
//     form.append('action', JSON.stringify(data))
//     $.ajax({
//       url: '../PHP_process/userData.php',
//       type: 'POST',
//       data: form,
//       processData: false,
//       contentType: false,
//       success: (response) => {
//         if (response == 'exist') {
//           $('#usernameReg').addClass('border-accent').removeClass('border-gray-400');
//           $('#usernameWarning').show();
//           usernameAvailable = false;
//         }
//         else {
//           $('#usernameField').removeClass('border-accent').addClass('border-gray-400');
//           $('#usernameWarning').hide();
//           usernameAvailable = true;
//         }
//       },
//       error: (error) => {
//         console.log(error)
//       }
//     })

//   }

//   //check if the email already existing
//   $('#personalEmail').on('change', function () {
//     checkEmailAddress()
//   })

//   function checkEmailAddress() {
//     let personalEmail = $('#personalEmail').val();

//     let form = new FormData();
//     var data = {
//       action: 'read',
//     };
//     form.append('personalEmail', personalEmail);
//     form.append('action', JSON.stringify(data))
//     $.ajax({
//       url: '../PHP_process/person.php',
//       type: 'POST',
//       data: form,
//       processData: false,
//       contentType: false,
//       success: (response) => {
//         if (response == 'Exist') {
//           $('#personalEmail').addClass('border-accent').removeClass('border-gray-400');
//           $('#emailExist').show();
//           personalEmailAvailable = false;
//         }
//         else {
//           $('#personalEmail').removeClass('border-accent').addClass('border-gray-400');
//           $('#emailExist').hide();
//           personalEmailAvailable = true;
//         }
//       },
//       error: (error) => {
//         console.log(error)
//       }
//     })
//   }
// });

// // Get the necessary elements
// const page1 = document.getElementById("page1");
// const page2 = document.getElementById("page2");
// const nextButton = document.getElementById("nextButton");
// const circle1 = document.getElementById("circle1");
// const circle2 = document.getElementById("circle2");
// const circle3 = document.getElementById("circle3");
// const connector1 = document.getElementById("connector1");
// const connector2 = document.getElementById("connector2");


// // PAGE 1
// nextButton.addEventListener("click", function () {
//   // Perform form validation
//   const firstName = document.getElementById("firstName");
//   const lastName = document.getElementById("lastName");
//   const email = document.getElementById("personalEmail");
//   const contactNumber = document.getElementById("contactNumber");
//   const studentNumber = document.getElementById("studentNumber");
//   const birthday = document.getElementById("birthday");
//   const genderOptions = document.querySelectorAll('input[name="gender"]');
//   const genderLabels = document.querySelectorAll('.gender-label');

//   // Check if any field is empty
//   let hasError = false;

//   if (firstName.value.trim() === "") {
//     firstName.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     firstName.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }

//   if (lastName.value.trim() === "") {
//     lastName.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     lastName.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }

//   if (email.value.trim() === "") {
//     email.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     email.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }

//   if (contactNumber.value.trim() === "") {
//     contactNumber.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     contactNumber.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }

//   if (studentNumber.value.trim() === "") {
//     studentNumber.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     studentNumber.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }

//   if (birthday.value.trim() === "") {
//     birthday.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     birthday.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }

//   let genderSelected = false;
//   for (let i = 0; i < genderOptions.length; i++) {
//     if (genderOptions[i].checked) {
//       genderSelected = true;
//       break;
//     }
//   }

//   if (!genderSelected) {
//     // If no gender is selected, highlight the text of the radio buttons in red
//     for (let i = 0; i < genderLabels.length; i++) {
//       genderLabels[i].style.color = "#991B1B";
//     }
//     hasError = true;
//   } else {
//     // If a gender is selected, set the default color for the text of the radio buttons
//     for (let i = 0; i < genderLabels.length; i++) {
//       genderLabels[i].style.color = "#000000";
//     }
//   }

//   // Check if any field is empty
//   if (hasError || !personalEmailAvailable) {
//     return;
//   }

//   // Hide Page 1 and show Page 2
//   page1.classList.add("hidden");
//   page2.classList.remove("hidden");
//   circle2.style.backgroundColor = "#991B1B";
//   connector1.style.backgroundColor = "#991B1B";
// });

// // PAGE 1 - Add event listeners for input fields to reset the border color
// firstName.addEventListener("input", function () {
//   if (firstName.value.trim() === "") {
//     firstName.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
//   } else {
//     firstName.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
//   }
// });

// lastName.addEventListener("input", function () {
//   if (lastName.value.trim() === "") {
//     lastName.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
//   } else {
//     lastName.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
//   }
// });

// email.addEventListener("input", function () {
//   if (email.value.trim() === "") {
//     email.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
//   } else {
//     email.style.borderColor = "var(--gray-300)"; // Set border color to gray-300
//   }
// });

// contactNumber.addEventListener("input", function () {
//   if (contactNumber.value.trim() === "") {
//     contactNumber.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
//   } else {
//     contactNumber.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
//   }
// });

// studentNumber.addEventListener("input", function () {
//   if (studentNumber.value.trim() === "") {
//     studentNumber.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
//   } else {
//     studentNumber.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
//   }
// });

// birthday.addEventListener("input", function () {
//   if (birthday.value.trim() === "") {
//     birthday.style.borderColor = "var(--accent-color)"; // Set accent color for empty field
//   } else {
//     birthday.style.borderColor = "#9CA3AF"; // Set border color to #9CA3AF
//   }
// });


// const page3 = document.getElementById("page3");
// const nextButtonPage2 = document.getElementById("nextButtonPage2");
// const backButton = document.getElementById("backButton");

// // PAGE 2
// nextButtonPage2.addEventListener("click", function () {
//   // Perform form validation
//   const college1 = document.getElementById("college");
//   const username = document.getElementById("usernameReg");
//   const batch = document.getElementById("batch");
//   const statusOptions = document.querySelectorAll('input[name="status"]');
//   const emailBSU = document.getElementById("email");
//   const password1 = document.getElementById("password1");
//   const confirmPassword = document.getElementById("confirmPassword");
//   const passwordMismatchError = document.getElementById("passwordMismatchError");
//   const reqInputAns = document.getElementById("reqInputAns");
//   const passDetailsDiv = document.getElementById("passDetailsDiv");
//   const confirmPassDetailsDiv = document.getElementById("confirmPassDetailsDiv");
//   const employmentStatus = document.getElementById("employment-status");
//   const employmentStatusDiv = document.getElementById("employment-status-div");

//   let hasError = false;
//   if (username.value.trim() === "") {
//     username.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     username.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }
//   if (college1.value.trim() === "") {
//     college1.style.borderColor = "#991B1B"; // Set accent color for empty field
//     hasError = true;
//   } else {
//     college1.style.borderColor = "#9CA3AF"; // Set default color for filled field
//   }

//   // Check if the selected status is "Student"
//   let isStudent = false;
//   for (let i = 0; i < statusOptions.length; i++) {
//     if (statusOptions[i].checked && statusOptions[i].value === "Student") {
//       isStudent = true;
//       break;
//     }
//   }

//   if (!isStudent) {
//     // If the selected status is not "Student," perform validation for the employmentStatus field
//     if (employmentStatus.value.trim() === "") {
//       employmentStatusDiv.classList.add("validation-border"); // Add validation class to show red border
//       hasError = true;
//     } else {
//       employmentStatusDiv.classList.remove("validation-border"); // Remove validation class to reset border
//     }
//   }

//   let statusSelected = false;
//   for (let i = 0; i < statusOptions.length; i++) {
//     if (statusOptions[i].checked) {
//       statusSelected = true;
//       break;
//     }
//   }

//   if (!statusSelected) {
//     // If no status is selected, highlight the radio buttons' labels in accent color
//     const statusLabels = document.querySelectorAll('.status-label');
//     for (let i = 0; i < statusLabels.length; i++) {
//       statusLabels[i].style.color = "#991B1B";
//     }
//     hasError = true;
//   } else {
//     // If a status is selected, set the default color for the radio buttons' labels
//     const statusLabels = document.querySelectorAll('.status-label');
//     for (let i = 0; i < statusLabels.length; i++) {
//       statusLabels[i].style.color = "#000000";
//     }
//   }

//   // Check if password1 field is empty or does not meet the condition
//   if (!password1.checkValidity() || !isPasswordValid(password1.value)) {
//     passDetailsDiv.style.borderColor = "#991B1B"; // Set accent color for empty field or invalid password
//     document.querySelector(".note").style.color = "#991B1B"; // Change note color to accent color
//     hasError = true;
//   } else {
//     passDetailsDiv.style.borderColor = "#9CA3AF"; // Set default color for filled field
//     document.querySelector(".note").style.color = "#000000"; // Change note color back to default
//   }

//   // Check if confirmPassword field is empty or does not match password1 field
//   if (confirmPassword.value.trim() === "" || confirmPassword.value !== password1.value) {
//     confirmPassDetailsDiv.style.borderColor = "#991B1B"; // Set accent color for empty field or mismatch
//     passwordMismatchError.classList.remove("hidden"); // Show password mismatch error message
//     hasError = true;
//   } else {
//     confirmPassDetailsDiv.style.borderColor = "#9CA3AF"; // Set default color for filled field
//     passwordMismatchError.classList.add("hidden"); // Hide password mismatch error message
//   }

//   // Check if any fields have errors
//   if (hasError || !usernameAvailable) {
//     reqInputAns.classList.remove("hidden"); // Show error message
//     return; // Exit the function if there are errors
//   } else {
//     reqInputAns.classList.add("hidden"); // Hide error message
//   }

//   // Hide Page 2 and show Page 3
//   page2.classList.add("hidden");
//   page3.classList.remove("hidden");
//   circle3.style.backgroundColor = "#991B1B";
//   connector2.style.backgroundColor = "#991B1B";

//   // Display summary information
//   const fullName = document.getElementById("firstName").value + " " + document.getElementById("lastName").value;
//   const college = document.getElementById("college").value;
//   const emailPersonal = document.getElementById("personalEmail").value;
//   const studentNumber = document.getElementById("studentNumber").value;
//   const emailBulsu = document.getElementById("email").value;
//   const password = document.getElementById("password1").value;

//   document.getElementById("displayFullName").textContent = fullName;
//   document.getElementById("displayCollege").textContent = college;
//   document.getElementById("displayEmailPersonal").textContent = emailPersonal;
//   document.getElementById("displayStudentNumber").textContent = studentNumber;
//   document.getElementById("displayEmailBulsu").textContent = emailBulsu;
//   document.getElementById("displayPassword").textContent = password;
// });

// // Function to check if the password meets the condition
// function isPasswordValid(password) {
//   // Use regular expression to check for 8 or more characters with a mix of letters, numbers, and symbols
//   const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
//   return passwordRegex.test(password);
// }


// // Function to show an error message
// function showErrorMessage() {
//   const requiredFields = accountForm.querySelectorAll(':invalid');
//   const reqInputAns = document.getElementById('reqInputAns');

//   // Highlight the empty fields with a red border
//   requiredFields.forEach(function (field) {
//     field.style.borderColor = '#991B1B';
//   });

//   // Show the error message
//   reqInputAns.classList.remove('hidden');
// }


// backButton.addEventListener("click", function () {
//   // Hide Page 2 and show Page 1
//   page2.classList.add("hidden");
//   page1.classList.remove("hidden");
//   circle2.style.backgroundColor = "#FFFFFF";
//   connector1.style.backgroundColor = "#FFFFFF";
// });

// const backButtonPage3 = document.getElementById("backButtonPage3");

// backButtonPage3.addEventListener("click", function () {
//   // Hide Page 3 and show Page 2
//   page3.classList.add("hidden");
//   page2.classList.remove("hidden");
//   circle3.style.backgroundColor = "#FFFFFF";
//   connector2.style.backgroundColor = "#FFFFFF";
// });

// //Toggle password for Page2
// document.getElementById('togglePassword').addEventListener('click', function () {
//   var passwordInput = document.getElementById('password1');
//   if (passwordInput.type === 'password') {
//     passwordInput.type = 'text';
//     this.classList.remove('fa-eye-slash');
//     this.classList.add('fa-eye');
//   } else {
//     passwordInput.type = 'password';
//     this.classList.remove('fa-eye');
//     this.classList.add('fa-eye-slash');
//   }
// });

// //Confirmation Toggle Password for Page2
// document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
//   var confirmPasswordInput = document.getElementById('confirmPassword');
//   if (confirmPasswordInput.type === 'password') {
//     confirmPasswordInput.type = 'text';
//     this.classList.remove('fa-eye-slash');
//     this.classList.add('fa-eye');
//   } else {
//     confirmPasswordInput.type = 'password';
//     this.classList.remove('fa-eye');
//     this.classList.add('fa-eye-slash');
//   }
// });

// //Login Toggle Password
// document.getElementById('toggleLoginPassword').addEventListener('click', function () {
//   var loginPasswordInput = document.getElementById('password');
//   if (loginPasswordInput.type === 'password') {
//     loginPasswordInput.type = 'text';
//     this.classList.remove('fa-eye-slash');
//     this.classList.add('fa-eye');
//   } else {
//     loginPasswordInput.type = 'password';
//     this.classList.remove('fa-eye');
//     this.classList.add('fa-eye-slash');
//   }
// });

// //Hide and Show Employement Status
// // Get the radio buttons and the employment status div
// const alumniRadioButton = document.getElementById("alumni");
// const studentRadioButton = document.getElementById("student");
// const employmentStatusDiv = document.getElementById("employment-status-div");

// // Add event listeners to the radio buttons
// alumniRadioButton.addEventListener("change", toggleEmploymentStatus);
// studentRadioButton.addEventListener("change", toggleEmploymentStatus);

// // Function to toggle the display of the employment status div based on the selected radio button
// function toggleEmploymentStatus() {
//   if (alumniRadioButton.checked) {
//     employmentStatusDiv.style.display = "block";
//   } else if (studentRadioButton.checked) {
//     employmentStatusDiv.style.display = "none";
//   }
// }

// Call the function on page load to set the initial state based on the default checked radio button


//NEW-REG-FORM

const buttons = document.getElementById('buttons');
const statusHeader = document.getElementById('statusHeader');

const alumniButton = document.getElementById('alumniButton');
const studentButton = document.getElementById('studentButton');


const alumniTab = document.getElementById('alumniTab');
const alumniPage1 = document.getElementById('alumniPage1');

const studentTab = document.getElementById('studentTab');
const studentPage1 = document.getElementById('studentPage1');

const alumniBackButton = document.getElementById('alumniBackButton');
const alumniBackButton2 = document.getElementById('alumniBackButton2');

const alumniNextButton = document.getElementById('alumniNextButton');
const studentBackButton = document.getElementById('studentBackButton');
const studentNextButton = document.getElementById('studentNextButton');

alumniButton.addEventListener('click', () => {
  buttons.style.display = 'none';
  statusHeader.style.display = 'none';
  alumniTab.style.display = 'block';
  alumniPage1.style.display = 'block';

});

studentButton.addEventListener('click', () => {
  buttons.style.display = 'none';
  statusHeader.style.display = 'none';
  alumniTab.style.display = 'block';
  alumniPage1.style.display = 'block';
});

alumniBackButton.addEventListener('click', () => {
  buttons.style.display = 'flex';
  statusHeader.style.display = 'block';
  alumniTab.style.display = 'none';
});

function goToAlumniPage2() {
  // Validate the input fields on Page 1
  if (validatePage1()) {
    // If Page 1 is valid, show Page 2
    document.getElementById("alumniPage2").style.display = "block";
    document.getElementById("alumniPage1").style.display = "none"; // Hide Page 1
    document.getElementById("alumniTab").scrollTop = 0; // Scroll to the top of the tab
  }
}

function validatePage1() {
  const firstName = document.getElementById("firstName");
  const lastName = document.getElementById("lastName");
  const contactNumber = document.getElementById("contactNumber");
  const studentNumber = document.getElementById("studentNumber");
  const birthday = document.getElementById("birthday");
  const streetNumber = document.getElementById("address");
  const employmentStatus = document.getElementById("employmentStatus");
  const personalEmail = document.getElementById("personalEmail");
  const bulsuEmail = document.getElementById("bulsuEmail");

  // Remove red borders and error messages (in case there were previous errors)
  removeRedBorder(firstName);
  removeRedBorder(lastName);
  removeRedBorder(contactNumber);
  removeRedBorder(studentNumber);
  removeRedBorder(birthday);
  removeRedBorder(address);
  removeRedBorder(employmentStatus);
  removeRedBorder(personalEmail);
  removeRedBorder(bulsuEmail);

  // Remove error messages
  removeErrorMessage("contactNumberError");
  removeErrorMessage("bulsuEmailError");

  // Check if all fields are filled
  let isValid = true;

  if (firstName.value === "") {
    addRedBorder(firstName);
    isValid = false;
  }

  if (lastName.value === "") {
    addRedBorder(lastName);
    isValid = false;
  }


  if (contactNumber.value === "") {
    addRedBorder(contactNumber);
    addErrorMessage("contactNumberError", "Please enter a valid Philippine contact number.");
    isValid = false;
  } else {
    // Validate the Philippine contact number
    const contactNumberRegex = /^\+639\d{9}$/;
    if (!contactNumberRegex.test(contactNumber.value)) {
      addRedBorder(contactNumber);
      addErrorMessage("contactNumberError", "Invalid contact number format. Please use the format +639xxxxxxxxx.");
      isValid = false;
    }
  }

  if (studentNumber.value === "") {
    addRedBorder(studentNumber);
    isValid = false;
  }

  if (birthday.value === "") {
    addRedBorder(birthday);
    isValid = false;
  }

  if (address.value === "") {
    addRedBorder(address);
    isValid = false;
  }

  if (personalEmail.value === "") {
    addRedBorder(personalEmail);
    isValid = false;
  }

  if (bulsuEmail.value === "") {
    addRedBorder(bulsuEmail);
    addErrorMessage("bulsuEmailError", "Please enter a valid BulSU email address or 'N/A' if not applicable.");
    isValid = false;
  } else if (!bulsuEmail.value.endsWith("@bulsu.edu.ph") && bulsuEmail.value.toLowerCase() !== "n/a") {
    addRedBorder(bulsuEmail);
    addErrorMessage("bulsuEmailError", "Invalid BulSU email address format. It should end with @bulsu.edu.ph or use 'N/A'.");
    isValid = false;
  }

  // Add similar checks for the remaining input fields

  return isValid; // All validations passed
}

function addRedBorder(element) {
  element.style.border = "1px solid red";
}

function removeRedBorder(element) {
  element.style.border = "";
}

function addErrorMessage(id, message) {
  const errorElement = document.getElementById(id);
  errorElement.textContent = message;
  errorElement.style.color = "red";
}

function removeErrorMessage(id) {
  const errorElement = document.getElementById(id);
  errorElement.textContent = "";
}


function goBackAlumniPage2() {
  // Hide Page 2 and show Page 1
  document.getElementById("alumniPage2").style.display = "none"; // Hide Page 2
  document.getElementById("alumniPage1").style.display = "block"; // Show Page 1
  document.getElementById("alumniTab").scrollTop = 0; // Scroll to the top of the tab
}

// Function to toggle between "Batch" and "Year" dropdowns
function populateBatchDropdown() {
  const batchDropdown = document.getElementById("batch");
  const currentYear = new Date().getFullYear(); // Get the current year

  for (let year = currentYear; year >= 1904; year--) {
    const option = document.createElement("option");
    option.value = year.toString();
    option.text = year.toString();
    batchDropdown.appendChild(option);
  }
}

// Function to toggle between "Batch" and "Year" dropdowns
function toggleYearOrBatch(isStudent) {
  const batchContainer = document.getElementById("batchContainer");
  const yearContainer = document.getElementById("yearContainer");

  if (isStudent) {
    batchContainer.style.display = "none";
    yearContainer.style.display = "block";
  } else {
    batchContainer.style.display = "block";
    yearContainer.style.display = "none";
  }
}

// Add click event listeners to the "Alumni" and "Student" buttons

alumniButton.addEventListener("click", function () {
  // If the "Alumni" button is clicked, set toggleYearOrBatch to false
  toggleYearOrBatch(false);
});

studentButton.addEventListener("click", function () {
  // If the "Student" button is clicked, set toggleYearOrBatch to true
  toggleYearOrBatch(true);
});


// Call the function to populate the Batch dropdown when the page loads
window.addEventListener("load", function () {
  populateBatchDropdown();
});

// Get password and confirm password elements and their toggle icons
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirmPassword");
const passwordToggle = document.getElementById("passwordToggle");
const confirmPasswordToggle = document.getElementById("confirmPasswordToggle");

// Function to toggle password visibility
function togglePasswordVisibility(inputElement, toggleElement) {
  if (inputElement.type === "password") {
    inputElement.type = "text";
    toggleElement.classList.remove("fa-eye-slash");
    toggleElement.classList.add("fa-eye");
  } else {
    inputElement.type = "password";
    toggleElement.classList.remove("fa-eye");
    toggleElement.classList.add("fa-eye-slash");
  }
}

// Add click event listeners to the toggle icons
passwordToggle.addEventListener("click", () => {
  togglePasswordVisibility(passwordInput, passwordToggle);
});

confirmPasswordToggle.addEventListener("click", () => {
  togglePasswordVisibility(confirmPasswordInput, confirmPasswordToggle);
});

function validateAlumniFormPage2() {
  // Get form elements
  const form = document.getElementById("alumniFormPage2");
  const college = document.getElementById("college");
  const batch = document.getElementById("batch");
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirmPassword");

  // Reset previous validation styles and error messages
  const inputs = [college, batch, username, password, confirmPassword];
  inputs.forEach((input) => {
    input.style.border = "1px solid #ced4da"; // Reset border to default
  });

  const errorMessages = document.getElementsByClassName("error-message");
  for (let i = 0; i < errorMessages.length; i++) {
    errorMessages[i].style.display = "none"; // Hide previous error messages
  }

  // Validate input fields
  let isValid = true;

  if (college.value === "") {
    college.style.border = "1px solid red"; // Highlight in red if empty
    isValid = false;
  }

  if (batch.value === "") {
    batch.style.border = "1px solid red"; // Highlight in red if empty
    isValid = false;
  }

  if (username.value === "") {
    username.style.border = "1px solid red"; // Highlight in red if empty
    isValid = false;
  }

  // Password validation
  if (password.value === "") {
    password.style.border = "1px solid red"; // Highlight in red if empty
    isValid = false;
  } else if (!isPasswordValid(password.value)) {
    password.style.border = "1px solid red"; // Highlight in red if not valid
    isValid = false;
    document.getElementById("passwordError").style.display = "block";
  }

  if (confirmPassword.value === "") {
    confirmPassword.style.border = "1px solid red"; // Highlight in red if empty
    isValid = false;
  } else if (password.value !== confirmPassword.value) {
    confirmPassword.style.border = "1px solid red"; // Highlight in red if not matching
    isValid = false;
    document.getElementById("confirmPasswordError").style.display = "block";
  }

  if (isValid) {
    // All fields are valid, you can submit the form or proceed to the next step.
    // In this example, we'll just show the registration complete modal.
    openRegistrationCompleteModal(); // Call the function to open the modal
  }
}

// Function to update password complexity error messages
function updatePasswordComplexity(password) {
  const lengthReq = document.getElementById("lengthReq");
  const upperCaseReq = document.getElementById("upperCaseReq");
  const lowerCaseReq = document.getElementById("lowerCaseReq");
  const digitReq = document.getElementById("digitReq");
  const specialCharReq = document.getElementById("specialCharReq");

  lengthReq.style.color = password.length >= 8 ? "green" : "red";
  upperCaseReq.style.color = /[A-Z]/.test(password) ? "green" : "red";
  lowerCaseReq.style.color = /[a-z]/.test(password) ? "green" : "red";
  digitReq.style.color = /[0-9]/.test(password) ? "green" : "red";
  specialCharReq.style.color = /[@$!%*?&_]/.test(password) ? "green" : "red";
}

// Add input event listener to the password field
passwordInput.addEventListener("input", function () {
  const password = passwordInput.value;
  updatePasswordComplexity(password);

  // Check password validity and display/hide error message
  const passwordError = document.getElementById("passwordError");
  const isValid = isPasswordValid(password);
  if (isValid) {
    passwordError.style.display = "none";
    passwordInput.style.border = "1px solid #ced4da"; // Reset border to default
  } else {
    passwordError.style.display = "block";
    passwordInput.style.border = "1px solid red"; // Highlight in red if not valid
  }
});

if (confirmPassword.value === "") {
  confirmPassword.style.border = "1px solid red"; // Highlight in red if empty
  isValid = false;
} else if (password.value !== confirmPassword.value) {
  confirmPassword.style.border = "1px solid red"; // Highlight in red if not matching
  isValid = false;
  document.getElementById("confirmPasswordError").style.display = "block";
}

// Function to validate if password meets complexity requirements
function isPasswordValid(password) {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,}$/;
  return regex.test(password);
}

// Function to open the registration complete modal
function openRegistrationCompleteModal() {
  const registrationModal = document.getElementById('registrationModal');
  registrationModal.classList.remove('hidden');
}

function resetFormFields() {
  // Reset Page 1 input fields
  const firstNameInput = document.getElementById("firstName");
  const lastNameInput = document.getElementById("lastName");
  const contactNumberInput = document.getElementById("contactNumber");
  const studentNumberInput = document.getElementById("studentNumber");
  const birthdayInput = document.getElementById("birthday");
  const maleRadio = document.getElementById("male");
  const femaleRadio = document.getElementById("female");
  const addressInput = document.getElementById("address");
  const employmentStatusInput = document.getElementById("employmentStatus");
  const personalEmailInput = document.getElementById("personalEmail");
  const bulsuEmailInput = document.getElementById("bulsuEmail");

  // Reset the values of Page 1 form fields
  firstNameInput.value = "";
  lastNameInput.value = "";
  contactNumberInput.value = "";
  studentNumberInput.value = "";
  birthdayInput.value = "";
  maleRadio.checked = true; // Reset gender radio buttons
  addressInput.value = "";
  employmentStatusInput.value = "employed"; // Reset employment status dropdown
  personalEmailInput.value = "";
  bulsuEmailInput.value = "";

  // Clear any error messages on Page 1
  const contactNumberError = document.getElementById("contactNumberError");
  contactNumberError.style.display = "none"; // Clear the error message

  const bulsuEmailError = document.getElementById("bulsuEmailError");
  bulsuEmailError.style.display = "none"; // Clear the error message

  firstNameInput.style.border = "1px solid #ced4da";
  lastNameInput.style.border = "1px solid #ced4da";
  contactNumberInput.style.border = "1px solid #ced4da";
  studentNumberInput.style.border = "1px solid #ced4da";
  birthdayInput.style.border = "1px solid #ced4da";
  addressInput.style.border = "1px solid #ced4da";
  personalEmailInput.style.border = "1px solid #ced4da";
  bulsuEmailInput.style.border = "1px solid #ced4da";

  // Reset Page 2 input fields
  const collegeInput = document.getElementById("college");
  const batchInput = document.getElementById("batch");
  const usernameInput = document.getElementById("username");
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirmPassword");

  // Reset the values of Page 2 form fields
  usernameInput.value = "";
  passwordInput.value = "";
  confirmPasswordInput.value = "";

  // Clear any error messages on Page 2
  const passwordError = document.getElementById("passwordError");
  passwordError.style.display = "none"; // Clear the error message
  const confirmPasswordError = document.getElementById("confirmPasswordError");
  confirmPasswordError.style.display = "none"; // Clear the error message

  // Reset border colors to gray-300 for Page 2 input fields
  collegeInput.style.border = "1px solid #ced4da";
  batchInput.style.border = "1px solid #ced4da";
  usernameInput.style.border = "1px solid #ced4da";
  passwordInput.style.border = "1px solid #ced4da";
  confirmPasswordInput.style.border = "1px solid #ced4da";
}


// Add an event listener to the "Back" button to reset form fields
alumniBackButton.addEventListener("click", function () {
  resetFormFields(); // Call the function to reset form fields
  // Additional code to navigate back to the previous page or hide elements
});



