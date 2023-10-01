let usernameAvailable = true;
let personalEmailAvailable = true;
$(document).ready(function () {

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
    $('.selectionStatus').addClass('hidden')
    $('#alumniForm').removeClass('hidden')
  })
  // cancel registration
  $('.cancelBtnReg').on('click', function () {
    $('.selectionStatus').removeClass('hidden')
    $('.fieldFormReg').addClass('hidden')
  })


  $('#nextAlumni').on('click', function () {
    // check first if all the input fields are complete
    if (checkInputField('.requiredAlumni')) {
      $('.personalInfo').addClass('hidden')
      $('#accountInfoAlumni').removeClass('hidden')
    }
  })

  $('#alumniForm').on('submit', function (e) {
    e.preventDefault();
    if (checkInputField('.requiredAlumni2')) {
      let accountPass = $('#accountPass').val().trim();
      let confirmPass = $('#confirmPass').val().trim();

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
          },
          error: error => { console.log(error) }
        })
        $('.errorPassNotMatch').addClass('hidden')
      }
      else $('.errorPassNotMatch').removeClass('hidden')

    }
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


  //add batch option
  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const lastYear = 1904

  for (let i = currentYear; i > lastYear; i--) {
    const option = $('<option>').val(i).text(i);
    $('#batchAlumni').append(option)
  }
})
