
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

  if (batch.value.trim() === "") {
    batch.style.borderColor = "#991B1B"; // Set accent color for empty field
    hasError = true;
  } else {
    batch.style.borderColor = "#9CA3AF"; // Set default color for filled field
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

// Call the function on page load to set the initial state based on the default checked radio button
toggleEmploymentStatus();


