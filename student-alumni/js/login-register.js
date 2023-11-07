

let usernameAvailable = true;
let personalEmailAvailable = true;
$(document).ready(function () {
  const today = new Date().toISOString().split('T')[0];
  $('input[type="date"]').attr('max', today);

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


// for registration
$(document).ready(function () {

  // alumni is selected
  $('#alumniStatus').on('click', function () {
    $('.emailExistingMsg').addClass('hidden')
    $('.selectionStatus').addClass('hidden')
    $('#alumniForm').removeClass('hidden')
  })

  $('#studentStatus').on('click', function () {
    $('.emailExistingMsg').addClass('hidden')
    $('.selectionStatus').addClass('hidden')
    $('#studentForm').removeClass('hidden');
  })
  // cancel registration
  $('.cancelBtnReg').on('click', function () {
    $('.selectionStatus').removeClass('hidden')
    $('.fieldFormReg').addClass('hidden')
    //restart forms
    $('#alumniForm')[0].reset();
    $('#studentForm')[0].reset();

  })

  // password eye
  $('#alumniPassEye').on('click', function () {
    const icon = "#alumniPassEye"
    const password = "#accountPass"
    togglePassword(icon, password)
  })

  // confirm password eye
  $('#alumniConfirmPassEye').on('click', function () {
    const icon = "#alumniConfirmPassEye"
    const password = "#confirmPass"
    togglePassword(icon, password)
  })

  function togglePassword(icon, password) {
    let eyeCurrentState = $(icon).attr('icon');

    if (eyeCurrentState == 'bi:eye-fill') {
      // open the password
      $(icon).attr('icon', 'el:eye-close')
      $(password).attr('type', 'text');
    } else {
      // close the password
      $(icon).attr('icon', 'bi:eye-fill')
      $(password).attr('type', 'password');
    }
  }

  $('#nextAlumni').on('click', function () {
    // check first if all the input fields are complete
    if (checkInputField('.requiredAlumni')) {
      // check if the email is already existing or not
      const emailAdd = $('#personalEmail').val();
      const column = 'personal_email';

      if (emailAdd.endsWith('@gmail.com')) {
        $('.emailInvalidMsg').addClass('hidden')
        checkEmailAddress(emailAdd, column)
          .then(resolve => {
            if (resolve !== 'Existing') { //proceed to the next
              $('.emailExistingMsg').addClass('hidden')
              $('.personalInfo').addClass('hidden')
              $('#accountInfoAlumni').removeClass('hidden')
            }
            else $('.emailExistingMsg').removeClass('hidden')
          })
      } else $('.emailInvalidMsg').removeClass('hidden')


    }
  })

  // check if the input email is already existing
  function checkEmailAddress(emailAdd, column) {
    const action = { action: 'checkPersonEmail' };
    const formData = new FormData();
    formData.append('action', JSON.stringify(action));
    formData.append('email', emailAdd);
    formData.append('column', column)

    return new Promise((resolve) => {
      $.ajax({
        url: '../PHP_process/userData.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: response => { resolve(response) },

      })
    })
  }

  // back to personal information field
  $('#backAlumni').on('click', function () {
    $('.personalInfo').removeClass('hidden')
    $('#accountInfoAlumni').addClass('hidden')
  })

  let isUsernameValid = false
  let isPasswordStrong = false

  // submit the form and create the account
  $('#alumniForm').on('submit', function (e) {
    e.preventDefault();
    if (checkInputField('.requiredAlumni2')) {
      let accountPass = $('#accountPass').val().trim();
      let confirmPass = $('#confirmPass').val().trim();

      if (isUsernameValid) {
        // check account password if match
        if (accountPass === confirmPass) {
          // register new account
          let formData = $('#alumniForm')[0];
          let action = {
            action: 'create',
            account: 'User'
          }

          let data = new FormData(formData)
          data.append('action', JSON.stringify(action));
          data.append('status', 'Alumni')
          data.append('bulsuEmail', '')

          $.ajax({
            url: '../PHP_process/userData.php',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: response => {
              if (response === 'Success') $('#successJobModal').removeClass('hidden')
            }
          })
          $('.errorPassNotMatch').addClass('hidden')
        }
        else $('.errorPassNotMatch').removeClass('hidden')
      }

    }
  })

  // check alumni username is available
  $('#username').on('change', function () {
    let usernameVal = $(this).val();
    isUsernameAvailable(usernameVal)
      .then(response => {
        // user name is already exist
        if (response == 'exist') {
          $('.usernameMsg').removeClass('hidden')
          isUsernameValid = false
        }
        else {
          // valid
          $('.usernameMsg').addClass('hidden')
          isUsernameValid = true
        }
      })
  })

  function checkInputField(className) {
    let isCompleted = true;
    $(className).each(function () {
      let element = $(this)
      let elementVal = element.val().trim();

      if (elementVal === '') {
        isCompleted = false
        element.removeClass('border-gray-400').addClass('border-red-500')
      }
      else {
        element.addClass('border-gray-400').removeClass('border-red-500')
      }
    })


    return isCompleted
  }


  // username availability checker
  function isUsernameAvailable(username) {
    const action = {
      action: 'read',
      query: 0
    }

    const formData = new FormData();
    formData.append('action', JSON.stringify(action))
    formData.append('username', username);

    // return the response to be use in other function
    return new Promise((resolve, reject) => {
      $.ajax({
        url: '../PHP_process/userData.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: response => {
          resolve(response)
        },
        error: error => { reject(error) },
      })
    })

  }

  //add batch option
  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const lastYear = 1904

  for (let i = currentYear; i > lastYear; i--) {
    const option = $('<option>').val(i).text(i);
    $('#batchAlumni').append(option)
  }

  $('#college').on('change', function () {
    const college = $(this).val();
    const container = '#courses'
    addOptionCourse(college, container);
  })
  $('#studCollege').on('change', function () {
    const college = $(this).val();
    const container = '#courseStudent'
    addOptionCourse(college, container);
  })

  function addOptionCourse(college, container) {
    let data = {
      action: "courses",
      query: true,
    };

    const formData = new FormData();
    formData.append('colCode', college);
    formData.append('data', JSON.stringify(data));

    $.ajax({
      url: '../PHP_process/collegeDB.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: response => {
        if (response.response === 'Success') {
          $(container).find('option:not(:first)').remove();
          let length = response.courseName.length;

          // add the college in the option
          for (let i = 0; i < length; i++) {
            const courseID = response.courseID[i];
            const courseName = response.courseName[i];
            const option = $('<option>').val(courseID).text(courseName);
            $(container).append(option)
          }
        }
      }
    })
  }
  // student form process

  $('#nextStudent').on('click', function () {
    // check first if all the input fields are complete
    if (checkInputField('.requiredStudenField')) {

      //to verify if the email set as valid bulsu email
      let isBulSUEmailValid = false
      isBulSUEmailValid = $('#studbulsuEmail').val().endsWith('bulsu.edu.ph')

      // check if the email is existing or not
      const studPersonEmail = $('#studPersonalEmail').val()
      const column = 'personal_email';

      if (studPersonEmail.endsWith('@gmail.com')) {
        $('.emailInvalidMsg').addClass('hidden')
        checkEmailAddress(studPersonEmail, column)
          .then(resolve => {
            if (resolve !== 'Existing') {

              if (isBulSUEmailValid) {
                // check if the bulsu email is not already existing
                let bulsuEmail = $('#studbulsuEmail').val()
                let columnBulsu = 'bulsu_email';
                $('.emailExistingMsg').addClass('hidden')

                checkEmailAddress(bulsuEmail, columnBulsu)
                  .then(resolve => {
                    if (resolve !== 'Existing') { //proceed to the next
                      $('.emailExistingMsgBulsu').addClass('hidden')
                      $('#bulsuEmailError').addClass('hidden')
                      $('.personalInfo').addClass('hidden')
                      $('#accountInfoStudent').removeClass('hidden')

                    } else $('.emailExistingMsgBulsu').removeClass('hidden')
                  })
              } else $('#bulsuEmailError').removeClass('hidden')

            }
            else $('.emailExistingMsg').removeClass('hidden')
          })
      } else $('.emailInvalidMsg').removeClass('hidden')


    }
  })


  // back to personal information
  $('#backStudent').on('click', function () {
    $('.personalInfo').removeClass('hidden')
    $('#accountInfoStudent').addClass('hidden')
  })


  // submit the form
  $('#studentForm').on('submit', function (e) {
    e.preventDefault();

    // check first if all the input are complete
    if (checkInputField('.requiredStudent2')) {

      // check if the username is valid
      if (isUsernameValid && isPasswordStrong) {
        const studAccountPass = $('#studAccountPass').val()
        const studConfirmPass = $('#studConfirmPass').val()

        // check if password matches
        if (studAccountPass == studConfirmPass) {
          $('.errorPassNotMatch').addClass('hidden')

          // register new account
          let formData = $('#studentForm')[0];
          let action = {
            action: 'create',
            account: 'User'
          }

          let data = new FormData(formData)
          data.append('action', JSON.stringify(action));
          data.append('status', 'Student')

          $.ajax({
            url: '../PHP_process/userData.php',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: response => {
              if (response === 'Success') $('#successJobModal').removeClass('hidden')
            },
            error: error => { console.log(error) }
          })

        } else $('.errorPassNotMatch').removeClass('hidden')
      }
    }
  })


  // check password if meets the requirement of strong password
  $('#accountPass').on('input', function () {
    let passwordVal = $(this).val();
    // special characters, numbers, lower and upper case
    const strongpassReq = /[A-Z]/.test(passwordVal) && /[a-z]/.test(passwordVal) && /[^A-Za-z0-9]/.test(passwordVal) && /[0-9]/.test(passwordVal);
    const requiredPassLength = 8

    if (strongpassReq && passwordVal.length >= requiredPassLength) { //strong password
      $('.passwordStatus')
        .text('Strong password')
        .removeClass('text-red-500')
        .addClass('text-blue-500')
      isPasswordStrong = true
    }
    else {
      $('.passwordStatus')
        .text('Weak password')
        .addClass('text-red-500')
        .removeClass('text-blue-500')
      isPasswordStrong = false
    }


  })

  // password eye
  $('#studentPassEye').on('click', function () {
    const icon = "#studentPassEye"
    const password = "#studAccountPass"
    togglePassword(icon, password)
  })

  // confirm password eye
  $('#studentConfirmPassEye').on('click', function () {
    const icon = "#studentConfirmPassEye"
    const password = "#studConfirmPass"
    togglePassword(icon, password)
  })

  // check student username is available
  $('#studUsername').on('change', function () {
    let usernameVal = $(this).val();
    isUsernameAvailable(usernameVal)
      .then(response => {
        // user name is already exist
        if (response == 'exist') {
          $('.usernameMsg').removeClass('hidden')
          isUsernameValid = false
        }
        else {
          // valid
          $('.usernameMsg').addClass('hidden')
          isUsernameValid = true
        }
      })
  })

  // check password if meets the requirement of strong password
  $('#studAccountPass').on('input', function () {
    let passwordVal = $(this).val();
    const strongpassReq = /[A-Z]/.test(passwordVal) && /[a-z]/.test(passwordVal) && /[^A-Za-z0-9]/.test(passwordVal) && /[0-9]/.test(passwordVal);
    const requiredPassLength = 8

    if (strongpassReq && passwordVal.length >= requiredPassLength) { //strong password
      $('.passwordStatus')
        .text('Strong password')
        .removeClass('text-red-500')
        .addClass('text-blue-500')
      isPasswordStrong = true
    }
    else {
      $('.passwordStatus')
        .text('Weak password')
        .addClass('text-red-500')
        .removeClass('text-blue-500')
      isPasswordStrong = false
    }


  })
})

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
